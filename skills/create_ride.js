/*

WHAT IS THIS?

This module demonstrates simple uses of Botkit's conversation system.

In this example, Botkit hears a keyword, then asks a question. Different paths
through the conversation are chosen based on the user's response.

*/

module.exports = function(controller) {
  controller.on('slash_command', function(bot, message) {
        bot.replyAcknowledge();
        launchDialog(bot, message);
      });
  
    controller.on('dialog_submission', function(bot, message) {
          var submission = message.submission;
          //bot.reply(message, 'Got it!');
  
          // call dialogOk or else Slack will think this is an error
          bot.dialogOk();
          offerRide(bot, message, submission); 
      });
  
    function launchDialog(bot, message) {
        var dialog = bot.createDialog(
              'Route Selector',
              'callback_id',
              'Submit'
            ).addText('Start Location','StartLocation','Your Address')
              .addText('Destination','Destination','Destination Address')
              .addText('Seats','Seats','Number of Seats Available')
              .addText('Date', 'Date', 'Date')
              .addText('Time of Departure','Time','0:00 - 24:00');

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
                    var thumbnail = "https://maps.googleapis.com/maps/api/staticmap?size=600x400&path=enc:" + encodeURI(points) + "&key=" + + process.env.google_maps;
                    convo.say({
                      text: "<" + thumbnail + "|Map preview>",
                      unfurl_links: true
                    });
                    convo.ask('Does this look correct?', function (response, convo) {
                      var correct = response.text;
                        if (correct.toUpperCase() == 'YES') {
                          convo.say('Great. I\'ll tell the rideshare channel.');
                          //convo.next();
                          controller.storage.channels.delete(message.user, function(err){
                            controller.storage.channels.save({id: message.user, name: name, image: thumbnail, driver: driver, seats:submission.Seats, time:submission.Time, date:submission.Date}, function(err, user) {
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
          + '\nSeats: ' + user.seats + '\nLeaving: ' + user.date + " at " + user.time + 
          "\n<" + user.image + "|Map preview>",
        channel: "C8NT4J1C7"
          
        });
      }
  
      

  
      function startPrivateMessage(bot, driver, passenger) {
          bot.api.conversations.open({
            token: process.env.slackToken,
            users: "U5E31FZAB,U6YFZTGSG",
            text: "You can use this message to work out the details of your upcoming ride."
          });
      }

};
