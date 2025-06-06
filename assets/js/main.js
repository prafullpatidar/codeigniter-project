'use strict';
// Initializes FriendlyChat.
function FriendlyChat() {
  
  this.checkSetup();

  // Shortcuts to DOM Elements.
  this.messageList = document.getElementById('messages');
  this.messageForm = document.getElementById('message-form');
  this.messageInput = document.getElementById('message');
  this.contactList = document.getElementById('contact-list');
  this.submitButton = document.getElementById('submit');
  this.submitImageButton = document.getElementById('submitImage');
  this.imageForm = document.getElementById('image-form');
  this.mediaCapture = document.getElementById('mediaCapture');
  this.userPic = document.getElementById('user-pic');
  this.userName = document.getElementById('user-name');
  this.signInButton = document.getElementById('sign-in');
  this.signOutButton = document.getElementById('sign-out');
  this.signInSnackbar = document.getElementById('must-signin-snackbar');
  this.bodyContainer = document.getElementById('body-container');
  this.messageCard = document.getElementById("messages-card-container");
  this.messageCount = document.getElementById("message-notification-counter");  
  
  this.messageForm.addEventListener('submit', this.saveMessage.bind(this));
  this.signOutButton.addEventListener('click', this.signOut.bind(this));
  this.signInButton.addEventListener('click', this.signIn.bind(this));
  
  var buttonTogglingHandler = this.toggleButton.bind(this);
  this.messageInput.addEventListener('keyup', buttonTogglingHandler);
  this.messageInput.addEventListener('change', buttonTogglingHandler);

  this.selectedContact = null;
  this.topContact = null;
  this.loggedIn = false;
  
  this.submitImageButton.addEventListener('click', function(e) {
    e.preventDefault();
    this.mediaCapture.click();
  }.bind(this));
  this.mediaCapture.addEventListener('change', this.saveImageMessage.bind(this));
  this.initFb = this.initFirebase.bind(this);
  this.signIn = this.signIn.bind(this);
  this.signOut = this.signOut.bind(this);
  this.loadContacts = this.loadContacts.bind(this);
  this.getMessageCount = this.getMessageCount.bind(this);
  this.saveMessagingDeviceToken = this.saveMessagingDeviceToken.bind(this);
  this.displayContacts = this.displayContacts.bind(this);
  this.checkForContactCount = this.checkForContactCount.bind(this);
  this.initFb();

}

// Sets up shortcuts to Firebase features and initiate firebase auth.
FriendlyChat.prototype.initFirebase = function() {
   // Shortcuts to Firebase SDK features.
  console.log("Initializing firebase... ")

  if(this.auth==null){
    console.log("auth is null");
    this.auth = firebase.auth();
  }
  if(this.database==null){
    this.database = firebase.database();
  }
  this.database.ref('users').remove();
  this.storage = firebase.storage();

  // Initiates Firebase auth and listen to auth state changes.
  this.auth.onAuthStateChanged(this.onAuthStateChanged.bind(this));
};

// Triggers when the auth state change for instance when the user signs-in or signs-out.
FriendlyChat.prototype.onAuthStateChanged = function(user) {
    if (user) { // User is signed in!
      if(session_token=='')
      {
        this.signOut();
        return;
      }
      else
      {
        if(fb_logout=='')
        {
          fb_logout='N';
          this.signOut();
          return;
        }
      }
      // Get profile pic and user's name from the Firebase user object.
      var profilePicUrl = user.photoURL;
  
      //set user to realtime datebase
      this.userRef = this.database.ref('users').child(user.uid);
      this.loggedIn = true;
      this.userRef.child("status").set("logged_in").then(function(data){
        // console.log(data);
      }).catch(function(error){
        // console.log(error)
      });
  
      console.log("Username: " + user_name);
      this.userRef.once('value',function(data){
        if(data.exists()){
            var userName = data.val().display_name;
            this.userName.textContent = userName;
        } else {
            this.userName.textContent = user_name;
        }
      }.bind(this));
      $('#chat-modal').modal('show');
      // Set the user's profile pic and name.
      // console.log("profile pic url: " + profilePicUrl);
      // this.userPic.style.backgroundImage = 'url(' + (profilePicUrl || '/assets/images/profile_pic.png') + ')';
      // this.userPic.style.backgroundImage = 'url(' + (profilePicUrl || '/franchisesoft/assets/images/profile-pic.png') + ')';
      this.userPic.style.backgroundImage = 'url(' + (profilePicUrl || window.location.origin+'/assets/images/profile-pic.png') + ')';
      
      // Show user's profile and sign-out button.
      this.userName.removeAttribute('hidden');
      this.userPic.removeAttribute('hidden');
      this.signOutButton.removeAttribute('hidden');
      this.contactList.removeAttribute('hidden');
      this.bodyContainer.classList.add('mdl-layout--fixed-drawer');
  
      // Hide sign-in button.
      this.signInButton.setAttribute('hidden', 'true');
  
      // Load contact List
      this.loadContacts();
      this.getMessageCount();
  
      // We save the Firebase Messaging Device token and enable notifications.
      this.saveMessagingDeviceToken();
    } else { // User is signed out!
      // Hide user's profile and sign-out button.
      this.userName.setAttribute('hidden', 'true');
      this.userPic.setAttribute('hidden', 'true');
      this.signOutButton.setAttribute('hidden', 'true');
      this.contactList.setAttribute('hidden', 'true');
      this.messageCard.setAttribute("hidden",'true');
      this.bodyContainer.classList.remove('mdl-layout--fixed-drawer');
      // Show sign-in button.
      this.signInButton.removeAttribute('hidden');
      if(session_token!='')
      {
        // console.log("No user found signing in");
        this.signIn();        
      }
    }
  };

// Saves a new message on the Firebase DB.
FriendlyChat.prototype.saveMessage = function(e) {
  e.preventDefault();
  // Check that the user entered a message and is signed in.
  console.log("Saving message");
  if (this.messageInput.value && this.checkSignedInWithMessage() && this.selectedContact!=null) {
    var currentUser = this.auth.currentUser;
    var timestamp = + new Date();
    // Add a new message entry to the Firebase Database.
    this.setCount(this.selectedContact);
    this.messagesRef.child(this.roomType).child(timestamp).set({
      sender: this.userName.textContent,
      receiver: this.contactName,
      senderUid: currentUser.uid,
      receiverUid: this.selectedContact,
      message: this.messageInput.value,
      timestamp: timestamp
    }).then(function() {
      // Clear message text field and SEND button state.
      this.sendNotificaiton(this.userName.textContent,this.messageInput.value,currentUser.uid,this.selectedContact);
      this.resetMaterialTextfield(this.messageInput);
      this.toggleButton();
    }.bind(this)).catch(function(error) {
      console.error('Error writing new message to Firebase Database', error);
    });

  }
};

FriendlyChat.prototype.sendNotificaiton = function(sender, message, senderUid,receiverUid){
    // console.log("Sending Notification from: " + senderUid +" to: " + receiverUid);
    var getToken = function(data){

      var receiverToken = data.val();
      var body = JSON.stringify({
                  "to": receiverToken,
                  "notification": {
                    "uid":senderUid,
                    "username": sender,
                    "title": sender,
                    "text": message,
                    "sound":"default",
                    "badge":1,
                    "click_action": "OPEN_CHAT_ACTIVITY"
                  },
                  "data": {
                    "title":sender,
                    "text":message,
                    "username":sender,
                    "uid":senderUid,
                    "fcm_token": receiverToken
                  }
              });
      // console.log("body: " + body);
      var xhr = new XMLHttpRequest();
      var url = "https://fcm.googleapis.com/fcm/send";
      xhr.open("POST", url, true);
      xhr.setRequestHeader("Content-type", "application/json");
      xhr.setRequestHeader("Authorization", "key=AAAA42fFPXo:APA91bHrZhYQfrmRSBijJWq3rJV4egFmnbeZArhnqIUcsa3KsIziGBOGpGs1WiaOxEiH-c21QiXLE2wsxoakQNVmmS8SOkhiAssNVbl68rplGuiC8X1od7dRgBeI8kGP9Rf1Pjqc8vjV");
      xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status == 200) {
              // console.log("Notiication success");
          }
      }
      xhr.send(body);

    }
    this.database.ref("/users").child(receiverUid).child("auth_token").once("value",getToken);
}

// Sets the URL of the given img element with the URL of the image stored in Cloud Storage.
FriendlyChat.prototype.setImageUrl = function(imageUri, imgElement) {
  // If the image is a Cloud Storage URI we fetch the URL.
  if (imageUri.startsWith('gs://')) {
    imgElement.src = FriendlyChat.LOADING_IMAGE_URL; //  a loading image first.
    this.storage.refFromURL(imageUri).getMetadata().then(function(metadata) {
      imgElement.src = metadata.downloadURLs[0];
    });
  } else {
    imgElement.src = imageUri;
  }
};

// Saves a new message containing an image URI in Firebase.
// This first saves the image in Firebase storage.
FriendlyChat.prototype.saveImageMessage = function(event) {
  event.preventDefault();
  var file = event.target.files[0];

  // Clear the selection in the file picker input.
  this.imageForm.reset();

  // Check if the file is an image.
  if (!file.type.match('image.*')) {
    var data = {
      message: 'You can only share images',
      timeout: 2000
    };
    this.signInSnackbar.MaterialSnackbar.showSnackbar(data);
    return;
  }

  // Check if the user is signed-in
  if (this.checkSignedInWithMessage()) {

    // We add a message with a loading icon that will get updated with the shared image.
    var currentUser = this.auth.currentUser;
    this.messagesRef.push({
      name: currentUser.displayName,
      imageUrl: FriendlyChat.LOADING_IMAGE_URL,
      photoUrl: currentUser.photoURL || '/images/profile_placeholder.png'
    }).then(function(data) {

      // Upload the image to Cloud Storage.
      var filePath = currentUser.uid + '/' + data.key + '/' + file.name;
      return this.storage.ref(filePath).put(file).then(function(snapshot) {

        // Get the file's Storage URI and update the chat message placeholder.
        var fullPath = snapshot.metadata.fullPath;
        return data.update({imageUrl: this.storage.ref(fullPath).toString()});
      }.bind(this));
    }.bind(this)).catch(function(error) {
      console.error('There was an error uploading a file to Cloud Storage:', error);
    });
  }
};


// Signs-in Friendly Chat.
FriendlyChat.prototype.signIn = function(){
  console.log("Signin");
  // Sign in Firebase using popup auth and Google as the identity provider.
  var provider = new firebase.auth.GoogleAuthProvider();
  var token = session_token;
  if(this.selectedContact!=null && this.selectedContact!=undefined){
    document.getElementById(this.selectedContact).classList.remove("contact-selected");
  }
  this.selectedContact = null;
  //alert(token);
   //var token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJmaXJlYmFzZS1hZG1pbnNkay15bWdneUBmcy1jaGF0LXNlcnZlci5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsInN1YiI6ImZpcmViYXNlLWFkbWluc2RrLXltZ2d5QGZzLWNoYXQtc2VydmVyLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwiYXVkIjoiaHR0cHM6XC9cL2lkZW50aXR5dG9vbGtpdC5nb29nbGVhcGlzLmNvbVwvZ29vZ2xlLmlkZW50aXR5LmlkZW50aXR5dG9vbGtpdC52MS5JZGVudGl0eVRvb2xraXQiLCJpYXQiOjE0OTE5OTY1NzcsImV4cCI6MTQ5MjAwMDE3NywidWlkIjoiMjciLCJjbGFpbXMiOnsiMjciOiIyNyJ9fQ.Rw0ahKogQ8Wueq_QKnSOtEAc1EiMqd9sODFtW8CIMbv5vPvgxhvcm-zWnUj8XvxV3_IrD3bW7IW8W3NJW6zWS52HeA4IJ2SD5aGgopPT_LIrIs6m0hemtVUg1wxLF15Bnc7XnFlLPXP2RP4Yw4sfLdPESrZFEzshdnNU-CF3KWCfgPPehrCaGUVtzepBSHPEw9szI-042eknhJohlfkB3E4JTOu4rrmumNkVO3wjx82Ufc_Q5bZZxRCWfvs-gPreQ0zVNl2T8FU1HasOihSaz6gujFNqNwplHlHpOarr93kTX9ZOYy2N1UjDmiDnUufHzxfih76TdjCcmgI2H0Qt3w";
   if(token!=null && token!=undefined){
    this.auth.signInWithCustomToken(token).then(function(){
      // console.log("Successful signed in:" + token);
      $('#chat-modal').modal('show');
    }).catch(function(error){
      alert("Unable to open chat, try refreshing");
      $('#chat-modal').modal('hide');
      $("#customLoader").hide();

      // Handle Errors here.
      
      if(error.code === "auth/invalid-custom-token"){
        // console.log("Token expired: " + token);    
      } else {
        var errorCode = error.code;
        var errorMessage = error.message;
        // console.log("Error: " + errorCode + " " + errorMessage);
      }
    });
  }
};

// Signs-out of Friendly Chat.
FriendlyChat.prototype.signOut = function(){
  // Sign out of Firebase.
  console.log("Signout");
  if(this.auth.currentUser){
    this.userRef = this.database.ref('users').child(this.auth.currentUser.uid).child("status").set("logged_out").then(function(data){
      if(this.messagesRef){
        this.messagesRef.off();
      }
      this.auth.signOut();
      $('#chat-modal').modal('hide');
    }.bind(this)).catch(function(error){
      console.error(error)
    });
  }
};

// Returns true if user is signed-in. Otherwise false and displays a message.
FriendlyChat.prototype.checkSignedInWithMessage = function() {
  // Return true if the user is signed in Firebase
  if (this.auth.currentUser) {
    return true;
  }

  // Display a message to the user using a Toast.
  var data = {
    message: 'You must sign-in first',
    timeout: 2000
  };
  this.signInSnackbar.MaterialSnackbar.showSnackbar(data);
  return false;
};

// Saves the messaging device token to the datastore.
FriendlyChat.prototype.saveMessagingDeviceToken = function() {
  firebase.messaging().getToken().then(function(currentToken) {
    if (currentToken) {
      this.currentToken = currentToken;
      // console.log('Got FCM device token:', currentToken);
      this.messaging = firebase.messaging();
      this.messaging.onMessage(function(payload) {
        // console.log("Message received. ", payload);
        // ...
      });
      // Saving the Device Token to the datastore.
      if(this.auth.currentUser!=null){
        firebase.database().ref('/users').child(this.auth.currentUser.uid).child("auth_token")
            .set(currentToken).then(function(){
              // console.log("Token updated in firebase");
            }.bind(this)).catch(function(error){
              console.error('FCM setting failed: ',error);
            });
      } else {
        console.error("Current user not set yet");
        location.reload();
      }
    } else {
      // Need to request permissions to show notifications.
      this.requestNotificationsPermissions();
    }
  }.bind(this)).catch(function(error){
    // console.error('Unable to get messaging token.', error);
  });
};

// Requests permissions to show notifications.
FriendlyChat.prototype.requestNotificationsPermissions = function() {
  // console.log('Requesting notifications permission...');
  firebase.messaging().requestPermission().then(function() {
    // Notification permission granted.
    this.saveMessagingDeviceToken();
  }.bind(this)).catch(function(error) {
    console.error('Unable to get permission to notify.', error);
  });
};

// Resets the given MaterialTextField.
FriendlyChat.prototype.resetMaterialTextfield = function(element) {
  element.value = '';
  element.parentNode.MaterialTextfield.boundUpdateClassesHandler();
};



FriendlyChat.prototype.loadContacts = function(){
    if(this.auth.currentUser){
      // console.log('Loading contacts...' + window.location.origin +"/franchisesoft/user/getChatContacts");
      $.ajax({
        url: window.location.origin +"/user/getChatContacts",
        // url: window.location.origin +"/franchisesoft/user/getChatContacts",
        // url: window.location.origin +"/fs-uat/fs_uat_dev/user/getChatContacts",
        data: "", //ur data to be sent to server
        contentType: "application/json; charset=utf-8", 
        type: "GET",
        success: function (response) {
          console.log(response);
          var contacts = JSON.parse(response);
          if(contacts!=undefined && contacts!=null){
             for (var i = 0; i < contacts.length; i++) {
              var contact = contacts[i];
              // console.log(contact.display_name);
              if(contact.user_id!=this.auth.currentUser.uid){
                this.displayContacts(contact.user_id,contact.display_name,contact.profile_picture,i);
              }
            }          
          } else {
            console.log("No contacts found to chat");
            alert("No contacts found to chat with, closing now!");
            $("#customLoader").hide();
            $('#chat-modal').modal('hide');
          }
        }.bind(this),
        error: function (x, y, z) {
          $("#customLoader").hide();
           console.error(x.responseText +"  " +x.status);
        }
    }).done(function(){
      $("#customLoader").hide();
      this.contactList.scrollTop = 0;
    }.bind(this));   
    }
}

// Template for messages.
FriendlyChat.MESSAGE_TEMPLATE =
    '<div class="message-container">' +
      '</span><div class="message"></div>' +
      '<div class="name"></div>'
    '</div>';

FriendlyChat.MESSAGE_TEMPLATE_SELF =
    '<div class="message-container sender-card">' +
      '<div class="message"></div>' +
      '<div class="name"></div>'+
    '</div>';

// Template for contacts.
FriendlyChat.CONTACT_TEMPLATE =
        '<div class="mdl-list__item contact-item">' +
          '<span class="mdl-list__item-primary-content">' +
            '<div class="pic"></div>' +
            '<div class="name"></div>' +
            '<span class="count label label-danger message-rounded-count"></span>'
          '</span>' +
        '</div>';
// A loading image URL.
FriendlyChat.LOADING_IMAGE_URL = 'https://www.google.com/images/spin-32.gif';

FriendlyChat.prevSelected = null;

FriendlyChat.prototype.selectContact = function(key,name){
  this.contactName = name;
  // console.log("Selected contact: " + key);
  this.selectedContact = key;
  var contact = document.getElementById(key);
  if(contact===this.prevSelected){

  } else if(this.prevSelected==null) {
    contact.className += " contact-selected";
    this.prevSelected = contact;
  } else {
    this.prevSelected.classList.remove("contact-selected");
    this.prevSelected = contact;
    contact.className += " contact-selected";
  }
  this.messageCard.removeAttribute("hidden");
  this.loadMessages(key);
}

// Loads chat messages history and listens for upcoming ones.
FriendlyChat.prototype.loadMessages = function(contactUid) {
  // Reference to the /messages/ database path.
  // console.log("loading messages for " + contactUid);
  this.messagesRef = this.database.ref('chat_rooms');
  // Make sure we remove all previous listeners.
  if(this.roomType!=undefined){
    this.messagesRef.child(this.roomType).off();
  }
  
  this.roomType = contactUid+"_"+this.auth.currentUser.uid;
  //remove all previous messages from message container
  while ( this.messageList.firstChild ) this.messageList.removeChild( this.messageList.firstChild );

  // Loads the last 12 messages and listen for new ones.
  var setChatRoom = function(data) {
    var val = data.val();
    if(data.hasChild(contactUid+"_"+this.auth.currentUser.uid)){
      this.roomType = contactUid+"_"+this.auth.currentUser.uid;
    } else if(data.hasChild(this.auth.currentUser.uid+"_"+contactUid)){
      this.roomType = this.auth.currentUser.uid+"_"+contactUid;
    }
    var setMessage = function(data2) {
      var val2 = data2.val();
      // console.log("Sender: " + val2.sender + " recipient: " + val2.receiver);
      this.displayMessage(data2.key, val2.sender, val2.message, val2.photoUrl,val2.senderUid,contactUid);
    }.bind(this);
    this.messagesRef.child(this.roomType).limitToLast(20).on('child_added', setMessage);
    this.messagesRef.child(this.roomType).limitToLast(20).on('child_changed', setMessage);
  }.bind(this);
  this.messagesRef.once('value', setChatRoom);
  this.setRecieverCount(contactUid);
  this.clearCurrentCountForContact(contactUid);
};

// Displays a Contacts in the UI.
FriendlyChat.prototype.displayContacts = function(key, name, picUrl,index) {
  // console.log(key);
  var div = document.getElementById(key);
  // If an element for that message does not exists yet we create it.
  if (!div) {
    var container = document.createElement('div');
    container.innerHTML = FriendlyChat.CONTACT_TEMPLATE;
    div = container.firstChild;
    div.onclick = function(){
      this.selectContact(key,name);
    }.bind(this);
    div.setAttribute('id', key);
    if(index==0){
      this.topContact = div;
    }
    this.contactList.appendChild(div);
  }
  if (picUrl) {
    div.querySelector('.pic').style.backgroundImage = 'url(' + picUrl + ')';
  } else {
    div.querySelector('.pic').innerHTML ='<i class="material-icons mdl-list__item-icon contact-icon">person</i>';
  }
  if(key===this.auth.currentUser.uid){
      div.querySelector('.name').textContent = "Self";
  } else{
      div.querySelector('.name').textContent = name;
  }
  this.checkForContactCount(key);
  // Show the card fading-in and scroll to view the new message.
  setTimeout(function() {div.classList.add('visible')}, 1);
};

FriendlyChat.prototype.checkForContactCount = function(key){
    // console.log("contact counting for key " + key);
    this.database.ref('users/'+this.auth.currentUser.uid+'/unread/'+key).on('value',function(data){
      var div = document.getElementById(key); 
      if(data.exists && data.val()>0){
        this.moveDivToTop(div);     
      }
      if(this.selectedContact!=key){
        div.querySelector('.message-rounded-count').textContent = data.val();
      }
        // console.log("contact counting " + data.val());
    }.bind(this));
}

FriendlyChat.prototype.moveDivToTop = function(div){
    if(this.topContact==null){
      this.contactList.childNodes.forEach(function(node){
        console.log(node.id);
        if(node.id!=undefined){
          this.topContact = node;
          return;
        }
      })
    }
    if(div.id==this.topContact.id){
      return;
    }
    this.contactList.removeChild(div);
    this.contactList.insertBefore(div,this.topContact);
    this.topContact = div;
    this.contactList.scrollTop = 0;
}

// Displays a Message in the UI.
FriendlyChat.prototype.displayMessage = function(key, name, text, picUrl,sender_key,contactUid) {
  // console.log("Message found for "+ key + " Message: " + text);
  var div = document.getElementById(key);
  // If an element for that message does not exists yet we create it.
  if (!div) {
    var container = document.createElement('div');
    if(sender_key===firebase.auth().currentUser.uid){
      container.innerHTML = FriendlyChat.MESSAGE_TEMPLATE_SELF
    } else {
      container.innerHTML = FriendlyChat.MESSAGE_TEMPLATE;
    }
    div = container.firstChild;
    div.setAttribute('id', key);
    this.messageList.appendChild(div);
  }
  if(sender_key!=firebase.auth().currentUser.uid){
    if (picUrl) {
      // div.querySelector('.pic').style.backgroundImage = 'url(' + picUrl + ')';
    }
    div.querySelector('.name').textContent = name;
  }
  var messageElement = div.querySelector('.message');
  if (text) { // If the message is text.
    messageElement.textContent = text;
    // Replace all line breaks by <br>.
    messageElement.innerHTML = messageElement.innerHTML.replace(/\n/g, '<br>');      
  }
  // else if (imageUri) { // If the message is an image.
  //   var image = document.createElement('img');
  //   image.addEventListener('load', function() {
  //     this.messageList.scrollTop = this.messageList.scrollHeight;
  //   }.bind(this));
  //   this.setImageUrl(imageUri, image);
  //   messageElement.innerHTML = '';
  //   messageElement.appendChild(image);
  // }
  // Show the card fading-in and scroll to view the new message.
  setTimeout(function() {div.classList.add('visible')}, 1);
  this.messageList.scrollTop = this.messageList.scrollHeight;
  this.clearCurrentCountForContact(contactUid);
  this.messageInput.focus();
};

// Enables or disables the submit button depending on the values of the input
// fields.
FriendlyChat.prototype.toggleButton = function() {
  if (this.messageInput.value) {
    this.submitButton.removeAttribute('disabled');
  } else {
    this.submitButton.setAttribute('disabled', 'true');
  }
};

// Checks that the Firebase SDK has been correctly setup and configured.
FriendlyChat.prototype.checkSetup = function() {
  if (!window.firebase || !(firebase.app instanceof Function) || !window.config) {
    // window.alert('You have not configured and imported the Firebase SDK. ' +
    //     'Make sure you go through the codelab setup instructions.');
  } else if (config.storageBucket === '') {
    // window.alert('Your Cloud Storage bucket has not been enabled. Sorry about that. This is ' +
    //     'actually a Firebase bug that occurs rarely. ' +
    //     'Please go and re-generate the Firebase initialisation snippet (step 4 of the codelab) ' +
    //     'and make sure the storageBucket attribute is not empty. ' +
    //     'You may also need to visit the Storage tab and paste the name of your bucket which is ' +
    //     'displayed there.');
  }
};

FriendlyChat.prototype.getMessageCount = function(){
  // console.log("Getting message count");
  if(this.auth.currentUser){
        // console.log('Loading counts...');
        this.countRef = this.database.ref('users/'+this.auth.currentUser.uid+'/unread');
        this.countRef.on('value',function(data){ //.orderByChild('active').equalTo(1)
          if(data.exists){
            // console.log(data.numChildren());            
              this.messageCount.textContent=data.numChildren();          
          }
        }.bind(this));
    }
};

FriendlyChat.prototype.setRecieverCount = function(receiver_id){
  // console.log("TAGG "+ " Setting senders count for: " + receiver_id + " selected contact: " + friendlyChat.selectedContact);
    this.currentCount = 0;
    this.recieverRef = this.database.ref('users/'+ receiver_id +'/unread');
    this.recieverRef.once('value').then(function(data){
        if(data.exists()){
          data.forEach(function(data1){
            // console.log("TAGG "+"data1 key: " + data1.key + " value: " + data1.val());
              if(data1.key == this.auth.currentUser.uid){
                // console.log("TAGG "+"User " + data1.key + " count: " + data1.val());
                this.currentCount = data1.val();
              }
          }.bind(this));
        }
    }.bind(this));
}

FriendlyChat.prototype.setCount = function(receiver_id){
    // console.log("TAGG " + "setting actual count");
    var currentCounter = 0;
    this.database.ref('users/'+receiver_id+'/unread/'+this.auth.currentUser.uid).once('value').then(function(data){
      if(data.exists()){
        currentCounter = data.val();
      }
      this.database.ref('users/'+ receiver_id +'/unread').child(this.auth.currentUser.uid).set(currentCounter+1).then(function(){
        // console.log("TAGG" + "updating done");

      }).catch(function(e){
        console.error("TAGG "+"error occured: " + e);
      });
    }.bind(this));
    this.setRecieverCount(receiver_id);
}

FriendlyChat.prototype.clearCurrentCountForContact = function(receiver_id){
    console.log("TAGG " + "ressetting count for selected contact " +new Date().now );
    this.database.ref('users/'+ this.auth.currentUser.uid +'/unread').child(receiver_id).remove().then(function(){      
      var div = document.getElementById(receiver_id);  
      div.querySelector('.message-rounded-count').textContent = "";
    }).catch(function(e){
       console.log("TAGG " + "ressetting error " + e); 
    });
}