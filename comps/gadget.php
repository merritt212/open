<?if($lg){?>
<div class="chatgt">
 <div class="msggt">
  <div class="close">x</div>
  <div class="chattop">
   Chat | 
   <a id="cwinopen" href="chat?id=">Open Independently</a>
  </div>
  <?include("comps/chat_rend.php");?>
  <?echo show_chat("gadget");?>
 </div>
 <div class="usersgt">
  <div class="cusgt">
   <div class="close">x</div>
   <img src="<?echo get("avatar");?>"/>
   <div class="otd">
    <a href="profile"><?echo get("name",$who,false);?></a><br/>
    <span class="status on"></span><span>Online</span>
   </div>
  </div>
  <div class="users">
   <?
    $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)");
    $sql->execute(array(":who"=>$who));
    while($r=$sql->fetch()){
     $id=$r['fid'];
     $fname=get("fname",$id,false);
     $name=get("name",$id,false);
     $img=get("avatar",$id);
     $st=get("status",$id);
     echo "<div class='user' id='$id'><img height='32' width='32' src='$img'/><span class='status $st'>$st</span><span class='name' title='$name'>$fname</span></div>";
    }
   ?>
  </div>
 </div>
 <a class="openugt" href="javascript:;">Open Chat</a>
</div>
<script>if(localStorage['chatgtopen']!=0){$(".content").css("margin-right","350px");}</script>
<?}?>
