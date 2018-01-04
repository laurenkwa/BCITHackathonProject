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
                    var jsonMap = "https://maps.googleapis.com/maps/api/directions/json?origin=" + origin.replace(/ /g, "%20") + "&destination=" + destination.replace(/ /g, "%20") + "&mode=driving&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY";
                    convo.say('https://maps.googleapis.com/maps/api/staticmap?size=600x400&path=enc%3AohqfIc_jpCkE%7DCx%40mJdDa%5BbD%7BM%7D%40e%40_MgKiQuVOoFlF%7DVnCnBn%40aDlDkN%7DDwEt%40%7DM%7DB_TjBy%7C%40lEgr%40lMa%60BhSi%7C%40%7COmuAxb%40k%7BGh%5E_%7BFjRor%40%7CaAq%7DC~iAomDle%40i%7BA~d%40ktBbp%40%7DqCvoA%7DjHpm%40uuDzH%7Dm%40sAg%7DB%60Bgy%40%7CHkv%40tTsxAtCgl%40aBoeAwKwaAqG%7B%5CeBc_%40p%40aZx%60%40gcGpNg%7CBGmWa%5CgpFyZolF%7BFgcDyPy%7CEoK_%7BAwm%40%7BqFqZaiBoNsqCuNq%7BHk%60%40crG%7B%5DqkBul%40guC%7BJ%7D%5DaNo%7B%40waA%7DmFsLc_%40_V%7Dh%40icAopBcd%40i_A_w%40mlBwbAiiBmv%40ajDozBibKsZ%7DvAkLm%5DysAk%7DCyr%40i%60BqUkp%40mj%40uoBex%40koAk_E_hG%7B%60Ac%7DAwp%40soAyk%40ogAml%40%7Bg%40qKsNeJw%5DeuA%7D%60Fkm%40czBmK%7Bg%40wCed%40b%40_e%40dT%7BgCzx%40csJrc%40ejFtGi%60CnB_pFhCa%60Gw%40%7Du%40wFwaAmP%7BoA%7Dj%40etBsRm_AiGos%40aCyy%40Lic%40tFohA~NeoCvC_%7CAWm~%40gb%40w~DuLex%40mUk_Ae_%40o_Aol%40qmAgv%40_%7DAaf%40qhAkMcl%40mHwn%40iCuq%40Nqi%40pF%7D%7CE~CyiDmFkgAoUedAcb%40ku%40ma%40cl%40mUko%40sLwr%40mg%40awIoA_aApDe~%40dKytAfw%40kyFtCib%40%7DA%7Bj%40kd%40usBcRgx%40uFwb%40%7BCulAjJmbC~CumAuGwlA_%5Du_C_PqyB%7BI%7DiAwKik%40%7DUcr%40ya%40up%40%7DkB%7DoCoQ%7Da%40aMyf%40an%40wjEimBuwKiYybC%7DLuyBoJ%7DhBuMieAwd%40i%7BB%7B~%40g%60D_Si%5Dsi%40%7Bk%40cPeSuH_T%7DNct%40kNcmC_Gyr%40mq%40_~AkmA%7DkCksByrE_N%7Bc%40oAcs%40%60J%7Bi%40t%7DByaHxNqt%40tGgxA%7CJ%7BkGeJ_aDsQi_HmFwuAmI%7BdA_XijByFgv%40%7DAiwBxDocAdM%7BlAtSmcAfUmaAptAmbGh~AcvGbwBc%7DHff%40shB~Isp%40nQu%7DB%60UsuCbBok%40l%40%7DzAhIwbA~OuaAnYwp%40rYwe%40%7CNke%40zc%40%7BhBrOwRdo%40sf%40xNaTb_%40uy%40ta%40k~%40xTap%40hl%40uiCre%40unHlIi~AlFsc%40rEkk%40aAce%40mL%7DlAwPcyB_GohBzDsqAtMqtA~h%40weDtFkd%40Bi%60%40_XwfEdAag%40dEkM%60%40zAqApJef%40%7BP_o%40sYys%40ai%40yf%40_j%40y_%40oi%40mVi%5EmFqSwAiPtDuQbc%40_nAtZyaAlEkc%40r%40eq%40%7CAo%5BrTwcAtVuz%40vQ%7Dd%40%7CPmb%40xT%7B%5CzZyd%40jG%7BRzL%7Dh%40jr%40ov%40rFiImFqPiD%7BJ&key=YOUR_API_KEY');
                    convo.ask('Does this look correct?', function (response, convo) {
                      convo.next();
                      if (response.text == 'no' || response.text == 'No' || response.text == 'NO') {
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
