<?php
// /todo
// pouvoir lire les données sauvegardées du serveur quand on est connecté
// todo/
session_start();
if(get_magic_quotes_gpc()==1){
 $_GET    =hmq1($_GET);
 $_POST   =hmq1($_POST);
 $_COOKIE =hmq1($_COOKIE);
 $_REQUEST=hmq1($_REQUEST);
}
if(isset($_POST['data'])){ // gestion des post

 // formulaires exemples, normalement ils devraient être en bdd
     $jsonformul1=array(
      1 => array(
        'name'   => 'formulaire bidon 1',
        'active' => true,
        'fields'   => array(
          'nom'    => array( 'type' => 'c' , 'tag'=>'Nom'),
          'prenom' => array( 'type' => 'c' , 'tag'=>'Prénom'),
          'age'    => array( 'type' => 'i' , 'tag'=>'âge'),
        ),
        'conditions' => array(  // seul 'notvoid' est pris en compte dans ce dev, à compléter ...
           array( 'type' => 'field' , 'category' => 'notvoid' , 'test' => array( 'field' => 'nom'    , 'message' => 'le nom ne doit pas être vide' ) ),
           array( 'type' => 'field' , 'category' => 'notvoid' , 'test' => array( 'field' => 'prenom' , 'message' => 'le prénom ne doit pas être vide' ), )
        ),
      ),
      2 => array(
        'name' => 'formulaire bidon 2',
        'active' => true,
        'fields' => array(
          'nom'       => array( 'type' => 'c' , 'tag'=>'Nom'),
          'prenom'    => array( 'type' => 'c' , 'tag'=>'prénom'),
          'num_secu'  => array( 'type' => 'i' , 'tag'=>'Num sécu'),
          'malade'    => array( 'type' => 'b' , 'tag'=>'malade ?'),
        ),
        'conditions' => array(
        ),
      ),
      /* 
      // un 3eme formulaire pour des cas de test ( suppression / ajout formulaires )
      3 => array(
        'name' => 'formulaire bidon 3',
        'active' => true,
        'fields' => array(
          'nom'       => array( 'type' => 'c' , 'tag'=>'Nom'),
          'prenom'    => array( 'type' => 'c' , 'tag'=>'prénom'),
          'malade'    => array( 'type' => 'b' , 'tag'=>'malade ?'),
        ),
        'conditions' => array(
        ),
      ),
      */
     );
 // formulaires/


 $ret=array(
  'status' => 'KO',
  'message' => array(),
  'output' => array(),
 );
 $ret['input']=json_decode($_POST['data'],true);
 
 
 if(isset($ret['input']['funct'])&&$ret['input']['funct']!=''){
  
   // traitement des posts !
  
   if($ret['input']['funct']=='connexion'){
    if( // normalement, ça devrait être en bdd !
        ( $ret['input']['login']=='hdf' && $ret['input']['password']=='hdf' )
     || ( $ret['input']['login']=='tcz' && $ret['input']['password']=='tcz' )
     || ( $ret['input']['login']=='ese' && $ret['input']['password']=='ese' )
    ){
     $ret['jsonformul1']=$jsonformul1;
     $ret['status']='OK';
     $_SESSION['logged']=$ret['input']['login'];
    }
   }


   if($ret['input']['funct']=='deconnexion'){
    unset($_SESSION['logged']);
    $ret['status']='OK';
   }


   if($ret['input']['funct']=='verifierConnexion'){
    if(isset($_SESSION['logged'])){
     $ret['login']=$_SESSION['logged'];
     $ret['status']='OK';
    }
   }

   if($ret['input']['funct']=='chargerMesFormulaires'){
    if(isset($_SESSION['logged'])){
     $ret['jsonformul1']=$jsonformul1;
     $ret['login'] =$_SESSION['logged'];
     $ret['status']='OK';
    }
   }

   if($ret['input']['funct']=='envoyer'){
    if(isset($_SESSION['logged'])){
     $phpData=json_decode($ret['input']['dataformul1'],true);
     if(!is_null($phpData)){
      $fileName='data_formulaire_'.$ret['input']['id_formulaire'].'_by_'.$_SESSION['logged'].'.php';
      $writeHeader=false;
      if(!file_exists($fileName)){
       $writeHeader=true;
      }
      if($fd=fopen($fileName,'a+')){
       if($writeHeader){
        fwrite($fd,'<?'.'php'."\r\n".'$t[]=array();'."\r\n");
       }
       foreach($phpData as $k1=>$v1){
        fwrite($fd,'$t[]='.var_export($v1,true).';'."\r\n");
       }
       fclose($fd);
       $ret['id_formulaire'] =$ret['input']['id_formulaire'];
       $ret['end']           =$ret['input']['end'];
       $ret['status']='OK';
      }
     }
    }
   }


 }
 
 header('Content-Type: application/json');
 echo json_encode($ret);
 exit(0);
}

?>
<!doctype html>
<html lang="fr" manifest="formulaire.appcache" >
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="hello">
<!-- link rel="stylesheet" href="bootstrap.min.css" -->
<title>Hello, world!</title>
<style>
*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;margin:0;padding:0;}
html,body{height:100%;width:100%;}
body{background-color:#cdf2ff;color:#005063;font-family: verdana, arial, sans-serif;font-size:16px;
-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;
-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;text-size-adjust:100%;width:100%;position:absolute;touch-action:manipulation;}
*{scrollbar-color: #34d3f7 #bfe8ff;}
*::-webkit-scrollbar {width: 1.2em;background:#bfe8ff;}
*::-webkit-scrollbar-thumb {background-color: #34d3f7;}
*::-webkit-scrollbar-corner{background-color: #34d3f7;}
*::-webkit-resizer{background-color: #34d3f7;}
a,button{display:inline-block;font-size:1.3em;line-height:1.3em;text-decoration:none;border:1px #eee outset;padding:0 1px;min-width:2em;margin:5px;border-radius:5px;box-shadow:0px 0px 4px #aaa;color:#006c84;background:linear-gradient(to bottom,#beedff 0%, #7eddff 100%);}
a:visited{color:#005063;}
input{background-color:#cdf2ff;padding:5px;border:1px #eee inset;font-family: verdana, arial, sans-serif;font-size:16px;border-radius:3px;}

</style>
</head>
<body style="max-width:800px;" >
  <div id="menu" style="position:fixed;border:1px #eee solid;height:50px;width:100%;background:white;" ></div>    
  <div id="content" style="padding:50px 0 0 0;"></div>
<script type="text/javascript">
function myObj1(objname){
 "use strict";
 var onLineStatus1=false;
 var jsonformul1=null;
 var loginName1='';
 var pageStatus='';
 var idFormulaire=0;
 var chunkNumber=0;
 var chunkWeight=3072; // octets par chunk 2048 , 3072 , 4096 ...
 var chunkTimeout=150;
 var chunkSize=0;  // calculé dynamiquement : contient le nombre de formulaires à envoyer par appel ajax
 var chunkCount=0; // calculé dynamiquement : le nombre d'appels ajax
 //=====================================================================================================================
 function home(){
  pageStatus='';
  updateMenu();
  updatePage();
 }
 //=====================================================================================================================
 function verifierConnexion(){
  
  var r = new XMLHttpRequest();
  r.open("POST",'index.php?verifierConnexion',true);
  r.timeout=6000;
  r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  r.onreadystatechange = function () {
   if (r.readyState != 4 || r.status != 200) return;
   try{
    var jsonRet=JSON.parse(r.responseText);
    if(jsonRet.status=='OK'){
     loginName1=jsonRet.login;
     updateMenu();
     updatePage();
    }else{
     home();
    }
   }catch(e){
    console.error(e);
   }
  };
  r.onerror=function(e){
//   console.error('e=',e); // si on est offline, ce n'est pas une erreur
   home();
  }
  var data={
   funct          : 'verifierConnexion',
  }
  r.send('data='+encodeURIComponent(JSON.stringify(data)));
  
 }
 
 //=====================================================================================================================
 function testData(id_formulaire,data){
  var ret={
   status:'OK',
   messages:[],
   message:'',
  };
  jsonformul1=localStorage.getItem('formulaires1');
  if(jsonformul1!==null){
   jsonformul1=JSON.parse(jsonformul1);
   for(var elem in jsonformul1){
    if(elem==id_formulaire){
     var formulaire=jsonformul1[elem];
     if(formulaire.hasOwnProperty('conditions')){
      for(var i=0;i<formulaire.conditions.length;i++){
//           array( 'type' => 'field' , 'category' => 'notvoid' , 'test' => array( 'field' => 'nom' , 'message' => 'le nom ne doit pas être vide' ) )
       var condition=formulaire.conditions[i];
       console.log(condition);
       if(condition.type=='field'){
        if(condition.category=='notvoid'){
         if(data[condition.test.field]==''){
          ret.status='KO';
          ret.messages.push({
            field    : condition.test.field,
            tag      : formulaire.fields[condition.test.field].tag,
            message  : condition.test.message
          });
         }
        }
       }
      }
     }
    }
   }
  }
  if(ret.status!='OK'){
   var t='';
   for(var i=0;i<ret.messages.length;i++){
    ret.message+='champ "'+ret.messages[i].tag+'" : ' + ret.messages[i].message + '\n';
   }
  }
  return ret;
 }
 //=====================================================================================================================
 function enregistrer(id_formulaire){
  var data={};
  var lst=document.getElementById('dataToSave').getElementsByTagName('input');
  for(var i=0;i<lst.length;i++){
   if(lst[i].type=='radio'){
    var radios = document.getElementsByName(lst[i].name);
    for(var j = 0, length = radios.length; j < length; j++){
     if(radios[j].checked){
      data[radios[j].name]=radios[j].id.substr(0,1);
      break;
     }
    }
   }else{
    data[lst[i].name]=lst[i].value;
   }
  }
  var retTest=testData(id_formulaire,data);
  if(retTest.status!='OK'){
   alert('Problème !\n '+retTest.message+'\n\n');
   return;
  }
  
  try{
   var dataformul1=localStorage.getItem('data_formulaires_'+id_formulaire);
   if(dataformul1==null){
    dataformul1=[];
    dataformul1.push(data);
    localStorage.setItem('data_formulaires_'+id_formulaire , JSON.stringify(dataformul1));
   }else{
    dataformul1=JSON.parse(dataformul1);
    dataformul1.push(data);
    localStorage.setItem('data_formulaires_'+id_formulaire , JSON.stringify(dataformul1));
   }
  }catch(e){}
  home();
 }
 //=====================================================================================================================
 function saisir(id_formulaire){
  
  pageStatus='saisir';
  jsonformul1=localStorage.getItem('formulaires1');
  if(jsonformul1!==null){
   jsonformul1=JSON.parse(jsonformul1);
   for(var elem in jsonformul1){
    if(elem==id_formulaire){
     var formulaire=jsonformul1[elem];
     
     var t=formulaire.name+'<div id="dataToSave">';
     for(var kf in formulaire.fields){
      var field=formulaire.fields[kf];
      t+=field.tag+'<br />';
      if(field.type=='c'){
        t+='<input type="text" id="'+kf+'" name="'+kf+'" value="" />'
      }else if(field.type=='i'){
        t+='<input type="number" id="'+kf+'" name="'+kf+'" value="" />'
      }else if(field.type=='b'){
        t+='Oui <input type="radio" id="1'+kf+'" name="'+kf+'" />'
        t+=', Non <input type="radio" id="0'+kf+'" name="'+kf+'" />'
        t+=', NSPP <input type="radio" id="2'+kf+'" name="'+kf+'" checked="checked" />'
      }
      t+='<br />';
     }
     t+='<button onclick="'+objname+'.enregistrer('+id_formulaire+')">Enregistrer</button>'
     t+='</div>';
     document.getElementById('content').innerHTML=t;
     
    }
   }
  }
 }
 
 //=====================================================================================================================
 function enregistrerLaModif(id_formulaire,indice){
  var data={};
  var lst=document.getElementById('dataToSave').getElementsByTagName('input');
  var oneDone=false;
  for(var i=0;i<lst.length;i++){
   if(lst[i].type=='radio'){
    var radios = document.getElementsByName(lst[i].name);
    for(var j = 0, length = radios.length; j < length; j++){
     if(radios[j].checked){
      data[radios[j].name]=radios[j].id.substr(0,1);
      oneDone=true;
      break;
     }
    }
   }else{
    data[lst[i].name]=lst[i].value;
    oneDone=true;
   }
  }
  
  if(oneDone){
   try{
    var retTest=testData(id_formulaire,data);
    if(retTest.status!='OK'){
     alert('Problème !\n '+retTest.message+'\n\n');
     return;
    }
    
    var dataformul1=localStorage.getItem('data_formulaires_'+id_formulaire);
    if(dataformul1!==null){
     dataformul1=JSON.parse(dataformul1);
     data.id_formulaire=id_formulaire;
     dataformul1[indice]=data;
     localStorage.setItem('data_formulaires_'+id_formulaire , JSON.stringify(dataformul1));
    }
   }catch(e){
    console.error(e);
   }
  }
  home();
 }
 //=====================================================================================================================
 function modifier(id_formulaire,indice){
  var t='';
  var dataformul1=localStorage.getItem('data_formulaires_'+id_formulaire);
  if(dataformul1==null){
   t+='Il n\'y a pas de données à modifier';
   document.getElementById('content').innerHTML=t;  
   return;   
  }else{
   dataformul1=JSON.parse(dataformul1);
   try{
    var data=dataformul1[indice];
    console.log(data);
    jsonformul1=localStorage.getItem('formulaires1');
    if(jsonformul1!==null){
     jsonformul1=JSON.parse(jsonformul1);
     for(var elem in jsonformul1){
      if(elem==id_formulaire){
       t+=jsonformul1[elem].name+'<div id="dataToSave">';
       var formulaire=jsonformul1[elem];
       for(var kf in formulaire.fields){
        var field=formulaire.fields[kf];
        t+=field.tag+'<br />';
        if(field.type=='c'){
          var theData='';
          if(data.hasOwnProperty(kf)){
           theData=data[kf];
          }
          t+='<input type="text" id="'+kf+'" name="'+kf+'" value="'+ent1(theData)+'" />'
        }else if(field.type=='i'){
          var theData='';
          if(data.hasOwnProperty(kf)){
           theData=data[kf];
          }
          t+='<input type="number" id="'+kf+'" name="'+kf+'" value="'+ent1(theData)+'" />'
        }else if(field.type=='b'){
          var theData='2';
          if(data.hasOwnProperty(kf)){
           theData=data[kf];
          }
          t+='Oui <input type="radio" id="1'+kf+'" name="'+kf+'" '+(theData=='1'?'checked="checked"':'')+' />'
          t+=', Non <input type="radio" id="0'+kf+'" name="'+kf+'" '+(theData=='0'?'checked="checked"':'')+' />'
          t+=', NSPP <input type="radio" id="2'+kf+'" name="'+kf+'" '+(theData=='2'?'checked="checked"':'')+' />'
        }
        t+='<br />';
       }
      }
     }
    }
    t+='<button onclick="'+objname+'.enregistrerLaModif('+id_formulaire+','+indice+')">Enregistrer la modification</button>'
    t+='</div>';
    document.getElementById('content').innerHTML=t;
   }catch(e){
    console.error(e);
   }
   document.getElementById('content').innerHTML=t;  
  }  
 }
 //=====================================================================================================================
 function supprimer(id_formulaire,indice){
  if(!window.confirm('Certain ?')){
   return;
  }
  var t='';
  var dataformul1=localStorage.getItem('data_formulaires_'+id_formulaire);
  if(dataformul1==null){
   t+='Il n\'y a pas de données à supprimer';
   document.getElementById('content').innerHTML=t;  
   return;   
  }else{
   dataformul1=JSON.parse(dataformul1);
   dataformul1.splice(indice,1);
   localStorage.setItem('data_formulaires_'+id_formulaire , JSON.stringify(dataformul1));
   voir(id_formulaire);
  }
 }
 //=====================================================================================================================
 function envoyer(id_formulaire){
  chunkNumber=0;
  var dataformul1=localStorage.getItem('data_formulaires_'+id_formulaire);
  if(dataformul1==null){
   home();
   return;
  }
  var tailleChaine=dataformul1.length;
  if(tailleChaine<=2){
   home();
   return;
  }
  dataformul1=JSON.parse(dataformul1);
  var nbElements=dataformul1.length;
  if(nbElements<1){
   home();
   return;
  }
  try{
   var tailleParBloc=parseInt(tailleChaine/nbElements,10);
   if(tailleParBloc>chunkWeight){
    chunkSize=1;
   }else{
    chunkSize=parseInt(chunkWeight/tailleParBloc,10);
   }
   chunkCount=parseInt(nbElements/chunkSize,10)+1;
   setTimeout(
    function(){envoyerChunk(id_formulaire);},
    chunkTimeout
   );
  }catch(e){
   home();
  }
   
 }
 //=====================================================================================================================
 function envoyerChunk(id_formulaire){
  chunkNumber++;
  document.getElementById('content').innerHTML='Veuillez patienter, envoi du tronçon '+(chunkNumber)+' / '+chunkCount+' <br ><span style="font-size:2em;color:red;">Veuillez patienter !</span>';  
  
  var dataformul1=localStorage.getItem('data_formulaires_'+id_formulaire);
  if(dataformul1==null){
   t+='Il n\'y a plus de données non synchronisées pour ce formulaire';
   document.getElementById('content').innerHTML=t;   
  }else{
   
   var r = new XMLHttpRequest();
   r.open("POST",'index.php?envoyer',true);
   r.timeout=6000;
   r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
   r.onreadystatechange = function () {
    if (r.readyState != 4 || r.status != 200) return;
    try{
     var jsonRet=JSON.parse(r.responseText);
     if(jsonRet.status=='OK'){
      var dataformul1=localStorage.getItem('data_formulaires_'+jsonRet.id_formulaire);
      if(dataformul1==null){
      }else{
       dataformul1=JSON.parse(dataformul1);
       dataformul1.splice(0,jsonRet.end);
       if(dataformul1.length==0){
        localStorage.removeItem('data_formulaires_'+jsonRet.id_formulaire);
       }else{
        localStorage.setItem('data_formulaires_'+jsonRet.id_formulaire,JSON.stringify(dataformul1));
        setTimeout(
         function(){envoyerChunk(id_formulaire);},
         chunkTimeout
        );
        return;
       }
      }
      home();
     }else{
      demanderConnexion();
     }
    }catch(e){
     console.error(e);
    }
   };
   r.onerror=function(e){
    console.error('e=',e);
   }
   dataformul1=JSON.parse(dataformul1);
   var end=-1;
   if(dataformul1.length>0){
    if(dataformul1.length>chunkSize){
     end=chunkSize;
    }else{
     end=dataformul1.length;
    }
   }
   if(end>=0){
    var toSend=[];
    for(var i=0;i<end;i++){
     toSend[toSend.length]=dataformul1[i];
    }
    var data={
     funct          : 'envoyer',
     dataformul1    : JSON.stringify(toSend),
     id_formulaire  : id_formulaire,
     end            : end,
    }
    r.send('data='+encodeURIComponent(JSON.stringify(data)));
   }
  }
 }
 //=====================================================================================================================
 function supprimerTout(id_formulaire){
  if(!window.confirm('Certain ?')){
   return;
  }
  var dataformul1=localStorage.getItem('data_formulaires_'+id_formulaire);
  if(dataformul1==null){
   t+='Il n\'y a pas de données non synchronisées pour ce formulaire';
  }else{
   localStorage.removeItem('data_formulaires_'+id_formulaire);
   home();
  }
 }
 //=====================================================================================================================
 function voir(id_formulaire){
  document.getElementById('content').innerHTML='Veuillez patienter';
  
  window.setTimeout(function() {
    var t='';
    var formulaire=null;
    var count=0;
    var dataformul1=localStorage.getItem('data_formulaires_'+id_formulaire);
    if(dataformul1==null){
     t+='Il n\'y a pas de données non synchronisées pour ce formulaire';
    }else{
     var tabsChamps=[];
     jsonformul1=localStorage.getItem('formulaires1');
     jsonformul1=JSON.parse(jsonformul1);
     for(var elem in jsonformul1){
      if(elem==id_formulaire){
       formulaire=jsonformul1[elem];
       for(var kf in formulaire.fields){
        var field=formulaire.fields[kf];
        tabsChamps.push({
         name:kf,
         tag:field.tag,
         type:field.type,
        })
       }
      }
     }
     
     var t2='';
     
     dataformul1=JSON.parse(dataformul1);
     for(var i=dataformul1.length-1;i>=0;i--){
      count++;
      t2+='<hr />';
      var data=dataformul1[i];
      for(var j=0;j< tabsChamps.length;j++){
       
       t2+='<div style="border:1px #eee solid;padding:5px;">';
       t2+=tabsChamps[j].tag+' : ';
       t2+=data[tabsChamps[j].name]+'';
       t2+='</div>';
      }
      t2+='<div style="border:1px #eee solid;padding:5px;">';
      t2+='<button onclick="'+objname+'.supprimer('+id_formulaire+','+i+')">Supprimer</button>';
      t2+='<button onclick="'+objname+'.modifier('+id_formulaire+','+i+')">Modifier</button>';
      t2+='</div>';
     }
     if(t2!==''){
      var hea='<h2>'+formulaire.name+'</h2>'+'<p>Il y a '+count+' enregistrement(s)</p>';
      hea+='<button onclick="'+objname+'.supprimerTout('+id_formulaire+')">Supprimer toutes ces données</button>';
      t2=hea+t2;
      if(onLineStatus1 && loginName1!=''){
       t+='<hr >Vous êtes connecté au serveur et vous pouvez envoyer ces données';
       t+='<button onclick="'+objname+'.envoyer('+id_formulaire+')">Envoyer ces données</button>';
       t+='<hr >';
      }
     }else{
      localStorage.removeItem('data_formulaires_'+id_formulaire);
      home();
      return;
     }
     document.getElementById('content').innerHTML=t+t2;   
    } 
  },125);

  

 }
 //=====================================================================================================================
 function suppFormulaire(id_formulaire){
  if(!window.confirm('Certain ?')){
   return;
  }
  jsonformul1=localStorage.getItem('formulaires1');
  if(jsonformul1==null){
   home();
  }else{
   var jsonforTemp={};
   jsonformul1=JSON.parse(jsonformul1);
   var oneDone=false;
   for(var elem in jsonformul1){
    if(elem==id_formulaire){
    }else{
     jsonforTemp[elem]=jsonformul1[elem];
     oneDone=true;
    }
   }
   if(oneDone==true){
    localStorage.setItem('formulaires1' , JSON.stringify(jsonforTemp));
   }else{
    localStorage.removeItem('formulaires1');
   }
   home();
  }
 }
 //=====================================================================================================================
 function updatePage(){
  var t='';
  if(pageStatus=='connexion' && onLineStatus1==false){
   t+='Vous êtes hors ligne';
  }
  if(pageStatus=='saisir'){
  }else{
   t='<hr />';
   jsonformul1=localStorage.getItem('formulaires1');
   if(jsonformul1==null){
     t+='Vous n\'avez aucun formulaire attaché à votre terminal';
     if(loginName1===''){
      t+='<br />Vous devez vous connecter pour charger les formulaires';
     }
     if(loginName1!==''){
      t+='<button onclick="'+objname+'.chargerMesFormulaires()">charger mes formulaires</button>';
     }
   }else{
    jsonformul1=JSON.parse(jsonformul1);
    for(var elem in jsonformul1){
     t+='<hr />'+jsonformul1[elem]['name'];
     if(jsonformul1[elem]['active']==true){
      t+=' <button onclick="'+objname+'.saisir('+elem+')">Saisir des données</button>';
     }else{
      t+=' Ce formulaire n\'est plus actif';
     }
     
     var dataformul1=localStorage.getItem('data_formulaires_'+elem);
     if(dataformul1==null){
      if(loginName1!==''){
       t+='<button onclick="'+objname+'.suppFormulaire('+elem+')">Supprimer ce formulaire</button>';
      }
     }else{
      t+='<button onclick="'+objname+'.voir('+elem+')">Voir les données non synchronisées</button>';
     }
    }
   }
   var derniereConnexion=localStorage.getItem('derniereConnexion');
   if(derniereConnexion!==null && loginName1=='' ){
     var maintenant=new Date();
     maintenant=maintenant.getTime();
     var diff=parseInt((maintenant-parseInt(derniereConnexion,10))/1000,10);
     var jours=0;
     var heures=0;
     var minutes=0;
     if(diff>=60){
      minutes=parseInt(diff/60,10);
      if(minutes>=60){
       heures=parseInt(minutes/60);
       minutes=minutes-heures*60;
       if(heures>=24){
        jours=parseInt(heures/24);
        heures=heures-jours*24;
       }
      }
     }
     if(minutes>0){
      t+='<hr />Votre dernière connexion a été faite il y a '+(jours>0?jours + ' jour(s) ':'')+' '+(heures>0?heures + ' heure(s) ':'')+' '+(minutes>0?minutes + ' minute(s) ':'');
     }
   }
   
   
   document.getElementById('content').innerHTML=t;
  }
 }
 
 //=====================================================================================================================
 function chargerMesFormulaires(){
  var r = new XMLHttpRequest();
  r.open("POST",'index.php?chargerMesFormulaires',true);
  r.timeout=6000;
  r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  r.onreadystatechange = function () {
   if (r.readyState != 4 || r.status != 200) return;
   try{
    var jsonRet=JSON.parse(r.responseText);
    if(jsonRet.status=='OK'){
     localStorage.setItem('formulaires1' , JSON.stringify(jsonRet.jsonformul1));
    }else{
     alert('il y a eu une erreur de chargement');
    }
     updateMenu();
     updatePage();
   }catch(e){
    console.error(e);
   }
  };
  r.onerror=function(e){
   console.error('e=',e);
  }
  var data={
   funct          : 'chargerMesFormulaires',
  }
  r.send('data='+encodeURIComponent(JSON.stringify(data)));
  
  
 }
 //=====================================================================================================================
 function updateMenu(){
  if(onLineStatus1){
   var t='<button onclick="'+objname+'.home()">🏠</button><span style="color:green;">&nbsp;Online&nbsp;</span>';
   if(loginName1!==''){
    t+='<button onclick="'+objname+'.deconnexion()" style="float:right;">déconnexion</button>'
   }else{
    t+='<button onclick="'+objname+'.demanderConnexion()"  style="float:right;">connexion</button>'
   }
   t+='<div style="display:inline-block;float:right;line-height:1.2em;padding:10px;">'+(loginName1!=''?' connecté : '+loginName1:' déconnecté')+'</div>';
   document.getElementById('menu').innerHTML=t;
  }else{
   document.getElementById('menu').innerHTML='<button onclick="'+objname+'.home()">🏠</button><span style="color:red;">&nbsp;Offline&nbsp;</span>'+'';
  }
 }
 //=====================================================================================================================
 function verifierPresenceFormulaires(){
  jsonformul1=localStorage.getItem('formulaires1');
  if(jsonformul1==null){
   if(onLineStatus1){
    demanderConnexion();
   }else{
    document.getElementById('content').innerHTML='<span style="color:red;font-size:2em;">Vous êtes hors ligne sans formulaire donc vous ne pouvez rien saisir.<br /> Veuillez vous connecter à un réseau pour pouvoir charger le formulaire</span>'+'';
   }
  }
 }
 //=====================================================================================================================
 function deconnexion(){
  var r = new XMLHttpRequest();
  r.open("POST",'index.php?deconnexion',true);
  r.timeout=6000;
  r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  r.onreadystatechange = function () {
   if (r.readyState != 4 || r.status != 200) return;
   try{
    var jsonRet=JSON.parse(r.responseText);
    if(jsonRet.status=='OK'){
     loginName1='';
     pageStatus='';
     updateMenu();
     updatePage();
    }else{
     alert('erreur de deconnexion');
     demanderConnexion();
    }
   }catch(e){
    console.error(e);
   }
  };
  r.onerror=function(e){
   console.error('e=',e);
  }
  var data={
   funct          : 'deconnexion',
  }
  r.send('data='+encodeURIComponent(JSON.stringify(data)));
 }
 //=====================================================================================================================
 function connexion(){
  var r = new XMLHttpRequest();
  r.open("POST",'index.php?connexion',true);
  r.timeout=6000;
  r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
  r.onreadystatechange = function () {
   if (r.readyState != 4 || r.status != 200) return;
   try{
    var jsonRet=JSON.parse(r.responseText);
    if(jsonRet.status=='OK'){
     loginName1=document.getElementById('login').value;
     var maintenant=new Date();
     maintenant=maintenant.getTime();
     localStorage.setItem('derniereConnexion' , JSON.stringify(maintenant));
     
     jsonformul1=localStorage.getItem('formulaires1');
     if(jsonformul1==null){
      localStorage.setItem('formulaires1' , JSON.stringify(jsonRet.jsonformul1));
     }else{
      var jsonformul1Local=JSON.parse(jsonformul1);
      var nouveaux={};
      var oneDone=false;
      
      // comparer les anciens et les nouveaux
      
      // 1°) local toujours actif ? 
      for(var k1 in jsonformul1Local){
       var forLoc=jsonformul1Local[k1];
       var actif=false;
       if(jsonRet.jsonformul1.hasOwnProperty(k1)){
        if(jsonRet.jsonformul1[k1]['active']===true){
         actif=true;
        }
       }
       console.log(k1 + ' actif=' + actif );
       if(actif==true){
        nouveaux[k1]=jsonRet.jsonformul1[k1];
        oneDone=true;
       }else{
        // le formulaire n'est plus actif, mais existe-t-il des données 
        var dataformul1=localStorage.getItem('data_formulaires_'+k1);
        if(dataformul1==null){
         // plus de données, on ne le garde pas
        }else{
         // il existe encore des données, on le garde mais on le flag à inactif
         forLoc.active=false;
         nouveaux[k1]=forLoc;
         oneDone=true;
        }
       }
      }
      
      // 2°) nouveau formulaire ? 
      for(var k1 in jsonRet.jsonformul1){
       if(!jsonformul1Local.hasOwnProperty(k1)){
        if(jsonRet.jsonformul1[k1].active==true){
         nouveaux[k1]=jsonRet.jsonformul1[k1];
         oneDone=true;
        }
       }
      }
      
      if(oneDone){
       localStorage.setItem('formulaires1' , JSON.stringify(nouveaux));
      }else{
       localStorage.removeItem('formulaires1');
      }
     }
     
     home();
    }else{
     alert('erreur de connexion');
     demanderConnexion();
    }
   }catch(e){
    console.error(e);
   }
  };
  r.onerror=function(e){
//   console.error('e=',e); // si le serveur est KO, on doit continuer à pouvoir saisir des formulaires
   alert('La connexion au serveur est impossible\n\nRéessayez plus tard.');
   home();
  }
  var data={
   funct          : 'connexion',
   login          : document.getElementById('login').value,
   password       : document.getElementById('password').value,
  }
  r.send('data='+encodeURIComponent(JSON.stringify(data)));
 }
 //=====================================================================================================================
 function demanderConnexion(){
  var t='';
  pageStatus='connexion';
  jsonformul1=localStorage.getItem('formulaires1');
  if(jsonformul1==null){
   t+='<span style="">Vous devez vous connecter pour charger des formulaires</span>'+'';
  }
  t+='<div style="text-align:center;">';
  t+='<div>login</div> <div><input id="login"    type="text"     maxlength="128" size="15"></div>';
  t+='<div>mdp</div>   <div>   <input id="password" type="password" maxlength="128" size="15"></div>';
  t+='<div><button onclick="'+objname+'.connexion()">se connecter</button></div>';
  t+='</div>';
  document.getElementById('content').innerHTML=t;
 }
 //=====================================================================================================================
 function ent1(s){
  var x=s.replace(/</g,'&lt;');
  x=x.replace(/>/g,'&gt;');
  x=x.replace(/"/g,'&quot;');
  return x;
 }
 //=====================================================================================================================
 function handleConnectionChange(event){
  if(event.type == "offline"){
   onLineStatus1=false;
   updateMenu();
   updatePage();
  }else if(event.type == "online"){
   onLineStatus1=true;
   loginName1='';
   verifierConnexion();
  }
 }
 //=====================================================================================================================
 function init0(s){
  window.addEventListener('online', handleConnectionChange);
  window.addEventListener('offline', handleConnectionChange);
  onLineStatus1=navigator.onLine;
  updateMenu();
  verifierPresenceFormulaires();
  verifierConnexion();
 }
 //========================================================================================
 init0();
 return {
   supprimerTout           : function(i){supprimerTout(i);},
   enregistrerLaModif      : function(i,j){enregistrerLaModif(i,j);},
   modifier                : function(i,j){modifier(i,j);},
   suppFormulaire          : function(i){suppFormulaire(i);},
   envoyer                 : function(i){envoyer(i);},
   supprimer               : function(i,j){supprimer(i,j);},
   voir                    : function(i){voir(i);},
   enregistrer             : function(i){enregistrer(i);},
   home                    : function(){home();},
   saisir                  : function(i){saisir(i);},
   chargerMesFormulaires   : function(){chargerMesFormulaires();},
   demanderConnexion       : function(){demanderConnexion();},
   deconnexion             : function(){deconnexion();},
   connexion               : function(){connexion();},
   nop                     : function(){/*nothing to do*/}
 };  
}
// on commence le programme ici ( et il était temps :-)
var myObj=new myObj1('myObj');
//=====================================================================================================================
</script>
<?php
// tests pour la volumétrie.
$toto=array(
// mettre ici les données de volumétrie de la dernière colonne du fichier ods, elle ressemble à :
// array('nom' => 'nom1' , 'prenom' => 'prenom1' , 'age' => 1 , 'id_formulaire' => 1 ),
);
?>
<script>
// pour les tests volumétrie, décommenter les 2 lignes çi dessous.
//var toto=<?php echo json_encode($toto);?>;
//localStorage.setItem('data_formulaires_1',JSON.stringify(toto));
/*
// pour info, en wifi le test a été fait pour 10 000 formulaires et le fichier sauvegardé en tableau php fait 1Mo
// en 3 g il faut environ 12 secondes pour envoyer 1000 formulaires d'environ 65 octets
*/
</script>
</body></html>