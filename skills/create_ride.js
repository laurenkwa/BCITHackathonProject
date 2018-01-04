/*

WHAT IS THIS?

This module demonstrates simple uses of Botkit's conversation system.

In this example, Botkit hears a keyword, then asks a question. Different paths
through the conversation are chosen based on the user's response.

*/

module.exports = function(controller) {
  controller.on('slash_command', function(bot, message) {
        var dialog = bot.createDialog(
              'Route Selector',
              'callback_id',
              'Submit'
            ).addText('Start Location','StartLocation','Your Address')
              .addText('Destination','Destination','Destination Address')
              .addText('Seats','Seats','Number of Seats Available')
              .addText('Date', 'Date', 'Date')
              .addText('Time of Departure','TimeofDeparture','0:00 - 24:00');

        bot.replyWithDialog(message, dialog.asObject(), function(err, res) {})
      });
  
    controller.on('dialog_submission', function(bot, message) {
        var submission = message.submission;
        //bot.reply(message, 'Got it!');

        // call dialogOk or else Slack will think this is an error
        bot.dialogOk();
        offerRide(bot, message, submission);
      });
  
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
                    var jsonMap = "https://maps.googleapis.com/maps/api/directions/json?origin=" + origin.replace(/ /g, "%20") + "&destination=" + destination.replace(/ /g, "%20") + "&mode=driving&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY";
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
                    var name = route.summary;
                    var thumbnail = "https://maps.googleapis.com/maps/api/staticmap?size=600x400&path=enc:" + encodeURI(points) + "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY";
                    convo.say(thumbnail);
                    convo.ask('Does this look correct?', function (response, convo) {
                      var correct = response.text;
                        if (correct.toUpperCase() == 'YES') {
                          convo.say('Great. I\'ll tell the rideshare channel.');
                          //convo.next();
                          //controller.storage.channels.delete(message.user, function(err){});
                          controller.storage.channels.save({id: message.user, name:message.text}, function(err, user) {
                          //controller.storage.channels.save({id: message.user, name: name, image: thumbnail}, function(err, user) {});
                          //controller.storage.channels.get(message.user, function(err, user) {bot.reply(message, 'New route created: ' + user.name);});  
                            controller.storage.channels.get(message.user, function(err, user) {bot.reply(message, 'ok' + user.name);});  
                          });
                            convo.next();
                          alertChannel(bot, submission);
                        } else {
                          offerRide(bot, name);
                        }
                    });
                  });

              //});
          //});
      }
        
      function alertChannel(bot, name) {
        bot.say({
        text: 'New ride offered: ' + name,
        channel: "C8NT4J1C7"
        });
      }

   // });
/*

    controller.hears(['channel'], 'direct_message,direct_mention', function(bot, message) {

        bot.createConversation(message, function(err, convo) {
        convo.say(message.channel);

            // create a path for when a user says YES
            convo.addMessage({
                    text: 'How wonderful.',
                    attachments: 
            },'yes_thread');

            // create a path for when a user says NO
            // mark the conversation as unsuccessful at the end
            convo.addMessage({
                text: 'Cheese! It is not for everyone.',
                action: 'stop', // this marks the converation as unsuccessful
            },'no_thread');

            // create a path where neither option was matched
            // this message has an action field, which directs botkit to go back to the `default` thread after sending this message.
            convo.addMessage({
                text: 'Sorry I did not understand. Say `yes` or `no`',
                action: 'default',
            },'bad_response');

            // Create a yes/no question in the default thread...
            convo.ask('Do you like cheese?', [
                {
                    pattern:  bot.utterances.yes,
                    callback: function(response, convo) {
                        convo.gotoThread('yes_thread');
                    },
                },
                {
                    pattern:  bot.utterances.no,
                    callback: function(response, convo) {
                        convo.gotoThread('no_thread');
                    },
                },
                {
                    default: true,
                    callback: function(response, convo) {
                        convo.gotoThread('bad_response');
                    },
                }
            ]);

            convo.activate();

            // capture the results of the conversation and see what happened...
            convo.on('end', function(convo) {

                if (convo.successful()) {
                    // this still works to send individual replies...
                    bot.reply(message, 'Let us eat some!');

                    // and now deliver cheese via tcp/ip...
                }

            });
        });

    });
*/
};
