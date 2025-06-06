/**
 * Copyright 2015 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
'use strict';
// Initializes FriendlyChat.
function FriendlyChat() {
  // alert('<?php echo $session_token;?>');
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

  // Saves message on form submit.
  this.messageForm.addEventListener('submit', this.saveMessage.bind(this));
  this.signOutButton.addEventListener('click', this.signOut.bind(this));
  this.signInButton.addEventListener('click', this.signIn.bind(this));

  // Toggle for the button.
  var buttonTogglingHandler = this.toggleButton.bind(this);
  this.messageInput.addEventListener('keyup', buttonTogglingHandler);
  this.messageInput.addEventListener('change', buttonTogglingHandler);

  this.selectedContact = null;

  // Events for image upload.
  this.submitImageButton.addEventListener('click', function(e) {
    e.preventDefault();
    this.mediaCapture.click();
  }.bind(this));
  this.mediaCapture.addEventListener('change', this.saveImageMessage.bind(this));

  this.initFirebase();
}

// Sets up shortcuts to Firebase features and initiate firebase auth.
FriendlyChat.prototype.initFirebase = function() {
  // Shortcuts to Firebase SDK features.
  this.auth = firebase.auth();
  this.database = firebase.database();
  this.storage = firebase.storage();
  // Initiates Firebase auth and listen to auth state changes.
  this.auth.onAuthStateChanged(this.onAuthStateChanged.bind(this));
};

// Loads chat messages history and listens for upcoming ones.
FriendlyChat.prototype.loadMessages = function(contactUid) {
  // Reference to the /messages/ database path.
  console.log("loading messages for " + contactUid);
  this.messagesRef = this.database.ref('messages');

  // Make sure we remove all previous listeners.
  this.messagesRef.off();

  //remove all previous messages from message container
  while ( this.messageList.firstChild ) this.messageList.removeChild( this.messageList.firstChild );

  // Loads the last 12 messages and listen for new ones.
  var setMessage = function(data) {
    var val = data.val();
    console.log("Sender: " + val.sender + " recipient: " + val.recipient);
    if(val.sender===contactUid&&val.recipient===this.auth.currentUser.uid||val.sender===this.auth.currentUser.uid&&val.recipient===contactUid){
        this.displayMessage(data.key, val.sender_name, val.text, val.photoUrl,val.sender);
    }
  }.bind(this);
  this.messagesRef.limitToLast(20).on('child_added', setMessage);
  this.messagesRef.limitToLast(20).on('child_changed', setMessage);
  this.messagesRef.once('value',function(data){
    console.log(data);
  }).catch(function(error){
    console.log(error);
  });
};

// Saves a new message on the Firebase DB.
FriendlyChat.prototype.saveMessage = function(e) {
  e.preventDefault();
  // Check that the user entered a message and is signed in.
  if (this.messageInput.value && this.checkSignedInWithMessage() && this.selectedContact!=null) {
    var currentUser = this.auth.currentUser;

    // Add a new message entry to the Firebase Database.
    this.messagesRef.push({
      sender_name: currentUser.displayName || friendlyChat.userName.textContent,
      recipient_name: this.contactName,
      sender: currentUser.uid,
      recipient: this.selectedContact,
      text: this.messageInput.value,
      photoUrl: currentUser.photoURL || '/images/profile_placeholder.png'
    }).then(function() {
      // Clear message text field and SEND button state.
      FriendlyChat.resetMaterialTextfield(this.messageInput);
      this.toggleButton();
    }.bind(this)).catch(function(error) {
      console.error('Error writing new message to Firebase Database', error);
    });
  }
};

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
  // Sign in Firebase using popup auth and Google as the identity provider.
  var provider = new firebase.auth.GoogleAuthProvider();
  var token = session_token;
  //alert(token);
   //var token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJmaXJlYmFzZS1hZG1pbnNkay15bWdneUBmcy1jaGF0LXNlcnZlci5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsInN1YiI6ImZpcmViYXNlLWFkbWluc2RrLXltZ2d5QGZzLWNoYXQtc2VydmVyLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwiYXVkIjoiaHR0cHM6XC9cL2lkZW50aXR5dG9vbGtpdC5nb29nbGVhcGlzLmNvbVwvZ29vZ2xlLmlkZW50aXR5LmlkZW50aXR5dG9vbGtpdC52MS5JZGVudGl0eVRvb2xraXQiLCJpYXQiOjE0OTE5OTY1NzcsImV4cCI6MTQ5MjAwMDE3NywidWlkIjoiMjciLCJjbGFpbXMiOnsiMjciOiIyNyJ9fQ.Rw0ahKogQ8Wueq_QKnSOtEAc1EiMqd9sODFtW8CIMbv5vPvgxhvcm-zWnUj8XvxV3_IrD3bW7IW8W3NJW6zWS52HeA4IJ2SD5aGgopPT_LIrIs6m0hemtVUg1wxLF15Bnc7XnFlLPXP2RP4Yw4sfLdPESrZFEzshdnNU-CF3KWCfgPPehrCaGUVtzepBSHPEw9szI-042eknhJohlfkB3E4JTOu4rrmumNkVO3wjx82Ufc_Q5bZZxRCWfvs-gPreQ0zVNl2T8FU1HasOihSaz6gujFNqNwplHlHpOarr93kTX9ZOYy2N1UjDmiDnUufHzxfih76TdjCcmgI2H0Qt3w";
  friendlyChat.auth.signInWithCustomToken(token).catch(function(error){
    // Handle Errors here.
    var errorCode = error.code;
    var errorMessage = error.message;
    console.log("Error: " + errorCode + " " + errorMessage);
  });;

  //this.auth.signInWithPopup(provider);
  //post("http://192.168.0.165/franchisesoft/services?param=getToken&user_name=jenny&password=123456","","get");
  // post("http://192.168.0.165/franchisesoft/services?param=getToken&user_name=jenny&password=123456","","get");
  
};


function post(path, params, method)
{
    console.log("Getting custom token");
    method = method || "post"; // Set method to post by default if not specified.
    var http = new XMLHttpRequest();
    http.open(method, path, true);
    // http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // http.setRequestHeader("Access-Control-Allow-Origin","*");
    http.onreadystatechange = function(){
      if (http.readyState == XMLHttpRequest.DONE) {
          var token = http.responseText;
          // var token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJmaXJlYmFzZS1hZG1pbnNkay15bWdneUBmcy1jaGF0LXNlcnZlci5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsInN1YiI6ImZpcmViYXNlLWFkbWluc2RrLXltZ2d5QGZzLWNoYXQtc2VydmVyLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwiYXVkIjoiaHR0cHM6XC9cL2lkZW50aXR5dG9vbGtpdC5nb29nbGVhcGlzLmNvbVwvZ29vZ2xlLmlkZW50aXR5LmlkZW50aXR5dG9vbGtpdC52MS5JZGVudGl0eVRvb2xraXQiLCJpYXQiOjE0OTE1NTg2ODYsImV4cCI6MTQ5MTU2MjI4NiwidWlkIjoiMTciLCJjbGFpbXMiOnsiMTciOiIxNyJ9fQ.kEhNTgCoqR8zkQGFjgWIHioP3TXfVlBRM0dK0-DCVurr9ctcvV_ENIAPSpnV-RQe9nfOqBm2okBWiTNo_nZg-Hm-GFDca8f_rqFd0aRBvaJGSQs5Niyqh2Ux_v1lCpvzg4nzfFqQEsVCpVtL_jV-m2aaxJeKoL0g5DDsgmsEFcmwzLWaW-D9ODGenlgEsQWphZPNTP5zflxBhVfYjJoUWt3QMSWJRlwJ6MMbdwMwo6akZz4N1AIszIc6RFvmu7OUIKWbbkGBvpApZpQ4ACRuFkI0RpogDgZvRWTQXn0Tib35F0yHJMbFFPuSAIaU-xtz5-s5nzeCa5nnk7NgJLwHDg";
          console.log("Token: " +token);
          friendlyChat.auth.signInWithCustomToken(token).catch(function(error){
            // Handle Errors here.
            var errorCode = error.code;
            var errorMessage = error.message;
            console.log("Error: " + errorCode + " " + errorMessage);
          });;
      }
    }
    http.send();
}


// Signs-out of Friendly Chat.
FriendlyChat.prototype.signOut = function(){
  // Sign out of Firebase.
  this.userRef = this.database.ref('users').child(this.auth.currentUser.uid).child("status").set("logged_out").then(function(data){
    console.log(data);
    friendlyChat.auth.signOut();
  }).catch(function(error){
    console.log(error)
  });
};

// Triggers when the auth state change for instance when the user signs-in or signs-out.
FriendlyChat.prototype.onAuthStateChanged = function(user) {

  if (user) { // User is signed in!
    console.log("auth state changed: " + user.uid);
    // Get profile pic and user's name from the Firebase user object.
    var profilePicUrl = user.photoURL;


    //set user to realtime datebase
    this.userRef = this.database.ref('users').child(user.uid);
    this.userRef.child("status").set("logged_in").then(function(data){
      console.log(data);
    }).catch(function(error){
      console.log(error)
    });

    this.userRef.once('value',function(data){
      if(data.exists()){
          var userName = data.val().display_name;
          friendlyChat.userName.textContent = userName;
      }
    });

    // Set the user's profile pic and name.
    this.userPic.style.backgroundImage = 'url(' + (profilePicUrl || '/images/profile_placeholder.png') + ')';


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

    // We load currently existing chant messages.
    // this.loadMessages();

    // We save the Firebase Messaging Device token and enable notifications.
    this.saveMessagingDeviceToken();

    if(session_token=='')
    {
      this.signOut();
    }
    else
    {
      if(fb_logout=='')
      {
        fb_logout='N';
        this.signOut();
        // this.signIn();
      }
    }
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
      this.signIn();
    }
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
      console.log('Got FCM device token:', currentToken);
      // Saving the Device Token to the datastore.
      firebase.database().ref('/fcmTokens').child(currentToken)
          .set(firebase.auth().currentUser.uid).catch(function(error){
            console.error('FCM setting failed: ',error);
          });
    } else {
      // Need to request permissions to show notifications.
      this.requestNotificationsPermissions();
    }
  }.bind(this)).catch(function(error){
    console.error('Unable to get messaging token.', error);
  });
};

// Requests permissions to show notifications.
FriendlyChat.prototype.requestNotificationsPermissions = function() {
  console.log('Requesting notifications permission...');
  firebase.messaging().requestPermission().then(function() {
    // Notification permission granted.
    this.saveMessagingDeviceToken();
  }.bind(this)).catch(function(error) {
    console.error('Unable to get permission to notify.', error);
  });
};

// Resets the given MaterialTextField.
FriendlyChat.resetMaterialTextfield = function(element) {
  element.value = '';
  element.parentNode.MaterialTextfield.boundUpdateClassesHandler();
};

FriendlyChat.prototype.loadContacts = function(){

    if(this.auth.currentUser){
      console.log('Loading contacts...');
        this.contactsRef = this.database.ref('users');
        this.contactsRef.child(this.auth.currentUser.uid).once('value',function(snapshot){
          var val = snapshot.val();
          if(val.displayname!=null || val.displayname!=undefined && val.group_status!=0){
              console.log("User found : " + val.displayname);
              friendlyChat.displayContacts(snapshot.key, val.displayname, val.photoUrl);
          }
          var group_id = snapshot.val().group_id;
          console.log("Current user group: " + group_id);
          var contactToGroup = [];
          // contactToGroup = snapshot.val().contact_to_group;
          // console.log("Contact to groups: " + contactToGroup);
          friendlyChat.contactsRef.orderByChild("group_id").equalTo(group_id).on('child_added',function(data){
            if(data.exists){
              var val = data.val();
              if(val.displayname!=null || val.displayname!=undefined && val.group_status!=0){
                  friendlyChat.displayContacts(data.key, val.displayname, val.photoUrl);
              }
            }
          });
        });
        this.contactsRef.child(this.auth.currentUser.uid).child('contact_to_group').once('value',function(snapshot){
          snapshot.forEach(function(subSnap){
            friendlyChat.contactsRef.orderByChild("group_id").equalTo(subSnap.key).on('child_added',function(data){
              if(data.exists){
                if(subSnap.val().type===data.val().type){
                  var val = data.val();
                  if(val.displayname!=null || val.displayname!=undefined){
                      friendlyChat.displayContacts(data.key, val.displayname, val.photoUrl);
                  }
                } else {
                  console.log("Same id different type");
                }

            }
          });
        });
      });
        // friendlyChat.addDummyContacts();

        // Make sure we remove all previous listeners.
        // this.contactsRef.off();
        //
        // // Loads the last 12 messages and listen for new ones.
        // var setContacts = function(snapshot) {
        //   snapshot.forEach(function(childSnap){
        //
        //   })
        //   var val = data.val();
        //   if(val.display_name!=null || val.display_name!=undefined){
        //       this.displayContacts(data.key, val.display_name, val.photoUrl);
        //   }
        // }.bind(this);
        // this.contactsRef.on('child_added', setContacts);
        // this.contactsRef.on('child_changed', setContacts);
    }
}

// FriendlyChat.prototype.addDummyContacts = function(){
//       // this.contactsRef = this.database.ref('contacts').once('value',function(data){
//
//
//       // this.contactsRef.push().set({
//       //   name: "Tom Hanks",
//       //   photoUrl: "http://obrieniph.ucalgary.ca/files/iph/person_placeholder-image.png"
//       // });
//       // this.contactsRef.push().set({
//       //   name: "Tom Cruise",
//       //   photoUrl: "http://obrieniph.ucalgary.ca/files/iph/person_placeholder-image.png"
//       // });
//       // this.contactsRef.push().set({
//       //   name: "Tom Sawyer",
//       //   photoUrl: "http://obrieniph.ucalgary.ca/files/iph/person_placeholder-image.png"
//       // });
//       // this.contactsRef.push().set({
//       //   name: "Tom Jerry",
//       //   photoUrl: "http://obrieniph.ucalgary.ca/files/iph/person_placeholder-image.png"
//       // });
//       // this.contactsRef.push().set({
//       //   name: "Jeff Hardy",
//       //   photoUrl: "http://obrieniph.ucalgary.ca/files/iph/person_placeholder-image.png"
//       // });
//       // this.contactsRef.push().set({
//       //   name: "Jeff Simsons",
//       //   photoUrl: "http://obrieniph.ucalgary.ca/files/iph/person_placeholder-image.png"
//       // });
//
// }

// Template for messages.
FriendlyChat.MESSAGE_TEMPLATE =
    '<div class="message-container">' +
      '</span><div class="message"></div>' +
      '<div class="name"></div>'
    '</div>';

FriendlyChat.MESSAGE_TEMPLATE_SELF =
    '<div class="message-container sender-card">' +
      '<div class="message"></div>' +
    '</div>';

// Template for contacts.
FriendlyChat.CONTACT_TEMPLATE =
        '<div class="mdl-list__item contact-item">' +
          '<span class="mdl-list__item-primary-content">' +
            '<div class="pic"></div>' +
            '<div class="name"></div>' +
          '</span>' +
        '</div>';
// A loading image URL.
FriendlyChat.LOADING_IMAGE_URL = 'https://www.google.com/images/spin-32.gif';

FriendlyChat.prevSelected = null;

FriendlyChat.prototype.selectContact = function(key,name){
  this.contactName = name;
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

// Displays a Contacts in the UI.
FriendlyChat.prototype.displayContacts = function(key, name, picUrl) {
  console.log(key);
  var div = document.getElementById(key);
  // If an element for that message does not exists yet we create it.
  if (!div) {
    var container = document.createElement('div');
    container.innerHTML = FriendlyChat.CONTACT_TEMPLATE;
    div = container.firstChild;
    div.onclick = function(){
      friendlyChat.selectContact(key,name);
    }
    div.setAttribute('id', key);
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
  // Show the card fading-in and scroll to view the new message.
  setTimeout(function() {div.classList.add('visible')}, 1);
  this.contactList.scrollTop = this.contactList.scrollHeight;
};


// Displays a Message in the UI.
FriendlyChat.prototype.displayMessage = function(key, name, text, picUrl,sender_key) {
  console.log("Message found for "+ key + " Message: " + text);
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

window.onload = function() {
  window.friendlyChat = new FriendlyChat();
};
