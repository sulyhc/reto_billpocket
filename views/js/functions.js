/**
 * Created by walter on 8/07/17.
 */

/**
 * load user games table by ajax
 */
function displayUserTable(){
        var url = "http://localhost/billpocket/user_details/";
        var steamUser = $('#inputPlayerNick').val();
        $.ajax({
        type:'GET',
        url: url,
        data: {'action':'user_history','id':steamUser},
            beforeSend:function(){
                $('#displayResults').html('LOADING....');
            },
        success:function (data) {
            $('#displayResults').html(data);
        }
    });
}
/**
 * Show popup with stat information of user game
 * @param steamID
 * @param appId
 */
function displayModalGame(steamID, appId){
    var url = "http://steamcommunity.com/profiles/" + steamID + "/stats/" + appId;
    window.open(url, 'user stats','height=600,width=800');
}