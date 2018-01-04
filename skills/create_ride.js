/*

WHAT IS THIS?

This module demonstrates simple uses of Botkit's conversation system.

In this example, Botkit hears a keyword, then asks a question. Different paths
through the conversation are chosen based on the user's response.

*/

var dropDownList = {
    "text": "What route would you like to take?",
    "response_type": "in_channel",
    "attachments": [
        {
            "text": "Choose a route",
            "fallback": "Sorry, an error occured.",
            "color": "#3AA3E3",
            "attachment_type": "default",
            "callback_id": "route_selection",
            "actions": [
                {
                    "name": "route_list",
                    "text": "Pick a route...",
                    "type": "select",
                    "options": []
                }
            ]
        }
    ]
}

var selectionButton = {
    "text": "Would you like to create a new car pool route or find one?",
    "attachments": [
        {
            "text": "Choose?",
            "fallback": "Something went wrong",
            "callback_id": "create_find",
            "color": "#3AA3E3",
            "attachment_type": "default",
            "actions": [
                {
                    "name": "Create",
                    "text": "Create",
                    "type": "button",
                    "value": "Create"
                },
                {
                    "name": "Find",
                    "text": "Find",
                    "style": "Find",
                    "type": "button",
                    "value": "Find"
                }
            ]
        }
    ]
}

module.exports = function(controller) {
  controller.on('slash_command', function(bot, message) {
        bot.replyAcknowledge();
        bot.reply({text: '', channel: message.user}, selectionButton);
      });
    
    controller.on('interactive_message_callback', function(bot, message) {
          if(message.callback_id == 'create_find'){
            bot.reply(message, '');
            if(message.actions[0].name == "Create"){
              launchDialog(bot, message);
            }else{
              routeMenu(bot, message);
            }
          }
      });
  
    controller.on('dialog_submission', function(bot, message) {
      var submission = message.submission;
      //bot.reply(message, 'Got it!');
  
      if(message.callback_id == 'route_creator'){
        bot.dialogOk();
        offerRide(bot, message, submission); 
      }
      if(message.callback_id == 'route_selector'){
        bot.dialogOk();
        routeMenu(bot, message, submission.City, submission.EarlyTime, submission.LateTime); 
      }
    });
    
    /***** Opens a route dialog box to choose time and location *****/
    function routeDialog(bot, message){
      var dialog = bot.createDialog(
              'Route Selector',
              'route_selector',
              'Submit'
            ).addSelect('Select Your City','City',null,[
                {label:'Vancouver',value:'Vancouver'},
                {label:'Burnaby',value:'Burnaby'},
                {label:'Abbotsford',value:'Abbotsford'},
                {label:'Mission',value:'Mission'},
                {label:'New Westminister',value:'New Westminister'},
                {label:'North Vancouver',value:'North Vancouver'},
                {label:'Richmond',value:'Richmond'},
                {label:'Surrey',value:'Surrey'},
                {label:'Whistler',value:'Whistler'},
                {label:'Other',value:'Other'}
              ], {placeholder: 'Select One'})
              .addSelect('Select a Earliest Time','EarlyTime',null,[
                {label:'12:00 am',value:'00'},
                {label:'1:00 am',value:'1'},
                {label:'2:00 am',value:'2'},
                {label:'3:00 am',value:'3'},
                {label:'4:00 am',value:'4'},
                {label:'5:00 am',value:'5'},
                {label:'6:00 am',value:'6'},
                {label:'7:00 am',value:'7'},
                {label:'8:00 am',value:'8'},
                {label:'9:00 am',value:'9'},
                {label:'10:00 am',value:'10'},
                {label:'11:00 am',value:'11'},
                {label:'12:00 am',value:'12'},
                {label:'1:00 pm',value:'13'},
                {label:'2:00 pm',value:'14'},
                {label:'3:00 pm',value:'15'},
                {label:'4:00 pm',value:'16'},
                {label:'5:00 pm',value:'17'},
                {label:'6:00 pm',value:'18'},
                {label:'7:00 pm',value:'19'},
                {label:'8:00 pm',value:'20'},
                {label:'9:00 pm',value:'21'},
                {label:'10:00 pm',value:'22'}, 
                {label:'11:00 pm',value:'23'}
              ], {placeholder: 'Select One'})
            .addSelect('Select a Latest Time','LateTime',null,[
                {label:'12:00 am',value:'00'},
                {label:'1:00 am',value:'1'},
                {label:'2:00 am',value:'2'},
                {label:'3:00 am',value:'3'},
                {label:'4:00 am',value:'4'},
                {label:'5:00 am',value:'5'},
                {label:'6:00 am',value:'6'},
                {label:'7:00 am',value:'7'},
                {label:'8:00 am',value:'8'},
                {label:'9:00 am',value:'9'},
                {label:'10:00 am',value:'10'},
                {label:'11:00 am',value:'11'},
                {label:'12:00 am',value:'12'},
                {label:'1:00 pm',value:'13'},
                {label:'2:00 pm',value:'14'},
                {label:'3:00 pm',value:'15'},
                {label:'4:00 pm',value:'16'},
                {label:'5:00 pm',value:'17'},
                {label:'6:00 pm',value:'18'},
                {label:'7:00 pm',value:'19'},
                {label:'8:00 pm',value:'20'},
                {label:'9:00 pm',value:'21'},
                {label:'10:00 pm',value:'22'}, 
                {label:'11:00 pm',value:'23'}
              ], {placeholder: 'Select One'});

        bot.replyWithDialog(message, dialog.asObject(), function(err, res) {})
    }
  
    /***** Creates drop down menu of all available routes *****/
    function routeMenu(bot, message, city, earlyTime, lateTime){
     controller.storage.channels.all(function(err, user) {
       dropDownList.attachments[0].actions[0].options.length = 0;
       var text = '';
       for(var i = 0; i < user.length; i++){
         //bot.reply(message, '' + user[i].time);
         if(parseInt(user[i].seats) > 0 
            && parseInt(earlyTime) < parseInt(user[i].twentyFourTime) && parseInt(user[i].twentyFourTime < lateTime)){
           var string = user[i].name + '  ~  Seats: ' + user[i].seats;
           var object = { text: string, value: user[i].name };
           text += 'Route ' + user[i].name + ' by ' + user[i].driver + '\nWith ' + user[i].seats + ' seats on  ' + user[i].date + ' at ' + user[i].time + '\n\n';
           dropDownList.attachments[0].actions[0].options.push(object);
         }
       }
       dropDownList.attachments[0].text = text;
       bot.reply(message, dropDownList);
      });
    }
  
    function launchDialog(bot, message) {
        var dialog = bot.createDialog(
              'Route Selector',
              'route_creator',
              'Submit'
            ).addText('Start Location','StartLocation','Your Address')
              .addText('Destination','Destination','Destination Address')
              .addText('Seats','Seats','Number of Seats Available')
              .addText('Date', 'Date', 'Date')
              .addSelect('Select a Departure Time','Time',null,[
                {label:'12:00 am',value:'00'},
                {label:'1:00 am',value:'1'},
                {label:'2:00 am',value:'2'},
                {label:'3:00 am',value:'3'},
                {label:'4:00 am',value:'4'},
                {label:'5:00 am',value:'5'},
                {label:'6:00 am',value:'6'},
                {label:'7:00 am',value:'7'},
                {label:'8:00 am',value:'8'},
                {label:'9:00 am',value:'9'},
                {label:'10:00 am',value:'10'},
                {label:'11:00 am',value:'11'},
                {label:'12:00 am',value:'12'},
                {label:'1:00 pm',value:'13'},
                {label:'2:00 pm',value:'14'},
                {label:'3:00 pm',value:'15'},
                {label:'4:00 pm',value:'16'},
                {label:'5:00 pm',value:'17'},
                {label:'6:00 pm',value:'18'},
                {label:'7:00 pm',value:'19'},
                {label:'8:00 pm',value:'20'},
                {label:'9:00 pm',value:'21'},
                {label:'10:00 pm',value:'22'}, 
                {label:'11:00 pm',value:'23'}
              ], {placeholder: 'Select One'});

      bot.replyWithDialog(message, dialog.asObject(), function(err, res) {})
    }
  
      function offerRide(bot, message, submission) {
              bot.startConversation(message, function(err, convo) {
            
                  var origin = submission.StartLocation;
                  if (origin.toUpperCase() == 'BCIT') {
                    origin = 'BCIT Burnaby campus';
                  }

                    var destination = submission.Destination;
                    
                   if (destination.toUpperCase() == 'BCIT') {
                      destination = 'BCIT Burnaby campus';
                    }
                    
                    convo.say('Here\'s your map from ' + origin + ' to ' + destination);

                    //convo.say('Great, let\'s go to ' + destination);
                    destination = destination + "Vancouver BC";
                    origin = origin + " Vancouver BC";
                    var jsonMap = "https://maps.googleapis.com/maps/api/directions/json?origin=" + origin.replace(/ /g, "%20") + "&destination=" + destination.replace(/ /g, "%20") + "&mode=driving&key=" + process.env.google_maps;
                    //convo.say(jsonMap);
                    var mapObject;
                    var XMLHttpRequest = require("../xmlhttprequest.js").XMLHttpRequest;
                    var xmlhttp = new XMLHttpRequest();
                    //convo.say("Let's get started.");
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4) {
                            //convo.say("Got it.");
                            mapObject = JSON.parse(this.responseText);
                        } else {
                          convo.say("Uh oh. JSON error. Try again.");
                        }
                    };
                    xmlhttp.open("GET", jsonMap, false);
                    xmlhttp.send();
                    
                    var route = mapObject.routes[0];
                    var polyline = route.overview_polyline;
                    var points = polyline.points;
                    var driver = "";
                    bot.api.users.info({user: message.user}, (error, response) => {
                        let {name} = response.user;
                        driver = name;
                    })
                    var name = route.summary;
                    var thumbnail = "https://maps.googleapis.com/maps/api/staticmap?size=600x400&path=enc:" + encodeURI(points) + "&key=" + process.env.google_maps;
                    convo.say({
                        attachments: 
                          [{
                            "fallback": "\n<" + thumbnail + "|Map preview>",
                            "image_url": thumbnail
                          }]
                    });
                    convo.ask('Does this look correct?', function (response, convo) {
                      var correct = response.text;
                        if (correct.toUpperCase() == 'YES') {
                          convo.say('Great. I\'ll tell the rideshare channel.');
                          //convo.next();
                          controller.storage.channels.delete(message.user, function(err){
                            var time = (parseInt(submission.Time) <= 12) 
                              ? ((submission.Time == '00') ? '12:00' : submission.Time) + ':00 am' 
                              : toString(parseInt(submission.Time) - 12) + ':00 pm';
                            controller.storage.channels.save({id: message.user, name: name, image: thumbnail, driver: '<@' + message.user + '>', seats:submission.Seats, time: time, twentyFourTime:submission.Time, date:submission.Date}, function(err, user) {
                              controller.storage.channels.get(message.user, function(err, user) {bot.reply(message, 'New route created: ' + user.name); alertChannel(bot, user);});  
                            });
                          });
                        } else {
                          convo.say('Oh no. Confirm your locations and then call me again.');
                        }
                    });
                  });
      }
        
      function alertChannel(bot, user) {
        bot.say({
        text: 'New ride offered: ' + user.name + " by " + user.driver 
          + '\nSeats: ' + user.seats + '\nLeaving: ' + user.date + " at " + user.time,
        channel: "C8NT4J1C7",
        attachments: 
          [{
            "fallback": "\n<" + user.image + "|Map preview>",
            "image_url": user.image
          }]
        });
      }
  
    controller.hears(['private'], 'direct_message,direct_mention', function(bot, message) {
        bot.say(bot.api.conversations.open({
          token: process.env.legacyToken,
          users: 'U5E31FZAB',
        }).channel.id);
    });
    
  

}
