<?
include("config.php");
include("../comps/chat_rend.php");
ch();
$to=$_GET['to'];
$lid=$_GET['lid'];
$lup=false;
$isFri=false;
if($to!="gadget"){
 $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid=:fid AND uid IN (SELECT fid FROM conn WHERE uid=:fid)");
 $sql->execute(array(":who"=>$who,":fid"=>$to));
 if($sql->rowCount()==0){
  ser();
 }
}
if($lid!="" && $to!="gadget"){
 $sql=$db->prepare("SELECT id,red FROM chat WHERE (id > :ex OR id=:ex) AND (uid=:fid AND fid=:uid) ORDER BY id ASC LIMIT 1");
 $sql->execute(array(":ex"=>$lid,":uid"=>$who,":fid"=>$to));
 if($sql->rowCount()!=0){
  while($r=$sql->fetch()){
   if($r['id']!=$lid){
    $mid=$r['id'];
   }else{
    if($r['red']==0){
     $lup=true;
    }
   }
  }
  if($lup){
   $msg=str_replace('"','\"',show_chat($to,$lid));  
  }else{
   if($mid!=""){
    $msg=str_replace('"','\"',show_chat($to,$mid));
   }
  }
  if($msg!=""){
?>
   if(localStorage['onFormSion']==0){
    p="<?echo$msg;?>";
    <?
    if($lup){
    ?>
     $("#<?echo$to;?>.msgs #<?echo$lid;?>.msg").replaceWith(p);
     $(".msggt").show();
    <?
    }else{
    ?>
     if($("#<?echo$to;?>.msgs .msg").length==0){$("#<?echo$to;?>.msgs").html("");}
     if($("#<?echo$to;?>.msgs #<?echo$mid;?>.msg").length==0){
      $("#<?echo$to;?>.msgs").append(p);
     }
     $(".msggt").show();
    <?
    }
    ?>
    mcTop();
   }
<?
  }
 }
 $userstatus=array();
 $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)");
 $sql->execute(array(":who"=>$who));
 while($r=$sql->fetch()){
  $userstatus[$r['fid']]=get("status",$r['fid']);
 }
 $userstatus=json_encode($userstatus);
?>
 var userstatus=<?echo$userstatus;?>;
 $(".users .user").each(function(){
  id=$(this)[0].id;
  if($(this).find(".status").text()!=userstatus[id]){
   $(this).find(".status").replaceWith("<span class='status "+userstatus[id]+"'>"+userstatus[id]+"</span>");
  }
 });
<?
}elseif($_GET['all']=="true"){
 $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid=:fid AND uid IN (SELECT fid FROM conn WHERE uid=:fid)");
 $sql->execute(array(":who"=>$who,":fid"=>$to));
 if($sql->rowCount()==0){
  ser();
 }
 $ht=str_replace('"','\"',show_chat($to,true));
?>
 $("#<?echo$to;?>.msgs").html("<?echo$ht;?>");
 mcTop();
 t=$(".users #<?echo$to;?>.user");
 t.css({background:"white",color:"black"});
 t.attr("title","Unread Messages");
<?
}
if($to!=""){
 $sql=$db->prepare("SELECT uid FROM chat WHERE fid=:uid AND red='0' ORDER BY id LIMIT 1");
 $sql->execute(array(":uid"=>$who));
 if($sql->rowCount()!=0){
  $id=$sql->fetchColumn();
?>
  t=$(".users #<?echo$id;?>.user");
  t.css({background:"red",color:"white"});
  t.attr("title","Unread Messages");
<?
 }
}else{
 ser();
}
?>
