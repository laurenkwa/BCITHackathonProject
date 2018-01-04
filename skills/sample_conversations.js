/*

WHAT IS THIS?

This module demonstrates simple uses of Botkit's conversation system.

In this example, Botkit hears a keyword, then asks a question. Different paths
through the conversation are chosen based on the user's response.

*/

module.exports = function(controller) {
    controller.hears(['offer'], 'direct_message,direct_mention', function(bot, message) {
      offerRide();
      function offerRide() {
              bot.startConversation(message, function(err, convo) {

              convo.ask('Where will you start driving from?', function(response, convo) {
                  var origin = response.text;
                  convo.say('Cool, let\'s start at ' + origin);
                  convo.next();
                  convo.ask('Where will you drive to?', function(response, convo) {
                    var destination = response.text;
                    convo.next();
                    convo.say('Great, let\'s go to ' + destination);
                    convo.say('Here\'s your map.');
                    convo.say("https://maps.googleapis.com/maps/api/directions/json?origin=" + origin + "&destination=" + destination + "&mode=driving&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY");
                    convo.addMessage({
                        text: 'Cheese! It is not for everyone.',
                        attachments: [
                            {
                                "fallback": "Map.",
                                "image_url": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAMAAACahl6sAAABCFBMVEX///9ChfTpQzb6uwU2qFPR6dIAn0Ewpk8AmTWq1q0Ab/JFh/RBhPQAbfKYuvknefP5tQBdk/XqRzv4sAAAafI7gfT6tADoQTToOy4edvPgFAQPcvIxfPP5rgDiIRLlLyHnNindAADR3/yQs/f3+v5SjfT//fbe6PziJBb6n5f+5OLD48bwZFiHrfjq8P3w9f33i4Lzcma1zPn/8vD6q6RmmPX7zkr+4JK/1Pr+zcj+7sjuVEeqxPnwYFSVtvf8yT32gHb/7b3+1tP+9Nj8t7B4pPf7wiP+2n4AY/HK2fv8v7n+5aj902r2iYD7zVfw+PAAjB30eW4AWfCUzZv++en+56/5o5r81nX4DCRNAAAJRklEQVR4nO2afV/ayhLHRXp6e/JAICSSJxIIUqIHEFEsCFW0RosP7fHWe+77fyc3OxvIMyi2Vj93vn8prtn97c7MzkzY2EAQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBPm/Zq/b7e797kU8k26/4TZZTubYknsy3P7dy1mT7f7AYHhdMAzZMASd13Ou/eKL+P7jT48ff6z/hO0Jy+tyLozBM4OXlvLX3x88/v6x9gOGOUnIJeAY3n1Zd/nj/b/evXv34c81/33blVQ2WD63+JHlJONFD+V5Qlo5ab52WWUYXdcZRvDNjFX5yc9d61KeJcQWdHocnM5zg8ak3+9POiWDF3wlX1/wTJ4jxFZ9s9KZQT/wiFaB5Q02xxrSGzmRrgHnwcpSKb7zE4PxPn5JHc8R0mRAh8AUkn/bG0gvq+MZQjoS6FDVWeqf3ZNnruyJrC1kxshwHurLX+KprC2kBA4i68NfsKh1WFdIn4cIy7+wAWWzrpCSDnG3+SvWtBZrCrFVuNH1/i9Z1DqsKaQBlqUO1ply93w02j1YMuDh+Ozsy8fsv2+3bLsVr3nShXhznS+dq6QSIcyTr4qDi+lYsSyrJu7v7KYN+Hjz7bJIyJ9efUkbsDdxWUMQDNYlYaY1s2171k0Xct77TOe6v8haT8sgsVeWW0/UsbOpaZaiVBSlZmrWfVLKVb5c3irmPYrlavEoIWW7IPO6IXsYPF9qbbhfBUH42kgTct42NadG5rI0TcyQMtS5NSxrtFm3xM05oqKZO9EBZ/nqVj5Etfzf6IAWKxlB0cPodoPxNEHojAvpaVplMZno1O9Tl1QAF9EbT9Lxj+YEMuDxtXo7POBTtZyPUqyehn2lz+hB8eMFf8Nokg2FHCkmpF1XwpOJlfo4zVUaTLqLDDuFBCcFmhjv1Gv00TXHcWoVOkF9HPzzVbXoL39ra6s4P5TLQElfEqgOQ9VVA/JVmU0X8rku0lN3TNNR4MfwVAs6OhQhieA7+DeTQKJVyQXdItHSlPZ+e2yacPBicCY3vo6tav7y6PRyyz+d6ul8wIwxaJbq1T7ugGUWpWlSyBR0eMZrtafTdk0jU4v1aVKIS4Uk0hMQGC/fYdiuRdxDrHhuB0c82teov9R9P/lSpO5RzX96IL+fHZX9D67ogD0O7Irjm1D7bA+bvJwh5FpTwDHMOwgnBz2TGENFS3p81omkCyHD9jURtuhuMfbaIi4jWhaNXd+qdNnfFgP+Q6UVizR20Wxb5juLAQ1fSULI2CRPdmrni7lqCvkgaVxZPpIpZGSSPapo4TB1Tg+Jajv2tz8cpo6LYGzVW/JLS4B4xYcDDL2WE0IuNLDhWii690zykXYYXzCNWkwiakFYX6CCTYOQKRyIth8ZfaERl7cqxNZu4UDKR5EBN+AnxfwDLNp7GMtEIz7N+OJC4PQrZmBIo6kItmZ+ji+4n3GPnMilCKQ/xKnDjYNNy3uQEpw1pU32aRNM95LegsfRAUegpHzj/dgkHQ1ZiFY/QzUl/B5Uat5TnUUUOfxsan6gqV/HFkxvdoPrxpVEgXuTTD5yyN6b8UvpwiQfa725ZcUOxHMT+vEtadiQGXU3NgBSpZgQfzJqRru9Tc2pQJxxtM27RC4B+5NjViS/BQYSme7GoQZbHzfR3cXeUSMqf4oNeMiTg9o6JTaQ6pXgrDEhMJlikdO/3nc0uL3EmmZ+Pky5Ejv0al+Ro0CUFpokX1g8O0LbIU7iWe4V9Yaz+IBTciTFS39POCFeV4ONx4TAZDXxYGNnrJlwg2x6cqaJyYEhbBCX0Xjw6cqGbw702UriYO+Jk1heVLwtg1cnksQj+vlHuvXJLHWmZghRxlOlbtHb3dTG/2Sm8k1inOzyI5lAkCaNU/rstYV4aQpE2hQhQooQCLUVs+ZnEtZ+3MUji6QhfGl7F8TKRmthWqP4CGpa47lpba0wrXjQSjetHS1IFU1N7KXWPQGsStO37JKkwENGRw6NOrv58539JM3ZzXlyrTntzJIq2AueA+OSs0LwEN7+cDzJtNYNvzerwu8gM/ySFOUu3cFjuLTTmKVkppKcm9VL5JcD0UoLW/RCNJ9wIRpRA5ilXogwWcWJVW2Z7Mm0h60LaT26CSPQBh616jtIUcxoikINLpKinEYGrExRBqkpypSmKFEhh+3MwGXrgl8fuPFD6boS5Fk5yU/HRrS6IVa0wE8a65Gk8TY0IJo0qpDqRjqCJ+lJ4wgsthK5f8+dupKINXOGPK10OF7ohMOJ3VEh8OZYabGB2Wl87ZFpvOun8UGmepKVxrdhsloobbx2LNHRpllK+jyt0liZ10udydC2Z8NJp6nzMv1YChqRQWF1GCmsKvPC6iFZWBWjhZVMJvOmavZJT2t7WPKnSQo5d2CymrZPD4FO5tXtSpaSmcD49SYnMIzuZe7kJaL/WpGTSqEuWlDqViKl7sJtskrdRSSbG4DKs7TUnVc8yVL3ECbzrhFzHEwmKvVs/++WfGeAlXNy8F6XNaRorIw0H6x58yFUIXxa1XyYzOeC5gO3rPnQ87etYi06HZ6O3sYSJoIU/bYAlSFLRjw1PlzZDiqvaAdN+EQ7SE4X4m2bFZvMWXIe9FA6giRwERmcIamd5PUyEmMNunioP76cH4p/HPEGnZ0LGQBp0J3oISEeQYPuuja3AH/PlGUZly+l0FR5RpAB+DIK20i/JR/VMi0ub5lKfsuUgZZpIOTDh/fv34e+wnHQUzSTTmZ5JWJvWSs7wC64TU+DIBhc0z2ZZX496ODiflz7iU3sQMj3v4Dv4ckO90V/srSaKpNuy7bt1orqd4O+Vjhf9uAvx2fHj3ytQHOtJSk4vFdYuaTfznYTSrfX8h7z8dixihRy4ngm+fqxXd6Ieh/UjUbzTX1jb29S0hmZ6YQ/axnEsph4kfK6aX3loU4Lf19kAPfjqqbUawOa2Gyoid0tQaL3il6RP45tluy/VzPkCna32xp26JfFOOmNHUhQyBm8KnOG5zBstOR5Owz98ifHyYZMszuWZ99UyPKZqXw8x26+za9+dwcSExQNMp98Q/NmGA5UXldJKcrzhvvWrvQIrYI7KDVLbqO/Oj9FEARBEARBEARBEARBEARBEARBEARBEARBEARBEARBEARBEARBkLfN/wB3oTBsICbH9QAAAABJRU5ErkJggg==",
                                //"thumb_url": "http://example.com/path/to/thumb.png"
                            }
                        ]
                  
                    },'no_thread');
                    
                    convo.activate();
                    convo.ask('Does this look correct?', function (response, convo) {
                      convo.next();
                      if (response.text == 'no' || response.text == 'No' || response.text == 'NO') {
                        convo.say('Okay, let\'s try again.');
                        convo.next();
                        offerRide();
                      }
                    });
                  });

              });
          });
      }

    });


    controller.hears(['question'], 'direct_message,direct_mention', function(bot, message) {

        bot.createConversation(message, function(err, convo) {

            // create a path for when a user says YES
            /*convo.addMessage({
                    text: 'How wonderful.',
                    attachments: 
            },'yes_thread');*/

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

};
