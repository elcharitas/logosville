<div class="chatSidebar" style="box-shadow: 1px 0 0 #f0f0f2 inset;border-left-color: #ccc;">
    <div class="margin"></div>
    <div>
        <div>
            <div class="box">
                <div class="sIB">
                    <div class="clearfix">
                        <div class="sH">CONTACTS ONLINE</div>
                    </div>
                </div>
            </div>
            <ul class="box">
                <?php
                    echo $lvFriend->activeFriends($lvOnline);
                ?>
            </ul>
        </div>
        <div>
            <div class="box">
                <div class="sIB">
                    <div class="clearfix">
                        <div class="sH">CONTACTS</div>
                    </div>
                </div>
            </div>
            <ul class="box">
                <?php
                    echo $lvFriend->listFriends($lvOnline);
                ?>
            </ul>
        </div>
    </div>
    <div class="margin"></div>
</div>

<div class="buddyListPage">
    <div class="buddyNum">
        <div class="numListBtn">
            <div class="numText">
                <span class="label">Chat(<i id="noAU"></i>)</span>
                <div>
                    <a href="/messages?new"  class="label" title="New Message" style="color:#33334c;line-height:16px;margin-left: 30px">New Message</a>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/assets/css/styles.css">
<script>
    window.setInterval(function(){
        $.ajax({
            url: "/act.php",
            type: "GET",
            success: function(response){
                $('#noAU').html(response);
            }
        });
    }, 60000);
    $.ajax({
        url: "/act.php",
        type: "GET",
        success: function(response){
            $('#noAU').html(response);
        }
    });
</script>