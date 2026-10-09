<?php
/** Adminer - Compact database management
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 6.1.1
*/namespace
Adminer;if(isset($_GET["status"]))$_GET["variables"]=$_GET["status"];if(isset($_GET["import"]))$_GET["sql"]=$_GET["import"];const
VERSION="6.1.1";error_reporting(24575);set_error_handler(function($sd,$ud){return!!preg_match('~^Undefined (array key|offset|index)~',$ud);},E_WARNING|E_NOTICE);$Xd=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($Xd||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$W){$Ym=filter_input_array(constant("INPUT$W"),FILTER_UNSAFE_RAW);if($Ym)$$W=$Ym;}}$_COOKIE=array_filter($_COOKIE,'is_scalar');if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection($g=null){return($g?:Db::$instance);}function
adminer(){return
Adminer::$instance;}function
driver(){return
Driver::$instance;}function
connect(){$hc=adminer()->credentials();$H=Driver::connect($hc[0],$hc[1],$hc[2]);return(is_object($H)?$H:null);}function
idf_unescape($t){if(!preg_match('~^[`\'"[]~',$t))return$t;$kg=substr($t,-1);return
str_replace($kg.$kg,$kg,substr($t,1,-1));}function
q($P){return
connection()->quote($P);}function
idx($Ea,$w,$j=null){return($Ea&&array_key_exists($w,$Ea)?$Ea[$w]:$j);}function
number($W){return
preg_replace('~[^0-9]+~','',$W);}function
int_type(){return'(tiny|small|medium|big)?int(eger|\d)?';}function
number_type(){return'(^('.int_type().'|decimal|numeric|number|real|(binary_|half_|scaled_)?float\d?|(binary_)?double( precision)?|(small)?money)$)';}function
text_type(){return'char|text'.(JUSH=="sql"?'|enum|set':'');}function
is_user_type($T){return
in_array($T,idx(driver()->structuredTypes(),'User types',array()));}function
full_type_sql(array$l){$T=$l["type"];return(is_user_type($T)?idf_escape($T).substr($l["full_type"],strlen($T)):$l["full_type"]);}function
is_searchable(array$l,array$W){if(!isset($l["privileges"]["where"]))return
false;if(preg_match('~NULL$~',$W["op"]))return
true;$T=$l["type"];$Ak=$W["val"];$Xa='binary$|bytea|raw|image|bfile|^vector$'.(JUSH=="mssql"?'|^timestamp$':'|^bit').(JUSH=="oracle"?'|^blob|^long|rowid':'');if(preg_match("~$Xa~",$T))return
false;if(preg_match(number_type(),$T)){$Qh='-?\d+(\.\d+)?';return(bool)preg_match('~^'.$Qh.(preg_match('~IN$~',$W["op"])?"( *, *$Qh)*":'').'$~',$Ak);}if(preg_match('~^(small)?date|^timestamp~',$T))return(bool)preg_match('~^\d+-\d+-\d+~',$Ak);if(preg_match('~^time~',$T))return(bool)preg_match('~^\d+:\d+~',$Ak);if(preg_match('~^bool~',$T)||(JUSH=="mssql"&&$T=="bit"))return(bool)preg_match('~^(t|f|true|false|[01])$~i',$Ak);return
true;}function
remove_slashes(array$Y,$Xd=false){$H=array();foreach($Y
as$w=>$W)$H[stripslashes($w)]=(is_array($W)?remove_slashes($W,$Xd):($Xd?$W:stripslashes($W)));return$H;}function
bracket_escape($t,$Qa=false){static$_m=array(':'=>':1',']'=>':2','['=>':3','"'=>':4','='=>':5');return
strtr($t,($Qa?array_flip($_m):$_m));}function
url_escape($P){static$_m=array();if(!$_m){$_m=array(' '=>'+');foreach(str_split("\"'<>#%&+=?".ini_get("arg_separator.input"))as$lb)$_m[$lb]=sprintf('%%%02X',ord($lb));for($r=0;$r<256;$r++){if($r<32||$r>126)$_m[chr($r)]=sprintf('%%%02X',$r);}}return
strtr((string)$P,$_m);}function
min_version($yn,$Fg="",$g=null){$g=connection($g);$Xk=$g->server_info;if($Fg&&preg_match('~([\d.]+)-MariaDB~',$Xk,$_)){$Xk=$_[1];$yn=$Fg;}return$yn&&version_compare($Xk,$yn)>=0;}function
charset(Db$f){return(min_version("5.5.3",0,$f)?"utf8mb4":"utf8");}function
ini_set($qi,$X){return(function_exists('ini_set')?\ini_set($qi,$X):false);}function
ini_bool($Bf){$W=ini_get($Bf);return(preg_match('~^(on|true|yes)$~i',$W)||(int)$W);}function
ini_bytes($Bf){$W=ini_get($Bf);switch(strtolower(substr($W,-1))){case'g':$W=(int)$W*1024;case'm':$W=(int)$W*1024;case'k':$W=(int)$W*1024;}return$W;}function
max_input_vars($I,$Ei){$Jg=(int)ini_get("max_input_vars");return($Jg?(int)floor(($Jg-$Ei)/$I):0);}function
max_input_vars_error(){$Bf="max_input_vars";return
sprintf('Maximum number of allowed fields exceeded. Please increase %s.',"<b>$Bf = ".ini_get($Bf)."</b>");}function
sid(){static$H;if($H===null)$H=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$H;}function
set_password($xn,$M,$U,$D){$_SESSION["pwds"][$xn][$M][$U]=($_COOKIE["adminer_key"]&&is_string($D)?array(encrypt_string($D,$_COOKIE["adminer_key"])):$D);}function
get_password(){$H=get_session("pwds");if(is_array($H))$H=($_COOKIE["adminer_key"]?decrypt_string($H[0],$_COOKIE["adminer_key"]):false);return$H;}function
get_val($F,$l=0,$Ob=null){$Ob=connection($Ob);$G=$Ob->query($F);if(!is_object($G))return
false;$I=$G->fetch_row();return($I?$I[$l]:false);}function
get_vals($F,$d=0){$H=array();$G=connection()->query($F);if(is_object($G)){while($I=$G->fetch_row())$H[]=$I[$d];}return$H;}function
get_key_vals($F,$g=null,$al=true){$g=connection($g);$H=array();$G=$g->query($F);if(is_object($G)){while($I=$G->fetch_row()){if($al)$H[$I[0]]=$I[1];else$H[]=$I[0];}}return$H;}function
get_rows($F,$g=null,$k="<p class='error'>"){$Ob=connection($g);$H=array();$G=$Ob->query($F);if(is_object($G)){while($I=$G->fetch_assoc())$H[]=$I;}elseif(!$G&&!$g&&$k&&(defined('Adminer\PAGE_HEADER')||$k=="-- "))echo$k.adminer()->error()."\n";return$H;}function
unique_array($I,array$v){foreach($v
as$u){if(preg_match("~^(PRIMARY|UNIQUE)$~",$u["type"])&&!$u["partial"]){$H=array();foreach($u["columns"]as$w){if(!isset($I[$w]))continue
2;$H[$w]=$I[$w];}return$H;}}}function
where_function($ve,$d,array$l){if($ve=="md5")return
driver()->md5($d,$l)?:$d;return(in_array($ve,driver()->functions)||in_array($ve,driver()->grouping)?apply_sql_function($ve,$d):$d);}function
where(array$Z,array$m=array()){$H=array();foreach((array)$Z["where"]as$w=>$W){$w=bracket_escape($w,true);$d=idf_escape($w);$l=idx($m,$w,array());$Rd=$l["type"];$Of=$l&&(is_blob($l)||preg_match('~binary~',$Rd));$H[]=$d.($Of&&!is_utf8($W)?" = ".driver()->quoteBinary($W):(JUSH=="sql"&&$Rd=="json"?" = CAST(".q($W)." AS JSON)":(JUSH=="pgsql"&&preg_match('~^jsonb?$~',$l["full_type"])?"::jsonb = ".q($W)."::jsonb":(JUSH=="sql"&&is_numeric($W)&&preg_match('~\.~',$W)?" LIKE ".q($W):(JUSH=="mssql"&&strpos($Rd,"datetime")===false?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$W)):" = ".unconvert_field($l,q($W)))))));if(JUSH=="sql"&&preg_match('~char|text~',$Rd)&&preg_match("~[^ -@]~",$W))$H[]="$d = ".q($W)." COLLATE ".charset(connection())."_bin";}foreach((array)$Z["null"]as$w)$H[]=idf_escape($w)." IS NULL";foreach((array)$Z["col"]as$r=>$_b){$W=idx($Z["val"],$r);$H[]=where_function(idx($Z["fun"],$r),idf_escape($_b),idx($m,$_b,array())).($W!==null?" = ".q($W):" IS NULL");}return
implode(" AND ",$H);}function
where_columns(array$m){$H=array();foreach((array)$_GET["null"]as$w)$H[$w]=true;foreach(array_keys((array)$_GET["where"])as$w)$H[bracket_escape($w,true)]=true;foreach((array)$_GET["col"]as$_b)$H[$_b]=true;return
array_intersect_key($H,$m);}function
where_check($W,array$m=array()){parse_str($W,$ob);remove_slashes(array(&$ob));return
where($ob,$m);}function
where_link($r,$d,$X,$ni="="){$ki=($X!==null?$ni:"IS NULL");return"&where[$r][col]=".url_escape($d).($ki!=first(adminer()->operators())?"&where[$r][op]=".url_escape($ki):"")."&where[$r][val]=".url_escape($X);}function
convert_fields(array$e,array$m,array$L=array()){$H="";foreach($e
as$w=>$W){if($L&&!in_array(idf_escape($w),$L))continue;$Fa=convert_field($m[$w]);if($Fa)$H
.=", $Fa AS ".idf_escape($w);}return$H;}function
cookie_path(){return
strtr(preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),array(";"=>"%3B",","=>"%2C"));}function
cookie($A,$X,$ug=2592000){header("Set-Cookie: $A=".rawurlencode($X).($ug?"; expires=".gmdate("D, d M Y H:i:s",time()+$ug)." GMT":"")."; path=".cookie_path().(HTTPS?"; secure":"").($A=="adminer_import"?"":"; HttpOnly")."; SameSite=lax",false);}function
get_url($hn,$Xb){$http_response_header=null;$td=array();set_error_handler(function($sd,$k)use(&$td){$td[]=preg_replace('~^file_get_contents\([^)]*\):\s*~','',$k);return
true;});$H=file_get_contents($hn,false,$Xb);restore_error_handler();$Se=(function_exists('http_get_last_response_headers')?http_get_last_response_headers():$http_response_header);return
array($H,(preg_match('~^HTTP/[\d.]+ (\d+)~',idx($Se,0,''),$_)?$_[1]:''),(array)$Se,($H===false?implode("\n",$td):''),);}function
json_decode_exact($Wf){$Wf=preg_replace('~"(\\\\u0001(?:[^"\\\\]|\\\\.)*+")|"(?:[^"\\\\]|\\\\.)*+"(*SKIP)(*FAIL)~','"\\\\u0001$1',$Wf);return
json_decode(preg_replace('~"(?:[^"\\\\]|\\\\.)*+"(*SKIP)(*FAIL)|-?\d[-+.\deE]*+~','"\\\\u0001$0"',$Wf));}function
json_scalar($W){return(is_string($W)&&substr($W,0,1)=="\1"?substr($W,1):$W);}function
json_encode_exact($W,$de=0){return
preg_replace('~"\\\\u0001(-?\d[^"\\\\]*)"|(")\\\\u0001(\\\\u0001(?:[^"\\\\]|\\\\.)*+")|"(?:[^"\\\\]|\\\\.)*+"(*SKIP)(*FAIL)~','$1$2$3',json_encode($W,$de));}function
get_settings($bc){parse_str($_COOKIE[$bc],$bl);return$bl;}function
get_setting($w,$bc="adminer_settings",$j=null){return
idx(get_settings($bc),$w,$j);}function
save_settings(array$bl,$bc="adminer_settings"){$X=http_build_query($bl+get_settings($bc));cookie($bc,$X);$_COOKIE[$bc]=$X;}function
restart_session(){if(!ini_bool("session.use_cookies")&&(!function_exists('session_status')||session_status()==PHP_SESSION_NONE))session_start();}function
stop_session($ge=false){$kn=ini_bool("session.use_cookies");if(!$kn||$ge){session_write_close();if($kn&&ini_set("session.use_cookies",'0')===false)session_start();}}function&get_session($w){return$_SESSION[$w][DRIVER][SERVER][$_GET["username"]];}function
set_session($w,$W){$_SESSION[$w][DRIVER][SERVER][$_GET["username"]]=$W;}function
auth_url($xn,$M,$U,$i=null){$gn=remove_from_uri(implode("|",array_keys(SqlDriver::$drivers))."|username|ext|".($i!==null?"db|":"").($xn=='mssql'||$xn=='pgsql'?"":"ns|").session_name());preg_match('~([^?]*)\??(.*)~',$gn,$_);return"$_[1]?".(sid()?SID."&":"").($_GET["ext"]?"ext=".url_escape($_GET["ext"])."&":"").($xn!="server"||$M!=""?url_escape($xn)."=".url_escape($M)."&":"")."username=".url_escape($U).($i!=""?"&db=".url_escape($i):"").($_[2]?"&$_[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($Bg,$Zg=null){if($Zg!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($Bg!==null?$Bg:$_SERVER["REQUEST_URI"]))][]=$Zg;}if($Bg!==null){if($Bg=="")$Bg=".";header("Location: $Bg");exit;}}function
query_redirect($F,$Bg,$Zg,$Pj=true,$Ad=true,$Ld=false,$nm=""){if($Ad){$xl=microtime(true);$Ld=!connection()->query($F);$nm=format_time($xl);}$ql=($F?adminer()->messageQuery($F,$nm,$Ld):"");if($Ld){adminer()->error
.=adminer()->error().$ql.script("messagesPrint();")."<br>";return
false;}if($Pj)redirect($Bg,$Zg.$ql);return
true;}class
Queries{static$queries=array();static$start=0;}function
remember_query($F){if(!Queries::$start)Queries::$start=microtime(true);Queries::$queries[]=(driver()->delimiter!=';'?$F:(preg_match('~;$~',$F)?"DELIMITER ;;\n$F;\nDELIMITER ":$F).";");}function
queries($F){remember_query($F);return
connection()->query($F);}function
apply_queries($F,array$S,$vd='Adminer\table'){foreach($S
as$Q){if(!queries("$F ".$vd($Q)))return
false;}return
true;}function
queries_redirect($Bg,$Zg,$Pj){$Jj=implode("\n",Queries::$queries);$nm=format_time(Queries::$start);return
query_redirect($Jj,$Bg,$Zg,$Pj,false,!$Pj,$nm);}function
format_time($xl){return
sprintf('%.3f s',max(0,microtime(true)-$xl));}function
relative_uri($gn=''){return
preg_replace_callback('~^[^?]*~',function($_){return
str_replace(":","%3A",$_[0]);},preg_replace('~^[^?]*/([^?]*)~','\1',($gn?:$_SERVER["REQUEST_URI"])));}function
remove_from_uri($Li=""){return
substr(preg_replace("~(?<=[?&])($Li".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
get_files($A,$wc=false){$Td=$_FILES[$A];if(!$Td)return
null;foreach($Td
as$w=>$W)$Td[$w]=(array)$W;$H=array();foreach($Td["error"]as$w=>$k){if($k)return$k;$n=$Td["name"][$w];$vm=$Td["tmp_name"][$w];$Vb=file_get_contents($wc&&preg_match('~\.gz$~',$n)?"compress.zlib://$vm":$vm);if($wc){$xl=substr($Vb,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$xl))$Vb=iconv("utf-16","utf-8",$Vb);elseif($xl=="\xEF\xBB\xBF")$Vb=substr($Vb,3);}$H[]=array($n,$Vb);}return$H;}function
get_file($w,$wc=false,$Cc=""){$Wd=get_files($w,$wc);if(!is_array($Wd))return$Wd;$H='';foreach($Wd
as$Td){$Vb=$Td[1];$H
.=$Vb;if($Cc)$H
.=(preg_match("($Cc\\s*\$)",$Vb)?"":$Cc)."\n\n";}return$H;}function
upload_error($k){$Rg=($k==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($k?'Unable to upload a file.'.($Rg?" ".sprintf('Maximum allowed file size is %sB.',$Rg):""):'File does not exist.');}function
is_utf8($W){return(preg_match('~~u',$W)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$W));}function
utf8_length($W){return
strlen(preg_replace('~[\x80-\xBF]~','',$W));}function
format_number($W){preg_match('~^#+([^#0]+)(?:(#+)\1)?(#*0)$~u','#,##0',$_);$fl=strlen($_[3]);$H=number_format($W,0,".","");$H=preg_replace('~\B(?=(\d{'.(strlen($_[2])?:$fl).'})*\d{'.$fl.'}$)~',$_[1],$H);return
strtr($H,preg_split('~~u','0123456789',-1,PREG_SPLIT_NO_EMPTY));}function
format_status(array$R,$w){$W=idx($R,$w,'?');if(!is_numeric($W))return
h($W);if($W<0)return'?';$Aa=($w=="Rows"&&(JUSH=="sqlite"||$R["Engine"]==(JUSH=="pgsql"?"table":"InnoDB")));return($Aa?"~ ":"").format_number($W);}function
friendly_url($W){return
preg_replace('~\W~i','-',$W);}function
table_status1($Q,$Md=false){$H=table_status($Q,$Md);return($H?reset($H):array("Name"=>$Q));}function
column_foreign_keys($Q){$H=array();foreach(adminer()->foreignKeys($Q)as$o){foreach($o["source"]as$W)$H[$W][]=$o;}return$H;}function
fields_from_edit(){$H=array();foreach((array)$_POST["field_keys"]as$w=>$W){if($W!=""){$W=bracket_escape($W);$_POST["function"][$W]=$_POST["field_funs"][$w];$_POST["fields"][$W]=$_POST["field_vals"][$w];}}foreach((array)$_POST["fields"]as$w=>$W){$A=bracket_escape($w,true);$H[$A]=array("field"=>$A,"full_type"=>"","type"=>"","privileges"=>array("insert"=>1,"update"=>1,"where"=>1,"order"=>1),"null"=>true,"auto_increment"=>($A==driver()->primary),);}return$H;}function
dump_headers($gf,$rh=false){$H=adminer()->dumpHeaders($gf,$rh);$Gi=$_POST["output"];if($Gi!="text"||$H=="tar"){$Kb=($Gi!="text"&&$Gi!="file"&&preg_match('~^[0-9a-z]+$~',$Gi)?".$Gi":"");header("Content-Disposition: attachment; filename=".adminer()->dumpFilename($gf).".$H$Kb");}session_write_close();if(!ob_get_level())ob_start(null,4096);ob_flush();flush();return$H;}function
dump_csv(array$I){$Lm=$_POST["format"]=="tsv";foreach($I
as$w=>$W){if(preg_match('~["\n]|^0[^.]|\.\d*0$|'.($Lm?'\t':'[,;]|^$').'~',$W))$I[$w]='"'.str_replace('"','""',$W).'"';}echo
implode(($_POST["format"]=="csv"?",":($Lm?"\t":";")),$I)."\r\n";}function
parse_csv($kc,$Lk){$H=array();preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$kc,$Hg);foreach($Hg[0]as$I){preg_match_all("~((?>\"[^\"]*\")+|[^$Lk]*)$Lk~",$I.$Lk,$Ig);$H[]=$Ig[1];}return$H;}function
csv_value($W){return(preg_match('~^".*"$~s',$W)?str_replace('""','"',substr($W,1,-1)):$W);}function
apply_sql_function($p,$d){return($p?($p=="unixepoch"?"DATETIME($d, '$p')":($p=="count distinct"?"COUNT(DISTINCT ":strtoupper("$p("))."$d)"):$d);}function
get_temp_dir(){return
ini_get("upload_tmp_dir")?:sys_get_temp_dir();}function
file_open_lock($n){if(is_link($n))return;$ne=@fopen($n,"c+");if(!$ne)return;@chmod($n,0660);if(!flock($ne,LOCK_EX)){fclose($ne);return;}return$ne;}function
file_write_unlock($ne,$oc){rewind($ne);fwrite($ne,$oc);ftruncate($ne,strlen($oc));file_unlock($ne);}function
file_unlock($ne){flock($ne,LOCK_UN);fclose($ne);}function
first(array$Ea){return
reset($Ea);}function
password_file($ec){$n=get_temp_dir()."/adminer.key";if(!$ec&&!file_exists($n))return'';$ne=file_open_lock($n);if(!$ne)return'';$H=stream_get_contents($ne);if(!$H){$H=rand_string();file_write_unlock($ne,$H);}else
file_unlock($ne);return$H;}function
rand_string(){return(function_exists('random_bytes')?bin2hex(random_bytes(16)):md5(uniqid(strval(mt_rand()),true)));}function
select_value($W,$z,array$l,$lm,array$fj=array()){if(is_array($W)){$H="";if(array_filter($W,'is_array')==array_values($W)){$bg=array();foreach($W
as$V)$bg+=array_fill_keys(array_keys($V),null);foreach(array_keys($bg)as$Yf)$H
.="<th>".h($Yf);foreach($W
as$V){$H
.="<tr>";foreach(array_merge($bg,$V)as$rn)$H
.="<td>".select_value($rn,$z,$l,$lm,$fj);}}else{foreach($W
as$Yf=>$V)$H
.="<tr>".($W!=array_values($W)?"<th>".h($Yf):"")."<td>".select_value($V,$z,$l,$lm,$fj);}return"<table>$H</table>";}if(!$z)$z=adminer()->selectLink($W,$l);if($z===null){if(is_mail($W))$z="mailto:$W";if(is_url($W))$z=$W;}$W=driver()->value($W,$l);$H=adminer()->editVal($W,$l);if($H!==null){if(!is_utf8($H))$H="\0";elseif($lm!=""&&is_shortable($l))$H=shorten_utf8($H,max(0,+$lm),"",$fj);else$H=highlight_matches($H,$fj);}return
adminer()->selectVal($H,$z,$l,$W);}function
is_blob(array$l){return
preg_match('~blob|bytea|raw|file'.(JUSH=="mssql"?'|binary|image':'').'~',$l["type"])&&!in_array($l["type"],idx(driver()->structuredTypes(),'User types',array()));}function
is_identity_always(array$l){return$l["auto_increment"]&&(JUSH=="mssql"||$l["default"]=="GENERATED ALWAYS AS IDENTITY");}function
is_mail($id){$Ha='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$Uc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$ej="$Ha+(\\.$Ha+)*@($Uc?\\.)+$Uc";return
is_string($id)&&preg_match("(^$ej(,\\s*$ej)*\$)i",$id);}function
is_url($P){$Uc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^((https?):)?//($Uc?\\.)+$Uc(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$P);}function
is_ipv6($na){$q='[\da-f]{1,4}';$Nf='\d{1,3}(\.\d{1,3}){3}';return(bool)preg_match("~^(($q:){7}$q|($q:){6}$Nf|(($q:)*$q)?::(($q:)*($q|$Nf))?)$~iD",$na);}function
is_shortable(array$l){return!preg_match('~'.number_type().'|date|time|year~',$l["type"]);}function
url_host($cf){return(strpos($cf,":")!==false?"[$cf]":$cf);}function
server_parts(array$Yi){return
array("scheme"=>(string)$Yi["scheme"],"host"=>(string)$Yi["host"],"port"=>(string)$Yi["port"],"socket"=>(string)$Yi["socket"],"path"=>(string)$Yi["path"],);}function
parse_server($M){if($M=="")return
server_parts(array());if($M[0]==":"&&!is_ipv6($M)){$dk=substr($M,1);if(preg_match('~^\d+$~D',$dk))return
server_parts(array("port"=>$dk));return(preg_match('~^/[-\w.:/]*$~D',$dk)?server_parts(array("socket"=>$dk)):null);}$zk="";if(preg_match('~^([-+.\w]+)://~',$M,$_)){$zk=strtolower($_[1]);$M=substr($M,strlen($_[0]));}if(preg_match('~^\[(.+)](:(\d+))?(/[-\w./]*)?$~D',$M,$_))return(is_ipv6($_[1])?server_parts(array("scheme"=>$zk,"host"=>$_[1],"port"=>$_[3],"path"=>$_[4])):null);if(is_ipv6($M))return
server_parts(array("scheme"=>$zk,"host"=>$M));if(preg_match('~^(/[-\w./]*)(:(\d+))?$~D',$M,$_))return
server_parts(array("scheme"=>$zk,"host"=>$_[1],"port"=>$_[3]));return(preg_match('~^([-\w.]*)(:(\d+))?(/[-\w./]*)?$~D',$M,$_)?server_parts(array("scheme"=>$zk,"host"=>$_[1],"port"=>$_[3],"path"=>$_[4])):null);}function
count_rows($Q,array$Z,$Pf,array$q){$F=" FROM ".table($Q).($Z?" WHERE ".implode(" AND ",$Z):"");return($Pf&&(JUSH=="sql"||count($q)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$q).")$F":"SELECT COUNT(*)".($Pf?" FROM (SELECT 1$F GROUP BY ".implode(", ",$q).") x":$F));}function
slow_query($F){$i=adminer()->database();$om=adminer()->queryTimeout();$hl=driver()->slowQuery($F,$om);$g=null;if(!$hl&&support("kill")){$g=connect();if($g&&($i==""||$g->select_db($i))){$cg=number(get_val(connection_id(),0,$g));echo
script("const timeout = setTimeout(() => { ajax('".js_escape(ME)."script=kill', function () {}, 'kill=$cg&token=".get_token()."'); }, 1000 * $om);");}}ob_flush();flush();$H=@get_key_vals(($hl?:$F),$g,false);if($g){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$H;}function
get_token(){$Mj=rand(1,1e6);return($Mj^$_SESSION["token"]).":$Mj";}function
verify_token(){list($wm,$Mj)=explode(":",$_POST["token"]);return($Mj^$_SESSION["token"])==$wm&&in_array($_SERVER["HTTP_SEC_FETCH_SITE"],array("","same-origin"));}function
compress_alphabet(){return
strtr(implode(range('"','~')),"'\\","!\n");}function
decompress_string($P,$Ic=""){$ya=array_flip(str_split(compress_alphabet()));$x=strlen($P);$un=($x?13*($x-1)/2-$ya[$P[0]]:0);$Xa="";$dk=0;$ek=0;for($r=1;$r<$x;$r+=2){$dk=($dk<<13)+$ya[$P[$r]]*93+$ya[$P[$r+1]];$ek+=13;while($ek>=8&&$un>=8){$ek-=8;$un-=8;$Xa
.=chr($dk>>$ek);$dk&=(1<<$ek)-1;}}if($Xa=="")return"";if($Ic!=""&&function_exists('inflate_init'))return
inflate_add(inflate_init(ZLIB_ENCODING_RAW,array('dictionary'=>$Ic)),$Xa,ZLIB_FINISH);return($Ic==""&&function_exists('gzinflate')?gzinflate($Xa):inflate($Xa,$Ic));}function
inflate($Xa,$Ic=""){$rg=array(3,4,5,6,7,8,9,10,11,13,15,17,19,23,27,31,35,43,51,59,67,83,99,115,131,163,195,227,258);$sg=array(0,0,0,0,0,0,0,0,1,1,1,1,2,2,2,2,3,3,3,3,4,4,4,4,5,5,5,5,0);$Mc=array(1,2,3,4,5,7,9,13,17,25,33,49,65,97,129,193,257,385,513,769,1025,1537,2049,3073,4097,6145,8193,12289,16385,24577);$Oc=array(0,0,0,0,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12,13,13);$H=$Ic;$E=0;do{$Yd=inflate_bits($Xa,$E,1);$T=inflate_bits($Xa,$E,2);if(!$T){$E=($E+7)&~7;$x=inflate_bits($Xa,$E,16);$E+=16;$H
.=substr($Xa,$E>>3,$x);$E+=$x<<3;}else{if($T==1){$_g=array_merge(array_fill(0,144,8),array_fill(0,112,9),array_fill(0,24,7),array_fill(0,8,8));$Pc=array_fill(0,30,5);}else{$zg=inflate_bits($Xa,$E,5)+257;$Nc=inflate_bits($Xa,$E,5)+1;$ti=array(16,17,18,0,8,7,9,6,10,5,11,4,12,3,13,2,14,1,15);$fh=array_fill(0,19,0);$eh=inflate_bits($Xa,$E,4)+4;for($r=0;$r<$eh;$r++)$fh[$ti[$r]]=inflate_bits($Xa,$E,3);$gh=inflate_table($fh);$tg=array();while(count($tg)<$zg+$Nc){$Jl=inflate_symbol($Xa,$E,$gh);if($Jl==16)$tg=array_merge($tg,array_fill(0,inflate_bits($Xa,$E,2)+3,end($tg)));elseif($Jl==17)$tg=array_merge($tg,array_fill(0,inflate_bits($Xa,$E,3)+3,0));elseif($Jl==18)$tg=array_merge($tg,array_fill(0,inflate_bits($Xa,$E,7)+11,0));else$tg[]=$Jl;}$_g=array_slice($tg,0,$zg);$Pc=array_slice($tg,$zg);}$Ag=inflate_table($_g);$Rc=inflate_table($Pc);while(($Jl=inflate_symbol($Xa,$E,$Ag))!=256){if($Jl<256)$H
.=chr($Jl);else{$x=$rg[$Jl-257]+inflate_bits($Xa,$E,$sg[$Jl-257]);$Qc=inflate_symbol($Xa,$E,$Rc);$Yh=strlen($H)-$Mc[$Qc]-inflate_bits($Xa,$E,$Oc[$Qc]);for($r=0;$r<$x;$r++)$H
.=$H[$Yh+$r];}}}}while(!$Yd);return($Ic==""?$H:substr($H,strlen($Ic)));}function
inflate_bits($Xa,&$E,$dc){$H=0;for($r=0;$r<$dc;$r++){$H+=((ord($Xa[$E>>3])>>($E&7))&1)<<$r;$E++;}return$H;}function
inflate_table(array$tg){$Q=array();$zb=0;for($Ya=1;$Ya<=max($tg);$Ya++){foreach($tg
as$Jl=>$x){if($x==$Ya){$Q[$Ya][$zb]=$Jl;$zb++;}}$zb<<=1;}return$Q;}function
inflate_symbol($Xa,&$E,array$Q){$zb=0;$Ya=0;do{$zb=($zb<<1)+inflate_bits($Xa,$E,1);$Ya++;}while(!isset($Q[$Ya][$zb]));return$Q[$Ya][$zb];}function
script($ml,$zm="\n"){return"<script".nonce().">$ml</script>$zm";}function
script_src($hn,$zc=false){return"<script src='".h($hn)."'".nonce().($zc?" defer":"")."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
on($wd,$Je,$Ba=null){$Da=array();foreach(array_slice(func_get_args(),2)as$W)$Da[]=json_encode($W,256);return" data-on$wd='".str_replace(array('&','<',"'"),array('&amp;','&lt;','&#039;'),"$Je(".implode(", ",$Da).")")."'";}function
input_hidden($A,$X=""){return"<input type='hidden' name='".h($A)."' value='".h($X)."'>\n";}function
input_token(){return
input_hidden("token",get_token());}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($P){return
str_replace(array('&','<','"',"'","\0"),array('&amp;','&lt;','&quot;','&#039;','&#0;'),$P);}function
nl_br($P){return
str_replace("\n","<br>",$P);}function
checkbox($A,$X,$rb,$gg="",$c="",$xb="",$ig=""){$H="<input type='checkbox' name='$A' value='".h($X)."'".($rb?" checked":"").($gg==""&&$xb?" class='$xb'":"").($ig?" aria-labelledby='$ig'":"").$c.">";return($gg!=""?"<label".($xb?" class='$xb'":"").">$H".h($gg)."</label>":$H);}function
optionlist($B,$Hk=null,$ln=false){$H="";foreach($B
as$Yf=>$V){$si=array($Yf=>$V);if(is_array($V)){$H
.='<optgroup label="'.h($Yf).'">';$si=$V;}foreach($si
as$w=>$W)$H
.='<option'.($ln||is_string($w)?' value="'.h($w).'"':'').($Hk!==null&&($ln||is_string($w)?(string)$w:$W)===$Hk?' selected':'').'>'.h($W);if(is_array($V))$H
.='</optgroup>';}return$H;}function
group_system(array$zh,$yk=false){$H=array();$Kl=array();foreach($zh
as$A){if($yk?driver()->isSystem(DB,$A):driver()->isSystem($A))$Kl[]=$A;else$H[]=$A;}if($Kl)$H[sprintf('System%s','')]=$Kl;return$H;}function
html_select($A,array$B,$X="",$c="",$ig=""){static$gg=0;$hg="";if(!$ig&&substr($B[""],0,1)=="("){$gg++;$ig="label-$gg";$hg="<option value='' id='$ig'>".h($B[""]);unset($B[""]);}return"<select name='".h($A)."'".($ig?" aria-labelledby='$ig'":"")."$c>".$hg.optionlist($B,$X)."</select>";}function
html_radios($A,array$B,$X="",$Lk=""){$H="";foreach($B
as$w=>$W)$H
.="<label><input type='radio' name='".h($A)."' value='".h($w)."'".($w==$X?" checked":"").">".h($W)."</label>$Lk";return$H;}function
confirm($Zg=""){return
on('click','confirmClick',$Zg?:'Are you sure?');}function
print_fieldset($s,$qg,$An=false){echo"<fieldset><legend>","<a href='#fieldset-$s' class='toggle'>$qg</a>","</legend>","<div id='fieldset-$s'".($An?"":" class='hidden'").">\n";}function
bold($Za,$xb=""){return($Za?" class='active $xb'":($xb?" class='$xb'":""));}function
js_escape($P){return
str_replace("<","\\x3C",addcslashes($P,"\r\n'\\"));}function
js_escape_re($P){return
addcslashes(preg_quote($P,"/"),"\r\n");}function
pagination_href($C){return
remove_from_uri("page|next").($C?"&page=$C".($_GET["next"]!=""?"&next=".url_escape($_GET["next"]):""):"");}function
pagination($C,$lc){return" ".($C==$lc?($C?"<b>".($C+1)."</b>":$C+1):'<a href="'.h(pagination_href($C)).'">'.($C+1)."</a>");}function
hidden_fields(array$Fj,array$lf=array(),$vj=''){$H=false;foreach($Fj
as$w=>$W){if(!in_array($w,$lf)){if(is_array($W))hidden_fields($W,array(),$w);else{$H=true;echo
input_hidden(($vj?$vj."[$w]":$w),$W);}}}return$H;}function
hidden_fields_get(){echo(sid()?input_hidden(session_name(),session_id()):''),($_GET["ext"]?input_hidden("ext",$_GET["ext"]):""),(isset($_GET[DRIVER])?input_hidden(DRIVER,SERVER):""),input_hidden("username",$_GET["username"]);}function
on_upload_progress(&$fn){$fn=(ini_bool("session.upload_progress.enabled")&&ini_get("session.upload_progress.name")?rand_string():"");return($fn?on('submit','uploadProgress',ME."upload=$fn",SESSION_NAME."=$fn"):"");}function
file_input($c,$dk=""){$Lg="max_file_uploads";$Mg=ini_get($Lg);$Rg="upload_max_filesize";$Sg=ini_bytes($Rg);$rj=ini_bytes("post_max_size");if($rj&&$rj<$Sg){$Rg="post_max_size";$Sg=$rj;}$Tg=ini_get($Rg);return(ini_bool("file_uploads")?"<input type='file'$c".on('change','fileChange',(int)$Mg,sprintf('Increase %s.',"$Lg = $Mg"),$Sg,sprintf('Increase %s.',"$Rg = $Tg")).">$dk":'File uploads are disabled.');}function
enum_input($T,$c,array$l,$X,$ld=""){preg_match_all("~".driver()->enumLength."~",$l["length"],$Hg);$vj=($l["type"]=="enum"?"val-":"");$rb=(is_array($X)?in_array("null",$X):$X===null);$H=($l["null"]&&$vj?"<label><input type='$T'$c value='null'".($rb?" checked":"")."><i>$ld</i></label>":"");foreach($Hg[0]as$W){$W=stripcslashes(idf_unescape($W));$rb=(is_array($X)?in_array($vj.$W,$X):$X===$W);$H
.=" <label><input type='$T'$c value='".h($vj.$W)."'".($rb?' checked':'').'>'.h(adminer()->editVal($W,$l)).'</label>';}return$H;}function
input(array$l,$X,$p,$Oa=false,$cn=false){$A=h(bracket_escape($l["field"]));echo"<td class='function'>";$rd=driver()->enumLength($l);if($rd){$l["type"]="enum";$l["length"]=$rd;}$B=($l["type"]=="enum"||$l["type"]=="set");if(is_array($X)&&!$p&&!$B)$p="json";$Wf=($p=="json"||preg_match('~^jsonb?$~',$l["full_type"]));if($Wf&&$X!=''&&(JUSH!="pgsql"||$l["type"]!="json")&&(is_array($X)||!$_POST["save"]))$X=(is_array($X)?json_encode($X,128|64|256):json_encode_exact(json_decode_exact($X),128|64|256));$ck=($cn&&is_identity_always($l));if($ck&&!$_POST["save"])$p=null;$we=(isset($_GET["select"])||$ck?array("orig"=>'original'):array())+adminer()->editFunctions($l);$c=" name='fields[$A]".($B?"[]":"")."'".($Oa?" autofocus":"");echo
driver()->unconvertFunction($l)." ";$Q=$_GET["edit"]?:$_GET["select"];if($l["type"]=="enum")echo
h($we[""])."<td>".adminer()->editInput($Q,$l,$c,$X);else{$Le=(in_array($p,$we)||isset($we[$p]));$Zd=0;foreach($we
as$w=>$W){if($w===""||!$W)break;$Zd++;}echo(count($we)>1?"<select name='function[$A]'".on('change','functionChange').on_help_value('^SQL$').">".optionlist($we,$p===null||$Le?$p:"")."</select>":h(reset($we)))."<td".($Zd&&count($we)>1?on('input','skipOriginal',$Zd):"").">";$Df=adminer()->editInput($Q,$l,$c,$X);if($Df!="")echo$Df;elseif(preg_match('~bool~',$l["type"]))echo"<input type='hidden'$c value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$X)?" checked":"")."$c value='1'>";elseif($l["type"]=="set")echo
enum_input("checkbox",$c,$l,(is_string($X)?explode(",",$X):$X));elseif(is_blob($l)&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$A'>";elseif($Wf)echo"<textarea$c cols='50' rows='12' class='jush-json'>".h($X).'</textarea>';elseif(($km=preg_match('~text|lob|memo~i',$l["type"]))||preg_match("~\n~",$X)){if($km&&JUSH!="sqlite")$c
.=" cols='50' rows='12'";else{$J=min(12,substr_count($X,"\n")+1);$c
.=" cols='30' rows='$J'";}echo"<textarea$c>".h($X).'</textarea>';}else{$Qm=driver()->types();$Om=$Qm[$l["type"]];$Ea=preg_match('~\[]~',$l["full_type"]);if($Ea)$Ug=0;elseif(preg_match('~date|time|year~',$l["type"])){$uj=($l["length"]==""&&JUSH=="pgsql"?6:$l["length"]);$oe=(preg_match('~time~',$l["type"])&&preg_match('~^[1-9]\d*$~',$uj)?$uj+1:0);$Ug=($Om?$Om+$oe:0);}elseif(!preg_match('~int|vector~',$l["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$l["length"],$_))$Ug=(preg_match("~binary~",$l["type"])?2:1)*$_[1]+($_[3]?1:0)+($_[2]&&!$l["unsigned"]?1:0);else$Ug=($Om?$Om+($l["unsigned"]?0:1):0);echo"<input".((!$Le||$p==="")&&preg_match('~^'.int_type().'$~',$l["type"])&&!$Ea?" type='number'":"")." value='".h($X)."'".($Ug?" data-maxlength='$Ug'":"").(preg_match('~char|binary~',$l["type"])&&$Ug>20?" size='".($Ug>99?60:40)."'":"")."$c>";}echo
adminer()->editHint($Q,$l,$X),(count($we)>1?script("fire(qs('select', qsl('td').previousSibling), 'change');",""):"");}}function
process_input(array$l){$t=bracket_escape($l["field"]);$p=idx($_POST["function"],$t);if($p=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$l["on_update"])?idf_escape($l["field"]):false);if($p=="NULL")return"NULL";if(is_blob($l)&&ini_bool("file_uploads")){$Td=get_file("fields-$t");if(!is_string($Td))return
false;return
driver()->quoteBinary($Td);}$X=idx($_POST["fields"],$t);if($X===null)return
false;if($l["type"]=="enum"||driver()->enumLength($l)){$X=idx($X,0);if($X=="orig"||!$X)return
false;if($X=="null")return"NULL";$X=substr($X,4);}if($l["auto_increment"]&&$X=="")return
null;if($l["type"]=="set")$X=implode(",",(array)$X);if($p=="json"){$X=json_decode($X,true);if(!is_array($X))return
false;return$X;}return
adminer()->processInput($l,$X,$p);}function
search_tables(){$_GET["where"][0]["val"]=$_POST["query"];$Kk="<ul>\n";foreach(table_status('',true)as$Q=>$R){$A=adminer()->tableName($R);if(isset($R["Engine"])&&$A!=""&&(!$_POST["tables"]||in_array($Q,$_POST["tables"]))){$G=connection()->query("SELECT".limit("1 FROM ".table($Q)," WHERE ".implode(" AND ",adminer()->selectSearchProcess(fields($Q),array(),$R)),1));if(!$G||$G->fetch_row()){$Bj="<a href='".h(ME."select=".url_escape($Q)."&where[0][op]=".url_escape($_GET["where"][0]["op"])."&where[0][val]=".url_escape($_GET["where"][0]["val"]))."'>$A</a>";echo"$Kk<li>".($G?$Bj:"<p class='error'>$Bj: ".adminer()->error())."\n";$Kk="";}}}echo($Kk?"<p class='message'>".'No tables.':"</ul>")."\n";}function
on_help($km,$el=0){return
on('mouseover','helpMouseover',$km,$el).on('mouseout','helpMouseout');}function
on_help_value($Wj="",$bk=""){return
on('mouseover','helpValueMouseover',$Wj,$bk).on('mouseout','helpMouseout');}function
edit_form($Q,array$m,$I,$cn,$k='',$F='',$nm=''){$Sl=adminer()->tableName(table_status1($Q,true));page_header(($cn?'Edit':'Insert'),$k,array("select"=>array($Q,$Sl)),$Sl);adminer()->editRowPrint($Q,$m,$I,$cn,$F,$nm);if($I===false){echo"<p class='error'>".'No rows.'."\n";return;}echo"<form action='' method='post' enctype='multipart/form-data' id='form'>\n";$gd=false;$Hn=($cn&&!isset($_GET["select"])?where_columns($m):array());$Yb=(count($Hn)!=count($m));if(!$Yb)$Hn=array();if(!$m)echo"<p class='error'>".'You have no privileges to update this table.'."\n";else{echo"<table class='layout nowrap'".on('keydown','editingKeydown').">\n";$Oa=!$_POST;foreach($m
as$A=>$l){echo"<tr".($Hn[$A]?on('change','whereChange'):"")."><th>".adminer()->fieldName($l);$j=idx($_GET["set"],bracket_escape($A));if($j===null){$j=$l["default"];if($l["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$j,$Yj))$j=$Yj[1];if(JUSH=="sql"&&preg_match('~binary~',$l["type"]))$j=bin2hex($j);}$X=($I!==null?($l["type"]=="set"&&is_array($I[$A])?implode(",",$I[$A]):(is_bool($I[$A])?+$I[$A]:$I[$A])):(!$cn&&$l["auto_increment"]?"":(isset($_GET["select"])?false:$j)));if(!$_POST["save"]&&is_string($X))$X=adminer()->editVal($X,$l);if(($cn&&!isset($l["privileges"]["update"]))||$l["generated"])echo"<td class='function'><td>".select_value($X,'',$l,null);else{$gd=true;$p=($_POST["save"]?idx($_POST["function"],bracket_escape($A),""):($cn&&preg_match('~^CURRENT_TIMESTAMP~i',$l["on_update"])?"now":($X===false?null:($X!==null?'':'NULL'))));if(!$_POST&&!$cn&&$X==$l["default"]&&preg_match('~^[\w.]+\(~',$X))$p="SQL";if(preg_match("~time~",$l["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$X)){$X="";$p="now";}if($l["type"]=="uuid"&&$X=="uuid()"){$X="";$p="uuid";}if($Oa!==false)$Oa=($l["auto_increment"]||$p=="now"||$p=="uuid"?null:true);input($l,$X,$p,$Oa,$cn);if($Oa)$Oa=false;}}if(!fields($Q)&&driver()->primary!="")echo"<tr>"."<th><input name='field_keys[]'".on('input','fieldChange').">"."<td class='function'>".html_select("field_funs[]",adminer()->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>";echo"</table>\n";}echo"<p>\n";if($gd){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"])&&$Yb){$Jc=($Hn&&($k!=""||adminer()->error!="")?" disabled":"");echo"<input type='submit' name='insert' value='".($cn?'Save and continue editing':'Save and insert next')."' title='Ctrl+Shift+Enter'$Jc".($cn?on('click','ajaxForm','Saving…'):"").">\n";}}echo($cn?"<input type='submit' name='delete' value='".'Delete'."'".confirm().">\n":"");if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo
input_hidden("referer",(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"])),input_hidden("save",1),input_token(),"</form>\n";}function
repeat_pattern($ej,$x){return
str_repeat("$ej{0,65535}",$x/65535)."$ej{0,".($x%65535)."}";}function
shorten_utf8($P,$x=80,$Fl="",array$fj=array()){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$x).")($)?)u",$P,$_))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$x).")($)?)",$P,$_);$x=strlen(isset($_[2])?$_[1]:preg_replace('~\n[^\n]*\z~',"\n",$_[1]));return
highlight_matches($P,$fj,$x).$Fl.(isset($_[2])?"":"<i>…</i>");}function
highlight_matches($P,array$fj,$x=null){if($x===null)$x=strlen($P);$H="";$E=0;if($fj&&@preg_match_all("((?|".implode("|",$fj)."))su",$P,$Hg,PREG_OFFSET_CAPTURE)){foreach($Hg[0]as$_){list($km,$xl)=$_;if($km!=""&&$xl<$x){$md=min($xl+strlen($km),$x);$H
.=h(substr($P,$E,$xl-$E))."<mark>".h(substr($P,$xl,$md-$xl))."</mark>";$E=$md;}}}return$H.h(substr($P,$E,$x-$E));}function
icon($ff,$A,$ef,$qm,$c=""){return"<button ".($A?"type='submit' name='$A'":"draggable='true' tabindex='-1'")." title='".h($qm)."' class='icon icon-$ff".($A?"":" jsonly")."'$c><span>$ef</span></button>";}function
copy_icon(){$cc='Copy';return"<a href='' class='jsonly icon-copy' title='$cc'><span>$cc</span></a>";}if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");ini_set("zlib.output_compression",'1');if($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
decompress_string('+c(<]iDp;+<8]XG-X#ETBP{IAOo`HAD0Z,$t2FfTr3g#Vd(TVf>Cx5+d&ycL<,9"B6]b"oPK(+THBssK@e0=xnaBRf;$]A0]jsW_*Ibe$(2;yd5/}P:0_3xBmwvnkq<ydKh3e?rW3UW8c6iorbru~.;FOKSUq)z0u4OH&MRf7i:
N[U_7;F1p9i(5s29CE=`-:Ze`.kn@9RF4QL-+YuW|6!U&>e-zoVW6?4.A8t`/B@/ELQUIrIU%7Kvs3S?k#p2;1H
X/y@s?AR7M+0t;gg~I=j9:,1HW;ic+?$`BB#$=qSuRwY!<DX3Ny>D#NN*xh]"#vZCW/M>Y@mG3*h%?kSmN=OT;f?cTPrvH,vnUVam2HYmY(?/@
0?NF?2Ol^+5I
U%GE]1O0$>ys`u;eY*<H,E)jU7$
/G"I;uWG$9)5gsRW{:{e~U~.8AE.5W!
iWp-~OBKj1jL_EwRB?Y&i9o<;$`HciPwu)}$:S%2WeZN_EB+TMeR~sZ$~6Tlb.PFzj2c5D6$IrS,VAG6-$YN,Jsp.hB-"5(?g%)]_85S;K}Q)!]Ug5W7nWvawCpy>%pke1%;C>,8*br=&cDBB+3bJLku@UA6Pj:[LNyDz#!]HdCU2Vp/Tlj+yr(,J,*Wh<pP&Q5qxHb/^oeg5%v+WD+:w2T0+%DHK8Z6Oq>bbmt"W9"Gv0Dk;uesBj-9`dThaOFtw)#)nPV3&XyFGk01&HN4/Q{y|&Q`8uS0E,^"<CZDxnmK?l<Ff_g^SD_u$Z@<+<tC&b^k)8;L,&z5t.6)K0^"X#G_Rn:/T#T(cmAla^WGNtZS4XlovO
"JS=mYRH05NTD)D$P<[|2YAPQ@>B#ogdeOCH)M?8)+*D+`r2kS@J7:27wU2&LL!0(7rXo)F2]gv#2me/LOGGPS`9P@Wp!c9C)IEitF`CKjug?LA"_w?5f5ERLbV%"Sekh?;mD9kN
Kgs*3=$EYJJoJm;SuFwOv5@2rMMTVhHs
L]vkS}w&YkWCkI/+1n,j:]OE&&-ng%CfmYK`SO(N(v3A:cVgy
Z)dKHyOq`7+i0G]M(r,[9,?ZYq
]/"%_Hv07O6xyQ^+0#-*3$SpP!ci1={S}nLm3E:8k`7A`ng!$yH-j]G/|y}d8Vhy6XZ5FNRiRhJ:f23:43;VJp2REp"2/PU]Gt#F?,).3Oi]/oy2tM5RMl*RzvJsW2/:.fEIoUE.
4l]"?S9(cOr~IfBV51Su7^`]oq&3b5HUl~B4/Sb^C7,cE.H-tQV*^R#rEmw+$qICNudLR<En:sNot2

KY)]Xs7@-;-#x`3qs]OY=[_lc=L3=|G6qr8vNfc@]Vm(F,&^2w_="t$w3x[VZM*ybGUsmQORjL@FP4&?
5C3C%d%,g6fMf@kAN>(<M)9lHg<(aowp0%0gvB
>uekZH[Qhb3m3EZu69I0Z3Sk!}C,Cs`CJNb|ho&F+_Fv1V*9@fd39
&)f27Wk->IL*%gW1AqC,hV&i=^gG(P&
jb8I5PFUY-4Y_YjN@_kBFm$)`,?gA<HJm&6&N`7b69`OSgA1e]O:Jd%MEx>s2|
d%"mb?yp
C1+?Ml&im[ZB*N!c%e=]R
6B%O;a,NRqW|U}tM;5Z6t7o/?c>a9WsG1Tk!?#]gn|vgx{qBT_505u#2(M@LqfO(RlG=I)aR6fMs]
nyL/y6bx-,d|UacKJhi=PhxG2`Qhl5=kK?AT_AUf&(NI!:9i)TXDh`9<_N>d$}d?99epf"Lte7lxQz/KD0&
ZA8jpQrjlcQ`!?dHd(6IFPRu1E.&@RfvL"p*6zsdlUE%#5/[>7_)D3jrqi%&e%U492k}9j(NS]G2KOF?I*ib%:`c)lY)b}l`asY{0YvMx=TkbBq:&ne?mKPtlwO9<i6[J=Jvib,r+Fh4>y%C9%Yf)_/A$Z=)PHmQN[3@8SPov"@_3L7^5<?Dc?#_LK9z`l1?RA7mfD#{AcNbJjBY6}GCA*S
jaGHPp.*:(DO&{E97kVgEB_Mj
eyxuy@#C.PNGx>5*Frh_a_R?jDEH
0SJj74wk2(zJX$+G~tIe<)cUp$,i.@m:h$uqJkHp`rw5Oi^B#;af$OYt3F5ljH4E)n
D.E35kH:ddpC<:o)_ZTHo`JxY;Qo/~4cD]Kka#&Jg*
y8e"5(G+TB3VllVi"P,;M4*yU*:INGrm[E8&;fe79XPN-#HZv5M?*PHeu.j&qH`E)#ewBDs*XVW1sO4U^:"lWmS1I>hX!E/xZ:hSZq%;>_)s>QJx!o{4A%]JSThrG%Aq<$N00!#oQX&8YX@`nJxO-^f-2`Z#9H&@%7^<DA?mq`x3NL0"#LEPiR>]RVtp;MmL/xD4|W(@Q5=oF=5mMmOh/Ed]Sn#e4w!Xa3S0b5Zs2b4[JPU>Y-Q_NdQDebkvBS_`_2,Kmh/dlNO,=7^iJKsDWC+Y}eCrN]v7WqiU|@~@STU"M@~Z[ZQJfm!ZJ=,hW_1eaXa:~*LCm$prSpmT^XnJF)]#[MQtoKXlVgoIg#irnY4IhC=FVmq=3*Yv8]G>Mm=I"2/G")dC3B*e8=5rlIaC%)KY<2qP_N!X`(xkecZi(l?<@TCJAua%LjXkPpuSBqc%1"{/VvHRN]7dh00ywYgc4M/2.9$N-_EFZ=#GUk@,3jn5zb@70neZ0+4dd?x*0kYA~rA!AKSR.)6S~whrt^m2NhrAvS`$=p)^aw:ss1zm[bmvTR?tU->o$OHx",{x
vU@iBfpiWCxF%}yCyf`rpPj5N%[zq+ww-"f!bm,qDw*~l4Q$y|vWbth!KtKd!<p&2<BU,[sQmx7pn}fhC^bKwW4>csrq@JyewRu|l7Ko>ggj>ayQr+wB9$qURwa"&%IN+g5arv+VyAd"lDSyM|Pi5Y*5K<uvJ778nz<2xCBq&]*7p+X);jN9`i
o`,]_p5MuX3RtD*CcKdl&)hNr;rJ(d}KjWtTq]NRh(Xpx$ql^bW(3*]%@M)BntuCI]vqpX|v[,gS`tfFX)bclBkp~:F0/Ja^>h1q+MZ-U_2J4WqqJM;oN-/r/E{Qh9U1c@v[7r.C_i_Tje
0J`0("b_3TT`&!Lb&)u:F|(8mo,ScbAqi}[zj#5U
qY)>5Sz68#-"=vXaWY*E-<xKi+M0fX&e_Fbu
=+7.L*v{tw=j#l^DKec|yzcIm<)55UCRw`=/jRj^H,.qZ;Or,k1@?K$_(+XuOf%3qnQbR!3|l0",RrV!Quq[g.46^QGSk~KSyRE20k2B<-5b/TC4A_H,iUtJuYn%uR,cv9E@z)vGMXa
U7Y2)a(JKS0}aSk]F6_NGG[gkas.8:b|UpDNo7)#1:#^u(_HCcA[=y$0/DoQ*-u-Y(kgPrvr]SA.64*juL2`E:o"&N
&,C8%TeT~=V^yWF8S.B@gi_qm<d1?S%?e_=p!.T[#Bw5ZN+cEZIcA.cq*akE?G
:i+IU0S
U^_[@PL}3e`rc&=dEd1i
]Vb1Iot<#!x+zvS0U:]
Otkx6Tg
{N]Sq-dY*#Tq]m>cOeiMi<NR)uU1n>uWeM*X(]|raF9XW
Jk~L;&knB$^)Y91*G
*QyvX*Fy8cX";/[vR-M$^`qM>FPjg8vVhe8E
<@m+-r<%+Mnz^_hSjahRL@hlq&&P(j:BN~O5?x,=/)siN?uPspLjm?2uWg?;QeUeu;Ribp+C$IU0Uuc/m2BD<~juKOZbKB>_fBjkwO`#:]yJnD>.S0E/^Qhj9Zv<_:WwIvaK=N.)@)bOq;)Q87@L*l%Yk<s^`g1Cat#ti>&u6x&d+11d
/U#+W!`B:ZvZ&fc_/`sZceqo}[)20D-y$oVu9m_uA]j;-U7bB<|?7P|@2nE2@V?K98_5-g+ILk?^8*sC(bm*TB$7WbTag
9Q|r`/x[gbit95y)ed+c[J^bxHu4Bt13vMc@ORH,*amJeT
3qE<,,dHeMwr3;dgO>U(4bF.oRf}L*Ig:K0;UhO]&n>,++r:ue_nFS)#f9u6p-?syidcp)g?JNdnUG>eY+Z;Q
ic*@g5_.(zZ+YP)7WTcSVV64E0hX_i$-S*f>RIkT6ld.Z.><DZX71s.
WoV<&#()`85SxZ)5=HnlbK"*8mBsyhtbT&MSD&=h;.d+S7EUaT<uXX<57JdOk<fN,pSbM2v
)Gt5CBx_X;Xmx[w@arJubk5w^{]:
g*qcDVgqw;JdkVn3F.8$@p,:"FTh[*DX9m$V*:+c!axkZ)oj,UU@4RHdkI_C-nv@Bbb"2Il%Ec`V/]F!T7fe:-a?F&-iS-6v-`v1N"_kxs%h/QW`a,XTZX,EAi}J]q`kTc2LhWS
KS/b&hmAg*8t%vv$ER(=+^RuQ+RY1t{08ll%O;+4{X7>#p:nsa+_mEK"WOgW;oA/x=!OiUz/D[9&Ty.4oid/0)gkT19r~W^qH<o6j<O!J)P3
8@`.:n<:I^>H1d_z]a..A=+~7R`By];s-$4h19^+&_Wk7|H|u@b)l)m`d>1.U~vpYshDNjbBe4QTR`F$k[dSsdQOF5APIhwwcKCo@.rtDG^NYTsUbFiJyvxd1h&s]W+:x^L%tX');}elseif($_GET["file"]=="dark.css"){header("Content-Type: text/css; charset=utf-8");echo
decompress_string('$Osc2b7V>fj0U7tCw8TNfbT`5e<0!4x49EeL1n%e,E<_WZ?>@wWMzpUD:UubAB^u7,;ZJ.!U!ODBJ$p,I?B8.F[0L@ZP|U.08;>OMB~NvqOKctbeXn{?PG0IyC%OS?)r=jqOH2M?hb}k!R/`..+tcti.<c<RYfU_-K2A
vCxFn<6{L^r&8pu@R.[?Y;Q%DJhl3UE#:f_^Gy.{7^;|fNj6ajaX/-y3:kA
^e)ZU{TubDn#S5p^*>QuANxT;&o~2R.180pfCDp{Af#.9i9,-(*Y&"#eJu7R(uH>BnL38{cb_!.Xt`+GTj["w]mNugo|W%7muWs%-r2M[*vyw`Oi6fN"fuoOa;Cx<jivJ:1;k/yuSo;e7`7Ib+sTqL`5Z!<_Msdu^T1o?NxnfVqUcZ(31mO1mW&?l3hyqkSs<KDz)JOYNX.qcbw.a-hF9!u8),&16yAnEfk#`",*pieSR"^
:QnVS6*:q5["XTfNZdqS`4.wq&WqR$ff;}))8p/))?(NoZ>?`%8E(L)Qbr/mvjYq3odVYIZmt];gErx>%}pw8jHUEMo^U6FzrG6!>r%.Fl,i8GQ7.)96y=aANQAV
I&mM@=8CtkDDN#eQ><"PT&0&"
MqJq.V[OT-W:;AFl}kzYka3jtFJR2
a(@dy4=2dZ`=+P>(`R1E_t2_SsXn^QTobmely7V_<d>rQWl@v,kyf0vur:xH._r2kS"fa0O7^l]f4?(
>1U6U+VK-57o7x8oQWpsfb%dt4*EQPSa_B
R5pEdml@-Xb&>I"-^*scQ1=E/ctCOk5vsYn%D90P*?o@rb?
W?IHCMCI&bJI9sK8]T5bt76}rq_*)G[9K2;FAd)taZI^BtuBX+sK60V2H]NeE|*vCruwDpOK^kn6m6f6&1s^Ucs*]9vP^6+%wutWM/66l4Fj)WO0?_+RGg2|jW.*v?W?ZXyua"T+&(XWj;>M!:kHFApzq<`|]GHW(vkK32!q%[A`AHO1`}O*ZI?@w}[)');}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
decompress_string('+]bcjnsZ323o!;f/.e+fx8n;[f71r0pc]giW`oV!D5mSIO;.F)#>?N&/uKRvUtUkWix^XgjX;sS9{(3
eB-
eJD[z_=IA3FpiUH:=MZ3k@qANy
,pQ[T_3Z)Wvk_gT[Jd2khOG[lbtSJi%Xc~x,$3rQ`e7zs`9hczx~5/)NGI&}cu=mR0U@T!doS{^#,i$18TPKy|Ut2;;<LDrZ*rx?t3j]uV0wBhgRMVF+5rB"o&w`p]@K$P3@jPh+.?b}q$QhMV-w*DRoP)N{L2JTta*c.*>bWJuFy>FjBaYa^O=]J40HjoD4_^T?rw%#V3SaEJ,@haT<%p+hZz%2WiK*x/Ujaht!%qA^VCZ%24?IYc7SxrR/#P0:US@|Fn%2c_mX-0TL`9<sF_kip3+A]LVm4i_B)GKtx"@.D*hh<7$~]-CYZwFt@Oa4x]vbQ|`u+7g+B6IsXQ3H9^K]$9EnvPx3cO;lKlZbjaFHFIy,ncrDLW[Tn<w@a^MaXrb^LUyM7x^;r12)nqu8iDuIM(&I)|_X"Ta}5J+vE8Uf0-m`=4yNV{@ctgiirE-<
?TDx$]G!:iP](g0Z*9Tox-iN*VRy5Z>W|@*CdPh,qgPeFeMj{gObW[)Bvq#<xTo_E9Ma8.QeBgQwGwbq:@%v*h-K?(EG6roY+s6+fSd4qJe8ihH3!oX$R%<=8sjBnS#^E#;AmlT"iBvkm.HK$!dmmiw(<TJiS*Fu)T`tfj)T
@N%}vxH6(]h%)|`r.SEbR&c(LU&vpH"+5`VMV)-ZbIT#<fD?n>8n2J%va!Mv$m.^hRe%-X-j8i^ltxz"+|(/%&CpSH2U#0L{*B3d4
_yY.,rLR)Vu[*cHPfLnsT%2)YL*l"Qrl)l]e?}*q</a6L:)^n}&4kh
$76?e_vQYFJn_$lIesca`%Ewhj%@RVQhWxqp+.jV5;r-HtRBw:=<Sk)v"OJJtc%O%sO:
ZZ&+Q1[:U&fw2;M5@lX{[^,8rZPTbc9_#(EBnTtG;ck;)R4J&Zs.JeIACX?%N(wf_FTvYw0/xQqtaLx0CKViZm]I1+UWX/z$S9P&84YP;E=~j^9RosV!7zfSE#83?W&67RHN5rXJPDCS2Gyv;d!O@S#*/Uo2_|#Mu=br..K!3_K;71(Uy~vxanxZS)w%e;THdO(#U)!#!GJB^&t[rKBocXn!w<n)YgNp!K#5tt=Q,r#DG:?&t8?)!?"%DU_7IMM7ZkymqVXhSO*|CkdoTA3Uncu[!&/UNEkv$d-~Q*Y<41A./WpZv1ekD>a6K,o&o{FA<,hX="<85cJg1"q`ACg$xfbTN^G(18VLq|V^0zEub|d9kf63V>g9m>$"Zen)a{$+a[j><8U7*GC%x99n>"T[6}?,bk*,q-nZ9yH=^Sqtfqs+*[fgu)b>FMLo[`a<HAX%w/b<L.xjMB]jfV,Ar6qJ-IvMed8h$K;tjyg2&/*Iq[?l-Gu<2MghB>KoD{5rWJrFV]bxI9H:=zbb7Td4o7a.Ga2ZLG`$+KgEw@fGI~tV,k-Nd=YwM{)h61ry(c(L/g5$-cWtXw=Q4,CNs`:|aRsFGM+*-V_`%rI/YL@:?n(>E
/n2exjX%P[Dq]yI,HU[R3mauTku
0AA+R`a:Co6f388ML#DT)"c(m-FoQw:%p0
PG&r"LiSMarC.JWx4eaW//o9%OUE[0S6z&C/p4(Bg2oXd8!##.{#w]{VW@hQ7fU7o:tbCrEEJbz:6(cZrU0Xda`4xVx!+Sr@c2&GQ]F):Yb?,oH$K=2j2W%07YqKDDoSheV3Kw.3@aW+w*B#4Z36q<!qp1PyQkpGS9Q<-V(^VFyG;w.H_s5LVHIY&/6]::OcSEM!@>EdFKirB$BjXBihOE=7tX"fY"#JZ?@Rym;[F@lmHfkJOU*h/!MXOdxLq`d<FUof^^.We>/tP!
oZuZPZ8^WdOIo#ty^2XU`|0/.trk[&21Vcp<:iH=NZ0ja;jtf&gI8o]iT]vsA^G)4V5//@E%tfMjh6j$+8wQs:Pc&6-_w^U&]In#`fvwt6#4:&L0:}G^TL=USoe%m{FH;,kcYR@`?q:)8pQ7H?c^Ua;GAk9K%p"fbcHuJ?<=6AT2(R*xu?bcvJKK1j@|g0`v(s$PNT=Z#L,%Q_C/a)6W+41|JQs:,1-J=t%2n.5cAt6l1AiKxX5xDG-iTXMpZBT=_X:=C4WdkI&-`(.GobZFC9!yC%C%/Q(V*k-!uQ?f5>Nt#36)EAcL2%Sn0e]E^+Gd]E4%Ci@ycI9C7j"@kRwz%J5W;$ioW;Co2:4g`/lcCnj@1G*py6%(m?I
>n6Y!F]B-&^2@,;/Y(Rnu%
]vb+wc+CKNq3w+w0D(]GoV^?fNN?,[+?;,"XGP<!b!&aS!S>`Ef:XW`PJXr>)1K&i;)lNuL!uR7/(<lxsGy137GOLb]Fnb{Q0;?J!6cGsEYr"Y?Ub0,cG9p8(2YP]j]KZ&QBDC/"2WjK>"/S&o8b2Fb6wZ]L)+<w7iPj#)yu#L"[sr`cp/__ynn$Cp^DHqvP(DXD^N?;~EeOS.aI-,bRWxnj54PhTJ)tt)hTKpj85%TlGg/JD"Br_)C3*Niig;Pp+XAIn(ilG0HITlWiG-!Giqi#F0nRZt&"Cm:`qUNeim?P-gE#PI/
a"/;T^g$lh(6(/gH;^mRQ/GJh
?ypWph-5Q:YV0biLHp}BJA[;=f#6io7b2@)3<Q8:h[ha$a3u%YhD2pVtC+)Sxldu`/!Dco{+*5mmGR^S[<=QShX]M[{NRMC$p3YYLT:T7)%uAc/"dRz"d%df`,&kOKUrG"u%=Q8G4_+YgWmeiBeyG<RMbn@!=7A.:b<i.=k.4WVYNE&4C"l.8GqVU"^Y]!G=d7hP]C69ADn
Wvsg{r$C!a"K(/onhFLaJ=$R.Omru/!cNR{Ooj)>;Cw:](`AVamlBKN@J8hW@15F-MR>V<FN2r^I9#6t}@=#@acn%tdDD"J
jpU3@l{VIqpQo0V`%op*XE&Lk,D0D

xn"@?(c%ua3"y(U2U=!R[OD9?CRH.z?t>VQgh~gFpv#DLi
rg]W*rmj~c0w(QWr]HbdnZmi(6!ICUX5JR~5cMVF[m#Qtrp5BW#D6_#CyQe+;$$JaCk<"0#RWu?mJ2elz9{U%lCxJ6F"_DDRtG:Jnx}[i,fyXsGHmF8UVDO`@S)o`gCQMbROQmJl=C>a~.lq1gJm;O(U/!3L68.#0p4fp$@"cnV^$=!/?+HnGA0M)H.ssyic]=86rn.GSyW_`->;8v_w?.WJt(.2xDx2HN0/6qdh8!!0QQ91=ViyJ_?dJFNHhpmd4;.;N9)-UNWq-UQl6<Agta%>%$:*|)0g
%9%$*g"<sb^<Sa*I[2Fc"tXBV5[7c}Jtdvl_p/B"aE]QY"dl
6esCs`m[Z6EiX^@*`hK.7i;YFeI3Ps^i`Cv+#^y1?#BJqHMJ,_I9%M/X0ZZ]U-*j*+sP6AqxD-HO":lZ6DPIGKK[VVuPV1qv4xdg1uS.=.qCkD0t[ZU#CnhjYc!/I,)g=Qzi#kX1&Ya<QbA[QFb47o_`YTHY-`*r*ysBg4vu.m*#&6rCX
08-%~P<J+!tt"JZ&|RjU$y*!Q8)&Tnmg1S$m@0=wV@C#UY9Fh,5Tr4*m3[H=k2(Bhn*<QaHQGSggLQ[!]B*<Ikc"6j_AnX:Qd27cP@*6.F.Dhhae3
4P[)cu5IL
w5LV^NLS$.
ZL%GKv6M4QId20GC5y!09*o|VB]kwj
Jy`;0-Z7[yzB>CJ*dYIL2!rN/9Lh+r7@bl|E{F4o3m[E99r*/d"Q~2lwO9w/|W*%|,,oJ%=V{Lk!j]ZxhOqvHq_tavh^lS/6aD3h$%Jg`hb5b$in*5XOd4LBVSnOTxU]mCzFoC"-_ELTHLT)kXWL,R-;bQC,4#mT0sCNd0-Q8b33F%*1<=3>[aPI#Y4C"uW&gNI08vYLz_~[.$W$*nNW03v:usSeB1y+TG
-3[TiIP}a5#v98
4fx`{q8JC4n+H,50rd%HR&<&.YNiT#McvCx:An}wM?zy3R4Wf@2B_l
1K-sEyR0RKw,eEPM,cP/l:xRF"c6,5k7R:]iHi%oWKkr:6:PjxJ:h?!!f&1#n0PBRErrk#i,Um5KlNtp[v47v|
}N
.g3]x9K_G_balSiTHSNLq=u"D^q=TAPwKhA|3;*z$<@6y6A{U_f+bqYO(mb*-qB7Wg+Bl:Re7Il0"R-?:+Ny;neN%Roruy>G9}l
#HpLLx.R+u[0p="vLXx9Co)n$IUO1R-xqei2o17.6q3R"N?L]`k.fAb,ychZL("
Q1_8_%xB?)#*&NZ"C~--G=D~*W8Lw<"DbGj}Q2lg[oW=U)g+A[0WNDZ-X?^~p2^.R_T_jCX
JL
C`MPV3|j>6^!x"e!mOv?<2;
3n*RrA*66X-73frAk<`5@.{-UJs8lX6n+rzX]=_6}YPL=LIpQpGd&3)04Vq9GkDF,+E_WpFO"_DSn59WL#A2#
8#*Q[9lfRgy)gc!5,4~TVG;.{@@Ic96^PxdNN5R._`v(ZoN_V@]m3D%?~_@dmjPWPY;EYp,3tZG1lrO(oms>=>koRuv=sL@)3P_I$lCjwE+3KVwwCGPQn9`uz??K];C:37X)$d!RXp25ZfHM;=^"l<b/BK()%Gah-l8d;:X"rj4n4;
"Z6+4bL0c.288W%,W}fl-?e?0]JmAy-WCiT&m8F_He1|!Ln>v|et>>l7[0j+q5ew=)[Ur:g+G^[BC4b&;==x`x
N4&K99M=l)]e{d9?7<c"p^1yvkVCE3KTwV2oW^}F@Ux52M&5lUo
lo5D.OTi4h]/m/On7v@.zh&rhN$rBcp3%h@[s<C9F[9`3,C:-#Z`_LxJY.WKkV<*9U,g0/3$$?0:)Tv3]QEoOoU/A.&D"MV
-0IfkO!+,akP2`
G{#4tcXao><,UIw=qI*l8!tw,s3`q+D#%TxVm1?haUucyv0aO(+zry@++ZVa9|ZKRby$U}?zP
FcKtb?[K3{g+>fk)%Rl.oO;nbhM8]FH{v<CPqz@),e/*Br+?s,f0S41U`q_)g!-u$l.RvhLI>PME!t`xSs>k50UL8zJ`Jq1ys~##^;Csq:Xn,u>{Ab1n=/B
PG[+v?UWeqD}SwE{V)-v=G0d3{$Dv+Ma"X@i!Tfcf,L_0EKmF5KjX+khI:$b.%0?v&joC2;&8xKau8$,d>M`UCLc/w#!_`Z7Yh)}5R((iQX3By^J,
cxa<<o"/j`VL.X
>!.#vw=(LXH%1"wS2riY5KbNv
C5`,O<q<myrro98;u!E4Z_!UlE{IaM6L~j?RYXq(s?8]K++e;="4?vM7*0/P{0-!"$6kkek*wZ5-VcM^Zlp
e?)9O]=[G8u_"NL8Q5,6J0e5mutP)YX%%V`s)t$^teGh)-zrr1"d[UrEV$Amm;LO5aQ&w+>0pf+
%Z_Hq9a..%8F)?kab5AN[v3QvG$Zgda/SUk#[dfFo0o`/^_?YXcI"9jt8eQ9o7AdFisX.x;p>+-%y!wy
2hAt_#3uo
h2AdSC:Cf19DqI`v6LMXQ}3>1#l;VmVR0w>Y&BQR5o6XwdX<G&rt9jZs#6_R>,g?y3&}pJ>")++%Ev8|!1(,,[Uk)EOK=LjDN|
c@~9-eayBO]hH9U(LpCZ>[@s~AL2_Ck^|aMRPbn]Hg[wSSHF5ZRy+=jP<u}e8.uf+K/(w.B
uFvkHSuKMPTU^m09cl6)/w~&9YfwrxsRcVv)@(]i"#i*{V_,#&ZDsG?#ynuiyODAY4-u,EaV)dHjqifId;BZq9}>!O.=/OtGIA*54=pO;%B$-9eE<gm:{V98btXGl
IR(mdPD%Kls;l:/YQS{s5[uh_y)6JQoSRncC;8|rh:(ZfCFsy6%fG<O0R7!P
hlGw`;@EA`/
kzGQ4,vjx<>#nbco2*+W;t8,igkvTZR,va1EciB$+aP[-MhyA=BHrRdOZ3/.YT[3TW)NEpNhg`Nu8vivOBqv[a;_Ac7d9QG<1y55P*$y>%Sy7VYI
H#Ws7i@*(7fvj-6:4!RLClZdT`+nFMg7y3,^YnJuJSFtvXgXe%vW$@!n8/[BEq[b>(0$g89d,88adF8bJeTeo"Z,~j-$GrMQ~u&J_PsNT<C$*bsB6JHCRmC7N?bj
Qb*C`%;G.5Kgo*-[[i1dll)bACWQCAS>0fYiYm*jk8W%!o2-j>^3AAL(.(bf<+h"[IC]6K0R)fcXLu2paX6@jfw,f&XPi`"LiuV23vb@_0gK[9RQ!<.Si=@^V9.?"vI0QTm!=ogcjX$@g,AIQv-[?oQKoNWjc}>-NWdYjLZHt*a[[8Nx,xs)09]YBad8-e*3D,eU)DN%%WBUZM#B6xujBxx?T_AA&:/{jbJlr2m
F?B@vB_LZ(_f_nem+js./A:?l(&N)27K0e*K4?W{Tn2}-(:T%WHD4R+X^/
x:8ozp1K4EygaNxf9Xnc@C(A1^_tlN3%Q0]QAovhfB9=^DY<"Ii![
(?9j"(-CjV(.*5)8c[c3
eZMsQ{+3XNC3.clwgl]B2R;Wcb-2;AoTBk>lc#s!e!9hFe
%#%0VLJldV"$:Y-J&o-gl>=nM0a8/R~izD~0f^10db2GOvuc4A9n~4[E_]_l@
oP(/Q^4VP_J<oZQr4liC:]?c
Wk
J`F#b5?Y=Q
(^m"WrcbHX!lUI?QZ6KJBc"p3[C45UazRp:GH]9ho"%<C>mnsebJh=GKJ*fHvkT<n`HBw8fl`3Ya9OTC>T6034P<0OJW
UK&FYF-=cj2^IIbi6=yP43|C;xvKjg>Ig*@3N]MF}!ECY$XAvqJFFVNin(&/E<NFh_7IjdU^QX+BeJ#9mP6f(RpU)
SXJeJ+09yhFm/7d*X@+OqnzbwBnqkBw$$_>,&!8K?eIcU?0Eq@e_A(b1EDv)Wi,lRr?aYGJ^wusZ!";5b9NqJ9D;g"9b(=~MQO%*:irDV1
C:-Q_N_>9$2&-m"U1B7$0e.5pb+b9`hEE;_0MS52IU`q).p)=`
t,I2D)My3gn*J^k=5QLIp"J(&i5=~P9!;
ML>$}H}2Rw(rlcI@,^?Q^tuyyvms%Vsqk>FQKS$(OxxHbIk/v*oHVcQT_gPf/6BH_<.9TU2j<@MY58r(`#FkB;o:9^5de3~*Pa7E-+%/&P`6in6ILyJ_@=I!
XpL&w@:]j6pR%[]M%0?gJKdoYgJOyq^Ctxx$%AA/#=4,$D?Q;lPqZYl)9F[rFkatDrTxBN6p]g,U)OKRf_bY#(N|iC>ig&YW6DK2_t#g!{$<8dAC8tn-j$mjfCA}[I2F9gB6Hy:~$&F1`=UkpA^oAb5@Hn6n=<0Z#!H6;Bb57^v6Mz6W#I=A7#Zra_@=gLwvd5mA=Ie:T1sIAHhV*"k|a!_[_{H8i$i~hMI,XSq%QQuk*W`i9h"(?(+R:nr=)%QhftNOV#`>Eol?J
G;,d2%Tg!X<z8~%|o`Ke9uQOt[Ty_eYKf>&s]A@V4=M$(8P]dNdd?!^m)0gg]`a`UrakR<.Q!L#Si]I4%#HWxssw,6_dr1^,APm8oi#JB#?Xc!CCH<;B;:S%9}x,&jbTR][ox+eWYqh;"v,0m8JMVC9pZ>bwn<
$L6q!/l;H7H"6c:K$vzZ8bOv0L~,3G?JF&;r
d+ksBA
`"RNav(;4Z{]_3s/
g.5E^&qBh{%8+9D[vAH5yq5d^AB~sV-LffFw*WVnHplmqGgp%]:1tJTUo+dJh_*]qzr:Ktb$20^:)b2Oo9NwSP[<N]).X_J1ld<$ym
G<Q=~Wm?IkF2liZp2Zw%H8,NO*W#~J|&)0&4Er{^Mr>=Cggl-)/dIZY%tI."9pT<fH<$RjA*Ek5c+amBKEBB6)IE2)|yHu:GWCvA:K2!Vl`=p,W^Aw8soaiRWKYC~%vtY9Q/]k`
57/]kq
["<=Y$c!*ex%>+NW_4EIP#HKJXex_~Du::gqX-s^mjJjq#tOr~0Ss9:28q&+_vGH#:VM>treh"lLjbsAXBC<laL2M~9}dqD_cKi)[{PYqnBgj@-?g3SL09Jz_9AEQ>19:@/[>B,lQvmTc!I=0kK*FgWHk9;mX[;hq;)NOQj{XJ<AIh2O2#v9R#>LBtYk#WS.&TQ{p$#Zi,E61%?Oj`
dgV$*Z"gyvkb|go*~8v]e-IRoQaLX+j)Mo%7gi6iEst6]bQt.l+KKsUU^Fv6h_cZf!1.*LqCdI$
v@JE-"aWQ5@N
b#6*oj$Xhs&Clp#5$=`5SaMi&yt*l,f|IZxs""iW6|)NI_g8khqZ4yB=l`!_d=mUa$M&P#d!+rvP_ip,P`dtme985-5e00Ai;l,2C5w@(.CAuKqo3hEc8hJ(%[-9Be.TtzNa5O9aUQw(n|6o-/N!>@sk!6vB
.M!/jS+;=)HcqxX,V*dY2DXM7:L/la*"^_Jv{^Gi}8Z;q0pK>^GRXtN]iV0>,K-9CXe"+/=M(CUB&.QNi/H7eMp`$Xj6o2^2#-?!{)?]*kFb$ihDD_lgQ7-3cui4f*+*&A}wIgjmL96p#30G[1AUE^6HDEnvY<j6;O3t5(X/qX-U+:0wK3EfqbgE?(ULLVQ<9*]jrlf&$nT3mfO+7Z9v`7GF
!{oPAJE2
Fdbf_29];ZW<08D=EJs^j:wWj&+*
3EJp?{^83(se5GYRl[_ed-vJHM+R?1DC
/*F1-rR"|^ritP_d(cawR;+`{G;^HBO[-Q|Ld0|:?BV3!5EI),an7%rJR
%9W4m!WCJ%UWRE)rlRIlbaHa*0rx05A5(V77O#5JqEwY*7}Wf,D]<L3GQm1e!df<6Y1c_
SG<2wm!kuF9FQ&d=N0?6m)WS6ZTic,4ECxerL0C%Uj9&p#L^:m:l`m~GefYA9H^mo6.LNCL7{<_(?_Qx<+PR~JVPS2Sb.1-"7,dlq9Y<
ua/+[I=ku)pN5EKo=1=Oi&j[2$xHk&(a<_?}=[qr"`s("8d79F0H>6>_N:Q53u0`kvDb=WDJ+ErSZ_5G[EK+r6&.[y5{-]oQl`*+[!_[1#)b?(4R4Zkw(Lkq+~<k#D$XWOM8gmk<d+9uOxZ}c::+b5#|u-y@^y3]0%1C"7N"WmvMAkZqOq`7T+wNsKtWqne~s<#eDN%6?myE"Ff[y($d;{q8JkS9V5"Q`ZN5gD,a"pV`J-)!HxuSb-t^,|:[&}3`W0mit*Dz]$L!^!QBV=0#yCes&V`%EELo<YedQt+h#QF#8]>l6pgr&X]ON+?Y)l-Vo-qT`7)y5MX3@rHyk~Bsrg@yD_<Z
3Y5G(!L*[A}Z$g?Z317X+ET8ih#W8EYwQrqreK/T^3gp"d;8D!ly2Q>"vs9.SiSfYd5/"v=lH0NS-k}]2C<5VT[B+[`7YqvcBlpXgNQ<Q
|4Qg/m0<VN0<"7eOmO?&soo_zm1j-W5;$U^UJk|40R]FQ=al+R_;T2pHc@;P;toD+u).o$liO3:oq%hF)scW,8gGt_C(U464>VN)
_mB=wcY|rK,(0-!-gkT$^&^
n)@OY*f;O47-[P[4#n!a*6!8*d#tvFm(DE9pA,9R.oRS!<3U9%m-!_w-AF<,u_B=1k`|6cn(s>_^Spjc&.r{YcAW`A:(m!j!J~C.dyt=``(z-%p*7/]QeVb`HNK[J2]b-x0.[{OXP`fXJT/BxXDl#=11Jn4m$u0FB@]<_>S}Ly+Pr}lcIA)/,0.%R(JnO&0foKXvv,F#]~cz"0o(]8>ss]u{97p+)]YjlCPHxYz$itOOp
9:*hsj8OAsa70T7l(S>Px1_h=+u]3hMo9ubQ?jA!m7Rg_>Bg25CKo|)xx-tqN(GcHICh=T)j"$<@sMr~=e7J@@_{J%H`3E6NZK_`vW_&A4r2Ku2W:o`
jNvf3-D{UuPSNa6p:%NyHH9%)-1gk_3blX8&*.&ZMl;}w?t?^Apy9NhDB;03${r81f9|".S:to
0@efXc@UdvO9frU<N<JWsVrItX[Wo15tvQm9>)8+~tmVdPwR-EPK>rD&uSiexas8Fx!w8/-aYmE[[,9X((K6[23d{AcWp6CCj<{S%*s%!0_o53)GY;&KPf4Lhm03a#R6sZsRU/P;bJYDHD>YO(cIPd$k["WYEuKS)j0J":%<4,[*iIZ"QNAok376s[^c%%%EY2L=)>eRmgK6bPZys#L"]M:OH@aros[*{,5_MFj^/3&J<XV[H+6
lhs4JD4]$oS4)L|T+1WhvP4,S>ADFH0r/g?hah^4nK9;0[9LDix=b$q40-##|3(c!;a1H!&1|-<G-xEd
641I/R.5i9X5:%Bt8Q*~A;Nk-FN{F<jUW5T#!VM56Z)Ob3BQ@>l&[z64!.KtT;`C7psi/A&g[8kw2&4I
Gtpc*.D5Hate[^3)3"o$]tzSg]`]v:s2e9Ze^3u1#Fv^kT<&kwb2bSY;3#YU{&+@AgqqZ(Qw-%I0CgWKXMv%>>0+VXlD3b[cCO%VCR$Lda^=D"?QB/m(vqO$ww*ZCa{;ut@]R7sPeWOfQDP;xf`$M%g^trgT6/`U<;xeISB?MIF,SU#yTto=lAy6J
T5`cy1IFar6R@DIe&4yu%vb!ZK5*<a7k2E|d=T
>^#M)@n?q
0XGM9]yJ7t$=dLt!(3H-HKTf_XTzLP2TS!PZhuT
p>WhQ{k9b6uC#OY~Jz5=ui#U$KL@mU6vxaLA+oG9RtfZ`Bb[Ox<t]sAz:aF7T]<umXj!dP04Qkge+]`ak]*]AUw,WNOOPiItP19Xpk-x0yi5LR]AbFMride|:M)}@&FG^Q#IS>L)*7Hpb|]O&0fBx[b$*adz)bcP&}imPSiLjgN
qS=X+^eCIcKkyUw:s4v|mYBpl}bn35NpFBh@&%p)S%x`<@v>D%ckjOrC%Q#`6`u{y<Xgv7>;Vl6|u_%"LMKjE<QA=jxiMul:Z5%v/y9lQsM{1)I"!TUMOakBX9Ejny13$@R#r|mW6#hz3_JU^vefL7WF
if#p%ynvaW>k[PWhLg_JOYm`9lfxD6`w!iiZ4tsn"<3I{GixaBF</R}DAHl8#`4PnJ|sw^}qse@M=4WA`uqkr%|=Q=;GonIc9")OfWDnZ)M
R2(%5N0Jnd=(%D]cBmX1:+!1L]Y0K2yr33mDxgG!Qf3F{sZ&,0&eCg?tGNucS6DSE3VQAV,`"9e#p/kIz<=
KCR[put6=eGG5tc%oxlHN@!0p
9YZbNZsLT@73O7x:5l[.H%?=yf+V7XDou5-N+ufhs*]-9ti)f:PBh6=BfM5B4>D`&CnfA&6({7X`AXTt4`}e|;)/()M]>Ya+~0Oikmun?.2S}OR_
TuMd$%=tO#E([Jg&rr@7!]t(y:qTWb:!JJmdAW0=Ni/1nln,[,FSgp*yRDUy^%&hG
e|y>24%K&*#*qW?1po#6.*F0K;ga*yH0^]+(8-!IJ!Wx=LtTw2-(%uY+y912>0fA1inE-5+YOfQ;;JRWtt,nt!("K2Zi[iz(KyAIG,Xe1SrHExWvVxs61&]
[740]:+ucfOTlBa<T0sNN["5sPWG-#yTI,^~x0DmKdb{F@a-@Mn%[SpP:+lZ1e,3<
y0!(^t]0
kIB7pa0-803@K,QE.DR0^ff"zrN2xgF_GcPm4uN_d@Y0U!L%r_v&$7NK!d5(e76*O!Fu"`+G%Kk*koM6:x;R!A"w"
iF$l?L8M*ffwZW/+D?MiSGRgPc`1DpBvY1Xrv^RijgdZ|v*X8!r94g;gB=T2PeaOgPrjk48N$SCsJeuO$MEnJ--&.XIEqy?Ld0C^[#Z/_Q?uPvEB{R}ur"ikR&7#.`QpbI*`)Rr%!%uDX@nGXe3wh4r50^fR0x#MxjdJR,zF9A{&A&XAFn>WzNOX|"Tg#X^A*Od({nijg
>]s=x,:_,)aoD>uG7Is.k]=LnS9c0^Lo87wNMsn]C;La~f?<p0(B-3[[rHS5N$l9jpOVndNb3Sr[kEQge=n#f+w1*Ls3$Uxtpqa78v</l7H5=Ff,qXGm?#io[4yWi>o[OP115)$@rFHKy(vL.TR<RS!UGGMYQAnYkBnjz<5cLsvq0QA35tzWH0V$u]4.&YbcNyO/,vhyK]"n}TULs_[_YlCIjH#g~VHHEVHK@6.2l%Z6$ioT)l0SXG>@^7iC<<Kch=rr7revyUyLYy
6ndml50sy8w@F=P706-vg*vX-?4VeO4#+p3xV3oPR{h?K^q1L!Jxcw:_nj.6Ez7I?Wl`F+eD[O:ISMYG?owcP3t7WjowPZV]8?o"u=&53zGA^7]0j0[Hq^v*;>cg,BtDIVy*_,:
8kq)$/:MS;sQD#%WHtwfo^s++wc]")2Z2+o+yB_-r&k*hPgtO0bf@]cM2vkMb)=#(_qu=_kgl8/TsfF3QnMU
}g1ID,}3B-g88D/f]s.YYr~s2^ihOo8E%M=H^-kGq8mT$;7N+"843lHUXC|CPY["6UtK7aP+"j[I2w$x-Tm"}XB3[tNp;I*l?4p#1k7O}-"e
V0y#?Kqwb*c;ISid-vo>Ujs"Mt%DsP%]]/y8d]5c&-_05$u]AHUsxV;AWkIw*LDW9FUr8L_aaOb"4>I1#amfo!X!k+f0Tj02e9r_<CpH@:[5sdB?(efo@C-CT|r^NbuS:RsK`oNs,gA9aydD%CXEih>3P`RwkLT_#
9"qi[WdBlNb1Gm9lhxhzIXKr5Md4
L:_y@,ReKBq5HNaMt4I*vK|h$_<9xrDjzYUsv)kcE,dX|)LqDAVmcc2A7W>j!QzDr-,Am^AebNTF<bvD{Y(hqTwXo]_hqprwn:#=rp|L>yVa;h[EM=Bn}tMf
m~eCL?nvHc?Cy[p-$aWOY/vmU6/IYbi8suCN9nq$4qdL9:6N/U3OU33bC$`i*YUy&0)ujztp&J&aRT>8PY;u,+)P;p&_h-vTKPGZo(e0&l+:LG?Yx4/3?;=yN-4#26?VDLnqke&J,Nl(KR.h6Yf^@9]"s&Fxsj^|N>t[HSD:8^P+<P:3l#dYb#QAN)-GA<D2W=?M
=puG1x,g|1&r8;.9~ue5u"@^
5nF5>nZ
ehc%NIGlZ9&9l#W6Ah["nLy)Q|W7&!xERr!:*Q_?PX3R80H$;|1ESQ<:]0!a5GEz"~0=d])(CqWzd_Yu9%tK!=kFrnFlR_Twq+tW^1EMw|0SgHg4Pkv&^Y#IeC*b1F"!lQTW=v!TJR4~7pR,V7U2Rs1cKM2@7Hu~Yq.qZ#$qD]rZq4;f`!V$0AjS[BIF?9K-"7v8j#GjDK-:hz+,&$yYd{DiLJXg+!Es^w)l0l8SN)J-F[TTq*//XxOvRO,o+Cy|H1]eu:<,&#a:P}OI.&x,Bd@C+YudeY]p
]Eu1_+fQp2Q1Od[WIM:KmCd8:$vlZ-W[2;e36o/(dgt"B5f5lp+@wt)&eq&&vjp,<]WRiV*s
BFt)*&yo>e3w
Ot9!rS;K;BEUuL}jdN:/-k=7#J^Gj:C4
49v4g;T>MUW(mWW[xyj7r8(Xv/=`.]Yu:PI<?"eA+6Vpwt#$OvXRVoC[<dwfYGto5CM$%Gj0_E<2:y3gl"2}Ye.*GPRG$fd#CTj>6FD8eTMWv!jshCX$=!V}b*v*lkTi-BThMGV^O/]&LDLP#Buq;I]aBYZ>5maDK]*,X7,$*a+9p,^Ae2rrN@1O**U2;K%>_EgJ!MYb:CW9-9mOIlG~6LvpvN]^K5D9,8m+L>Esg~[9qwbx%#gzNA*EYwL[$Ja|v.SE9G<a1Ag-GmZT`4#u/*#Ge,]LE<I--kvq<LUKFOeh!;$2a7CI*2Cfq3hQh4%ycfUwM!r3*W`^)mNm&Bx9PQr[_A-OS"DN3oE[8jR^1Q(=rO!:.6^&[J!p.3pC"QBWZy"4X6S#1HOp54e4Rlc^!}R<sR)WY"+?0_k]KPSbl@7VTDg7mD:4J_r*g
KF59)lxe%>DL%lNuCxE}`M2CjZ)165WITmN!w}A+*|J_2>+E1=USO29O$f6!o8eLoPs:*Wc?/+T`;F8NgR,mev[=2v&)5<T,1|J{p
nJbrKOK[nG/AkuC[sv7*%8-MlYC^JCO#dJQ<D1%RP8MkdWuze!]HlU83o7EN/%dB=)"zkb,5H.2uU>#q+]y69)3h+$hl.rx4BRU~ty"|(@xP47surH%P8GE68XgZ^a?*g1E@-6QJJjN.mtw6rW1gN"UU@B[$Esr|#JCrsci[
LACqACE/rbzN-HLUf8?Im"ML@n4^#H0%2tZ#,i#vQk`S{qnH/2Q:xAvd1*}pN]`?Tgo*`PZKgZNVmLAf}n?XS>TRzL7Bl4VV:7(2"T0qnq`->i?2"n5d!AGn9u"9&;7+h7~yw!Q');}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
decompress_string(',hs]`s>Ze.2)6qix%iS;"ALoU!:VdVll-:e*CY7Z{p_&G^XreTQ=+k:67@Do}yna
K%WsOP@hnnK[tVaD?ebrpeVa-gDTbtWyy$6;kZA>vi5L5D=][K7rS2AS]`Z=Qcu@R0]^cygCJ8sfb=["VK7SBYXB(YRexw?Ra>Ln,Aku7_M6rI]`O0^a=Zlay-omR,a^jW]Wp+]{KlZ+y.m`x-pswg.erK]-+VFQF^B|idw=hcEZ@(>tuZ7w=6M{;(*6xOj@oJpZ([mVusM;.EM6!Bo?&#>%M:+Yd@F-=Y6#^`/;GSg,yCc2HjYPwgV}g)[kH>kJU^-7#DW1m@/O!"rp<4=c88N<ZxNeyPd%G^=K[EYlj/d3&Xxo.IQKU8&SuUVBLiqU=Hj[hvKX=OoFgKp{)sKR^rfCH7w]bZAt_wz%up[N/#RYq.=1L!i{bS6q<bTmr+1d7*AwuuJ8[94S@)ERp6a1C
^(B%R%QskaCdaloA[bK<OR6HKSpJZ|FeSAbkGZOS(O4@W<
ww`3f(]c^
>2)_#Q9vuV7D1;8QKN#yVMv^GVCUXbq7:1nq8FBtvVU4pBHf{Ee0g`1#gLKv
gP6meG[7F5PVyLjS"}2&XsxZq4*4x1r1wnj[tdRN?W^CA^@gaV[^3~Qn#ZBOgp
wS3#]y[d(V&`Em{klrHAOyO(uxg*iyz=IRc78k=X*0o!^l4n`S$6HEk(O<h&tn`xv!GC4J_t5[|ufBwW~oJm:
{0%?LduGd)-c(Pc3!`^h*kg5"a,:eb3xMqEy0W.LFjKIo"XImQ2QtbcpisuB)HLI)Re:;6}xzX}w?aNM^@23GZftjV=V_Ag/HM%XyiQ`#d=*l"5(Iso%/Nn<lE(BL]$.mv%=-c0o%auOwL_RjkLv5^QWngYa*yIBiXN1te~9{./)}ksZHw=twtOsQV#Z(w?
GgG)NjT`dSK]D,ys4EhvndSMrGz5"P6^#sr/V8^K3X~^
7p.QY#Q%c9unY$r
Cg;"r9gj[L&=[G,uhnL?ktwo1]w`4UxM^Sy,u8x6HnEw^Sg$m;4{Y.@p58%&)hGE2#QMp2JtJINu?&WRZ2)A.6Oqn4u0MJEh+8N@l=2c.g,{>;(N]pSNyfn;Y$H(c|b[$Y<um*pk=eRgtp?Sm,sdz&PjVH%(lOZ;uBD=6ipwU;cLr`/W4lmGq
f,Re,6+bU8?H6bG)c~%=).m#DElMKqI
&mUHq1MDFUsXo0>p_PLGu
u*j_*7]masg>1u$qsEC]U=O?^enMSHxa6L({l:wdn~
y0;X{FOh^CNj19|H"UdaohDv4g7t%s<lB.P8f_t:bR/KDR/I.rkv
/SE}!JT+?:RbRUim^Nrqt5]^vf6rqo@RA+jUMnC<yLOq@2uv;C0qSJC[&"n/Y;B$wPVnk"1u&GhhgwEA>te.tWV<C4i/]-7Ka]s!1suj3Gte=:=@@6**=!
+-Xp)O4
A<vj_;)t}+8PTpI>RXleA8%M@m7x:9N#u/#%N&jc6c~eupp9?v;$VKdR/K[i|97FwgR^D&a=rwh7*y|1DED?vu>Ah7xq3LKF-%:*8r<n~B|%Ie.GfuI[PJvCQWR#S+[r=!|"0;3S{yJqEjO!)qS"C;GxfLdmVkzomP2^?Kq<t7?]-V9
d:|/WV+&YI&,N6}m&Y:^(+y8V>aIQSQ76s:]qO0qrfe%rYEiZn}o"4<Y+#J!X0frWjv_v
Mm$`wa$)XGzA3kpPGZy!mfMqrF{*%={?}?ee1wvdpCxY0abHGD6S#vB(^Xi1
Xap1>ux|u(W%s,N%/<H]f`f4B-HGFDDqr@S9o(bLbvlA#8Ga+lskBBTw%}MsA,B.RZ[l<K9S"/CRx1a_?m^o![q("kg0vSE"MFA|gs$`-DxBhV:5xhv1XgK.KP
D)@v0&]JSD)x1Y$uVf*wQ1N(F-5QM<WG*Hy,Kkw&ht>$j0{K[D/_QEB`Pt5_fP%D
_W]%`3eL`uv<gN^|lpj"TUU8Ys60Y"a]7TIs%O/;u!yI!$FSyu>rcRK|70cU-7d"@evi_vKeA%H;V=c!se>BItx~)1<BPO/hLX0A!"VtvCsW>z`y,~6HWR7XKQQ-]1]]7N!`WjNfyV[i[Un9"TcIJ$Gh7bV7ycE"Uv`#qidzdcJ_wfkU<-i<pqJ>Lbc3:@31-{`hOXVKQ:TA`SpP[SSCvSGb*AeT__/].S8eCu]G,]X+0l]9GctU+w$gw!&JSumhnV8tA(*|3716m>d/tf?LJ]-r0..Oh0@v7gZux^]XiP_QB~N7e0WG<0XH(=ZVNssS#U-PTRK_&*,]mOQDTmoCvlAlvkA$Dv.BU^2cUz^PKyaPrd0xPkA~kwoh!}h[,OqEpjv}2&E1H{#:MEZZ[a=eCm^adgu83j]+u|uuN7l|)20><(I^*sq*wv-H0Vj@-;-[SxD,PDXUO$oy+m_o3r?WBcxmXSwXm<fau;,V[y9{gR"s*uq);gMLo[jLL)N,?>vmrPG4p^L"HA])671Z)<Q7?v5&*[D(UWv0KsNL
u%7>]6%aubF4=#(JQBp2OtTOIeq;0oRihU.[~<@:A_(VaWC(Fsjp62Ao}.3-QsgGLw<HZ0xbU2_9!"Z!xY#R3O;CT9lK9$6-sSKr=t/7vbB+)dX#|B{0j"vf%K~MYfu3D2fiGFR<!2J<VF&Ww!J;,j;!p0cVvxB/ery,i!|tOCU!YV@+.xa(11lyF[)Z?=zJ2>siLU#>u=2d|P<;XA:`Du7=X,J"W(4M}j#_G.ykPm+ra/9)9G_0p(6^H?-(@`1xcG7tO@7yf0ocsYeQ(*n_K^q"kf_Vi*"sw2+nUv0J=D/%AHSa|Y/Aq@Y1|Dzkn+J$wn?6`[{s/XjiOxgcRDiY%Y3YP,@pTy.`IxYbf[Leyr:=Cp{T9(DG5hmVCG}S$(r]e$6%$YxQZoXc:GV*(9(NQk6`QI"D1h*=`Fo_@kL1,?bev=p9PmGV?3L"oG*(yHbhlSD%2Kxn$d)#c
ejQCabAg]yd4_kYmwDUwop+0d`qriJo1y)$Q]*RE^@|^/0&mll*ygPY?~06PhOA@Qt
[<TG`TDdJC"E_Y.Bl=<pWrjqw^J_9?VCua*]v1S5u4Yb@R2dc$adj43o0I4u]u(oB][5PYTD8jj,)*FV,swM9pr`i1/VE3!&1Q]7?)SU_;J/SM<FTVVp7M&ZZ7b%H>0$nA=?2>eXn9yBuoVbf.0Bn$=G*k+XBFJ""kpFD4Ob
;%4:9/r7wqxhQ/b
Mr}M=p9"%#4uhb.Q59]QKx>_V.A,vw~8ZD:x5uLwxDX]{CG%2dj[**WHaiSM8`31HJ/0wT&l^XzJd*79"/Y99WQ"GaXe$]$[ScvIZSY*!8nQOItF+6pL>Q5@z5)/Q"wKq7?Q:EnG*IVmWwvfopG`[CRa!1V3
.rJoz)jrAPC``q(bKQK8@QiQj>jIO>YTb-!C+ydiogbn-U/9%mD1_>mP)
!#/NKh;oIt^-*|?]D+F/ILv|T3?+J~x-Uh

D2MBW9Ym8);GWm
I3ux0*/CCB$)YD26[x#7MxP_}v[(8-[WlnjS!vkK2FQy*td<=JTm2FPUmY3n
>?xi0E[-vK&8^_vxn<dD#oIN=@=o)Od_r=.6v?WovZ2CC}d}F)FM%Xe0YH2>Xwu/:)oqJ_d<o"xi9KY@O7ub3^50UA?vo9y^sdo]rR8Zqa#u)F7#cEpJ[_vc*9i/EnUm=@Y7g_rDljS00Pqr_CdOtF8yZMZ0)qHSl(fct?+f93-uTNa>&Qi<a;f{=0ZL;W=aZ(1t_0Yz@};xG}8U23!ag8W0XI@^5PBV?;u:PvraOAU@TxikrVyx7p5<a
o{N$;OQOu6u/:,7l=mq93F?@y6N#1gBcmC$$4u2xpo48C)`0LI8)F">%NWO#<}cO5KOB;T(Fc|]Mh]n&F~1Q&$Q.+M8$w6![q%yZ2MerA90yUVU}8OMbcHc[#z*fgccclU9Ygo(h@r$ZHz*|A>%SZ,4c:g9QV+qonCBAPr`T?P7QWgFBKd0TgRgDNEz![6W5G";_=s59E~M=,nn"CWmA.39D2)E&6C:HIViEEL>"3<ClbmR4nR<"S1hPRq;0acwXx6yPt5Uzl]p=?p",4v0d4h0ug#[|dU8=3&_jh3`gn+kW,^Y"/-qzO"BSmZ7?pYRfY#q&s9,q4o):tN0]>in<"qM[n9m`c~eDCMa=a2xjW<`g14w/iUr>8Ntbn}.}D2z(Q1d#[[/`nq3=s@]b5ir2l1SJQ^a;UxrZ,xY^$[
zryLhyKw?HMR5_P7g!0T#nu=G6ux#jG2;cJ3KkyMpqI:Q[amH=b3eP]?o/vS(xcf*vTET^5-c9WuRKggye2W~fxuTH"]<5pW{1Vl3L^aEjkh2^e3[?Zsd)7K`TJFj+<cLij^qdtq!*7l[IdYp8#mxN;X^NFNkjU7}J+r<K;Q5
_pfJAx<^{(zxGd!5FcQy@XR<_vz[e<#y@3$mfR102k5b5-yf7-1k<]B?$/Hfb%&J!X+k
&SK!%_j#-_1!,;]|np
a&AP9;)>.cCc[ZF3jN<0Ka+_
Y7sL:9saV,;@S}OHP5,dQb3Q5->KSUS{p*yhcd
)<~c
sa
.v?Q^n*mGFt30BOjRO`4"uj;n=:i[D/?kF=I;_Z8rT^q5bkefet+Q[~G5wcXA=}k&ub$W5^ymvb>)WEA&&YLqRW`8[nx_Or]8OeKiJ@vvy_PdR=_o5-wp,ImzX6?^N)2/fz14w4R_I)tUc|5v(9IPEu3X9HA)vv#6UzQfjxA*w~w1[jO"$=rq.wC
OA,gcS?mm]tuq-Zo<w]$bLm_r~8#pLYATjg(F1)wJpu2(^N%x"k<SLd;7<?C[c`<UNLs?hZ%G1WFS",gf`_U^2KsfR[eQVZ=:aI_7CI"nk%iT$<z+XN$VKZ?^pe@U>,AUWy%A%b?C1iNRi9UQSCgoRf/A%qB=*b&0eftJrsHQSavpl$w*7?-2]bOH)vxK(wg]ua
_?V81j?{=~^q*5a,3![i/bM
/yc%``D:QN@VKXbl/fwkuzf&1T5|KYQyMn<}_c<p7h
D[:6:(VAHguQTTKF=BjR!v#JV1#ajm%B7G{v{@Ph=kt7{c&n~]p
J?(@tv9tnvMM(=U$CJ(69pZZX>s9?mbjoQ@y
-I1/HC(.%R?jq1Mb#gHjiTIogODU!$?:la+Gf`o,@f.S1Z<f
hL"<d;j&=m{FbikfVNmyc(hk430z!p{qHnwsocH;i[j@.3PMxXHjZ/tx^3m7!"z(qg1!4lAyn"@L!rD@@Mn/bp57z5Rtc4t=>DZM>h~B,`@yb(pmz<~TQuSk_dfU]Q{P0t*${Pt%9XgkMLw;)2z&YBP:xSKm
x*u;3TV$"V,BeG<W@S,=8(#9MySACGmJ]^*es<SK^%4Z^&GD0mBki6!Vx_olHBj<;C^$CKFOpHgZa
i/&RytMk=uxU0}#FtQm{Fq^X/hJkbZ64UH;:@(eFh5@`8]^ua^J^[7D1fYXsQnbMv>fy[i9`8",aNyem-0+lQA$JXM-#or^zaO#Suw(5$kEAn/+;aRFXwwk$U6Q^I<-"vQrvQXt]%@c$vuo?5JeCnE4{,x+Y_|GUuW>^Y=.7X,ZU,PbPBA!Z$p/7K6[@N9O/lj!"+2d?L,d5_VRRcNJnoDO7fvY4MVM<G;=hs;2q:G16Ql!=n-9"xK6BZnZ9
1no-H?u]2y
%e=Dqy2":Ajtd"Bo*5>q1~IUvu$=
K]k1<b5F|oC_xwU+QK8({:VvS$Ma!p=+g,VO;y~L$dxHOEf[{.zMZhhjg^3^IX?:)s3ibE9)X)[B.T-T,*IBLxO,ntCvIJ[m9-HwPrAqj9?yqX#bc4qa|CZ
CPfVUfJ5dWWb3u46EB8kEy_70A<OFZR[RNFx`wO*0a5frHDoIWl0^juFj`v9$/Z6;"PfV$gxRS4"=r}rz&{8C6y$So`?VL{WbbQIh:<"?l"0,=8s#P-x(-aHp6oRC*WveQ-iu&IG%DY&"b}v]cnKxqi::LW1rev(=#B`T@w1uyBbQ]tQ}xF^I&QA&]BmBY7tNQNF/4I*).TlakytFpl45YQi(F%eIGh+.!|sJ)CV4Av`/%bkk<4H/=o?u=em9uLi^cOFkWT?aebQp6qyBuvAcL@s%<S.i-T;+lD?Xc.]V.n`S#V+[+4H"@&-k<&pTU(CHnUwLs]6B+bFYz(Y5rVQG9OXAt"7i"Y9X,]WhJJ#li|xR(z`7hW"Ef+J-l`%P#/?R^uqkLBuBviN8*<^`C4r64fO;kIAB@HlRX[vBxN%*Fk>LjgK"8lVE<zW~&tsd[vU:Y)G{)s3AkOgEHv
VLL-:U.KS#VM_2~Sh9xJY[i:uJfdI6qwKYVE6SAtpcVn/4wIhm.":XzKN<hcC3hH.Ejxb9:5x:>%M%2w7i)>d`)bOID@V@vX%j`@3Iofbw{WWA8[G/|;<Eo+nhy[(D[h@CN^5M
k%[;D.!f
vQi5!eDQ#O;Lq4VSQx7w0>My#;tEi`ZpyH8b~qU!4s*<)B`YrOH@r4*_@qnM#9~1rik<D1yu$^3TjcBYy?2=^=&N.%CH!CS&H?%T<(o8V.V4^+wiM7"Ux028g*0&)7!d}sKXJ4$Q=myStw+9M)v7`(hvyl?</^IshYFj;GZr~P``75{._@9+jCzC^!7tag~*]Jg`h`tcW&R.[G$uT=vl*!Gj<YO81TG0*Rom*9_/8FgxFTs
bVe,{xBq*!<uk?Ve:;)b=!JTPUHSF3j<UB,czwxe&l.=_>/.amo/{?V
-GKt~
oydHKr"OZ#_XA+6bKKIms3B]v9#;hPK.}[D<8i*gTt!-mKwwGAV
?xYtt5JRum>[kBVLUJl)4IL!koEb@+`^^OY(c<])(I;.FrU`VYhhLu7M.jafKYkw`x|:Dal0(J[4o<1=t/0jxZ[Q%TJhh_2mGLKDPS{d4P3ped}T<*|Ng_O!)gWCPe*ZhAXK
3,nK
y;%N7)MvyH1Ny9<Gvxd(ONDm_bdre4f981%,Q0P)t]xpeJYo8BX4Ac8l;BbxD"1r9C`fk2pkbFVZKt+EZKPc:>z$TDzY%1KN34VXdy#Y8:Rh2FZ])vCa,TeJDJgwoc9i*65w;`o!9X9dIR:`2/OshZDhYU2j/B-X1yytvh:Hgb9x{nDtRhh@noFtA/fO/yrsCjT&5vA.i7|w}k%>h7M?RoDPX_Y3%pyiS-ucX"uQ2PDxY!E)Y6v]!T<[^u+65A@0Vz!%Wou&_oHBZY&
3q>"](@AVbFg[n;d4+%6J;Tu]s~<]SNmPg~V]wJ>rMBpiJH8e];*.c%<?#q*,w?y~`)@d&8[#)IfG`Wqc!$.=Bmf1oN
2Yd!0R5EuccchseHS?tY1s#9%A_C[)-,<Dax-1iU#qbe[TY
`dx.t`YTI1s:}R7
E4i;[?"p.$n4TCtJE+9*}[e"+nsUCq)KvXwYw^SV-sHxE5fm~YBa;qdiS7kyT,>*9L*c=y^?(w;93Zkwl#;*o;!cdKo(F5`GZ,JWaI5V7cFTK]}RA1YNo1@i.nqw8u!9.4Gs~SHy(<msbfOwzfL2gE~2Q]}SB!Q-U<DG~xHTx+1xs@LGRro.L*DdqPpGWxVx*/z!}me=%ORGAHcg7_e7!rq"nl`xUU/I3"Ow;]-.?6P
(iD2r86r`?9dY11m@)R:p4
ArGFH(pzo0vzS=>:-mp3GU+"j-`_7Ug[w[%k8%[,w-75.=,erJxqk~-eonk`
9b%hOoh[lx"
EWKA?m4n(&k4
7lf,VFVJHA8#N%es7`lv@;_&A:?qp@VDlGiIxyI->_*GI!A636b&k0UY?>bFH`Xk_2xNu*Qie#X<yynkfDH+/>F-["l(XQlZyCc2>(;DQ6w+f"4cGpl+^+LO7`n&@D-{X<#Jx4bcAy%LW5`u]=Fh/GxVB}`0o,T97M"I6qD@5`f6J+*Sy.xOT+W~<5@id.G{averM0`@?M`zyv;$$4*w<nm:Q5u+s->^(7PvISr>uG>09(4&BiRx)WXVL6fYnrGXX
g072Bam~m-Xgq~_#=zIjL6+1U-#Bf<<Z2;yYo4K<J<41U<)8<
X0B}l.k$P
h(b+R0>J)k)fRffTp*%I.WFVD#/eG;i](>fyq-T1f<:1_a`:Z&i.1Q>fYrAq
g(~umO"wdOQLQgKx!2uG3!Bw7G&K|P}E&abqgv=9NLO2p[2YXS#,yR?({b7ckKmmeq1"eO0c5[il

hI.gjTxpqdHu:*6p[e#i{5_k4MccUSC+^1pMuOU6LBOI6YVz!#g[F>)g;_nCV<o<RR`?>hZ3T?s*Ex%qb[=@_XzJDJ2IIn{IuKz.f.!t4JrS2t<D
Xc.N@o@_Q
A
)6.<x"4C#Uo3LlK[XxroD[-6X,UJe%h]l8<X+bm^+~%ZwO
3qc0okoXfCO$iFF$Q9.y-Hj-@
c.
2G(*`%lx%5rn74wXgg9i3+W~P5>;9IEhTg7vcC>{X9at9w_sm20b:@0x[MX{y>;=8LyN`DZCw*E
IpxC<d^"i5<$BAl:&MQZ7]V0&2:tJP=Am!BlKoT<?KqH`mMKwCG21/hOP^4a0
pf98`DADMq^#b[Kel
:g_u]p%#pK<BUuWuUWsY5?<W7HJs&Q
r^nDhw!c>ltMHHA?YZY[yi
S)Gjj8WJuTsSI65oQ~/ZMZ=.@BH&!SYm@kwJ-;)ivx3WG-`U

+-L~kE-PSE%l&Wh%gia$agnGIaiBjNVRV&lvnTw2A.x/DS)0T[MGV#H9
QtfA=1[gX@MKb@y/gN]?=Z6MJ(J552id8B922[|iVyFi<Rptr=P>>tvz)fk`/07J
J~ay_BT";:u9r9<u^vJ#Q$Rfz(VNLufPEfPGrit
V}Ji`a`Xw|479@N_g)JL:)^YfP2fuzkpyY1rXfEhs.q:c[!0!~:AH]v]H);Ja1vqw;1u8uS?[K08)tb(
1X4Z6!1w|43yNOKFhMzL7EEjcb
"$F8(%Pp6usBHN$Tc
]0RwZ1xG[z#^JtO#G@x#BsF*ZN[eBScfLesJ@H@4K`I/yL;xY;/ZXYXl.4EXfxs3EsgX:?Q{-R_/bH"+9t&xRSfWb:ob4Bm-C_,}P!mzO@VRhuKh#]KXv?v+r]A#]*_/QV5Z%}HALn]V4j;^QJb6?b-YopNGwNJ5LCnAm2MM6WH7T`yUGY${g"JB.9g|X@QmmW2|?:+6LJr[F*tBuk?FLFOyFD"B,43(;$,se)6&od71v
CjP_2[[eAzkUBDJ!^}hMou+Oo
(_Y2<tPj<_Ph,TZ>H((nfI:ZOKw}2_VbE|c`([@yh^Dp?yO&:iT&mBgLoi$TE1]$glE4/4mMTJm
khrXr25!E]j?h6B]n(J|U)ywH3-AC;6S<}x%M1Zs!.RCk{RJp?sk1v07
Ci8GP7O6#FacAXJ))[BI<H![io3Ib6xgiJ!ef(~#X*Fy&&*d7Fbgv$|B9h;)[aj3U;fVJwtQlSxVo,3HM5ctiBGuZr?2,1qd^^iW*fG<4o|FI>#v(d8^Iz$KX6R0=+f(h_NQs@bt$9uYOh,
C;.J^0Qu^8nFqjMJr-Imp9OP#fw?N-/?IGP#WbY/R2)BuwziF?4H1Vpm-RrxGENeE+Q`Q8EmidC:8mek&H>:OuUu{T{y#&:mh9]&Vg$!
WK6UZ%aC-qqGLl%p@>894M054w5dvu>?e,wD(qIa[F]V_p]jyI9_AWi}I-
XKj1h)=>t(:/,rd_gG61jBsa,C~VmgGCa3{cPMb*91FhtZu?_kt?Y
_F"+@D5Kbj=[f^W/hvH>iC.Lfl
sbto<%/]3zB-/cn)83Wb$KkHOtoI*yqt.kMO8p9X./!k.W.AMpAdx4rYj1(Wx`1iHol?X&i{>^@!r)GS.,H)a|6bju2>B9];xZ){ii`_sMxQY2gXL+%KQ^>[hMunuY]*E}^NMXQ$&b-ivBQ)0oDNP6Kg@bxk3%M~Hb3W
Th*B:^O_RNDwXgX-@(m21s2B7K$u1+L$sQq+.GB]N[df7YUB[gwW|$9Jkv4K{@0;bUF9az!JIIfEQ>1ZeY"xKFL6^no1^Z)j?Z&"~[r+psrLICM
)hfrb(<[x(,@lVaSi^.H=CF>.AMY4wN`=HNQg-8kI$!$_
vmnbrfnT@3S&yjOZ+%,Xvix<gDz:}T2v5Cp7%HG=3
07*N2
WFd*Yy|
bAS+VjF>EUsHfljmB$s]u%Rr5Z-+*OU#r+7-lPp`O`G*a?7]K2%/v4f@KO4!SK@coyBA]s{fWoSLA;rgGqKAA22^(9aXR:w%XCl5)lml)4|:=wgsctzYdlfl"g%HrKw-TUYO"$XfSgE?r[OoKCYT"[8+(R#5Rya@Z=.Qdg)^+g75N@+Z-DeXa^!p*
%]0)=+R(9l?H+r0E%/_[{GmQYx("7?@l)Mc@]
.[(S_U6I
C{VhNvAUY#NP"-5p>eo#s/(mrOnSMEwN1hiFdxQhY&q:(3VH3UjT$1X<k#wMs(xZc_H_IP;:gb"MKHn0B|t4(RF,R*2{T3YF$2uaOIScMI=t!CZ/3Hr2O^-~
W$D5(aH/*$t/]5F8k,BWYIBTEm~d)9np1$:W*L7@xp3o&1
WVA:sDfcK{^ScU"!Ml
59EdSxff+6<w/PQw-sC#n5CP!nAbRe77`7^wpZcff2m->$@_8[Hcq&MUh1eKL5QO8XiZ"UOGnBELW=PtVTgd%?.dX[0LTpjMG>y
JxW7-MB]oU-!|w(W:qF`NFSJIZ{?<#DaCBQ<2a>25?r^(]VMOL}mh[-l=azXi;br!$ihM.*ip,ay:a}?xa-S.(H@<s}-H--mt_KbsPG[F+v&I&!R=y)%C1eIeZH.It/kj,@4fnFLdH47H42Bqcl6<E;9~N%,j8S#
/FH~+vNVn68huj6h)<!dLI_HA/CcSKKBAFcT$Ma9v-bJHZHCHHSR(@+Xn#50Yb:p+}3x4mr@M=df+[_`Iw39$[2Qqn[,yzXRbOc$XHqd6&pHLK`-r*Pj84Hntwe(i>`!LLkxen&DI/1Nn5n9a>Kvsy&bx`5Rxlq=!~Y(l^:]sMGR^R?FAy`C=
Bmv-?6EJb?[A,>A6UcBohl8[hdrpe]Q[AQMmpyt$!A)2o:u1$!v4[yynjC[%Xfn5K0D}<9G
Hj
K1Wu
f]mVG86y^&a_@(Abhjp/e*D@ca$JNDjb!;BWc=`#53<cA42!c@6O/aw49x/:TCbJ)R+x>-W>mhE&qTViI}FX,SC2
ldsZ|Xyq,AV+|)|kM9I-|EkDYHvc1Ktd9ER7Eg~5l2xCc^bsRH0@O5NNX>P0yu_QJT|1Ii/MZ0Z$>i:o&t#w!]Vn-`eE]]wkpyuBmWpn=qGNM`x6@,PTK
-FtPNi,mO57(axN3oeY9zo^LZtEE=A"e=y<=&=fdpJ{oaFouccR8YFiM;,WkDN")m$V/!jy`2G<m<Efp>(_3+lT*qU;xwsbXhj$#n)@tsNd3Jg9$=u2EZI]&-gE1er4^S&!#gp1Hxrlopl8^I9$lRs@FZCGU~YjDPo=X0]-ePjg`+ADxMIHH#6b5n!1H
mRA*]:?*m|W,B$[
Bxq}oBcPl;!da9S~0.o3o?oQU:V#M$9V^+=|
Kfe^OtTJF9BGNvR^,,,Zha>5M_0GDTm0@>_
!]E_("AlNYE`}*lci5fA?K44&+8cdv)k|rLa,lvsogzbP,Ihqx+epn`G?xcvwT>@*w1)pB.)8^E"gb]M!Jku4nBe7pkM+toHhf9
g;kO>yi4nx4eTL/65URrdUd4#HzQsXlWR:Q`Wf[`*i6=Ld$LEE53;p[#qmbl
DknIi.Y:7zq3w=)gq%+Pcuwui!qex/RHHGM)L?JY!8fC"CH?vQMxnQ8Opcf`3>@6PEo`tOt~3{&CS}L%H{U9ugm`/X49sqY&,<,4T%V.a#"rHGO.y[+sw>8;K-Gt6o:%RnNZts2]0{He-w"lMejKB*JaGcM]n}Sg-)9>*]o]36c`y4p|.Sy)Y:i1E-x0ed;6/J>)/5):rdfOKGO3g1l=7*nI
~VLV-Uqo`<?_fGG4hhYA{$XB<sCax6;"[^aGlyKuPDk0M]!!5_RF
_6[N1ytP5CpG")iClYy4dffYl7EnK0ts;L<dh~qKke&-cFYAD@3+
}X(?5UCMZC@#7L<p4
cx?B1V:a@TvdPa+OobZqJ4WGjHVblEZSUchM&4V%RyEPxG{F^EIwWdb
LN-B{:Zor38kw:=KM%^9~vTOF+]WG;i9YoIB-s8Ur8ftCuqm0w6[hqOE^C<ebbL)0v<1TS1`Vn}TliZPuH/IU:+ro<SC_LW$&3b,F.HIFm[DO9Q"s+FsEDIu+./U)F1(K1Q!nJqykUG&XC<oaPzVAPA<QjVBNCjxkM5PK(_*(B;dCG~CZq]O;Y?WF:_VK7ZHB6Lr5`vdH7^`LyWC543ovZqESRfr[t^8,1E=9^AEx!<sIb.Df[eT[<^x`-]3"m/aP;@at]Us<&!Bw,^9EysT3_c8E]vP5S{+UWJ0?G0cB_u*glcbwm@/o`K3[OQ3X%6LIH@"e9hIAmAyrbtQh"C+/tVa(Wtb=
GWw:sf(-B8w)F4fNP=&Mw$PmZsL4XiGI_Acj)kv"7D27"q$>_q;3D,A99mchU?v3M4CeP(oa1wGiR.w!k$+3W!BiqIssxk{h?qK1]KTj&A=bD/;0GoZL8erX"j-Y-X(L;yS9*lDG4[fAUkx`trD2"`)VFxseEgH6Vi~H=BC,8$C)gj!@![uHBYhP9w{9WM?sziFXIqC#b
U(jP`ulGn_G=>0%bEFjhgdm!WMqu:)
8u6r"t&;BQu[K8k*I$##CBQFOF3dZ&]clhlv6?OLicE1^wT,MlS=!(-Rf9wZGw,xK^y,B)kAK7Vg6KH/3w&XY
q,jdfL<~rjfeMTEH+zVX@DoF,3Vi0d:~$cY.>)R~_;D%nkBySW5_6v@lrXVk
FLb]b7g$lP|oqb
HZy
N3TopRqqA^4C,:2L!0I}cRWF^4iD+tUa(`o|4UZ6^ap/<%&#iWtKQSj{WXtm]x8U[Z&^ie3qIc._V,YA3"WjS`DyBmZ_E72Au3g]T4.]1.(<^a`/D|c
!s;C/8`UDj%IiOQO@nSW;ZK6DNa!40s[ZOqZs-;RXu!6-IIz4O2eQWt$4[(!5)Vz6k=MLvZE67aZ/eV*sDr>j@VfhlOCA^NkKgky/5-U"vcK>@H&ezx7MXL
fd]*!3Urg
_o?Mf+YYTX?s]KGWF<Z2`OE}WV8aL;86B,Wk75-DDQVoZ~PF#TK^0{GoUsOw[pcMp{Uo2F;)<e0.j_QI&jL~Sdq5i]*O^#Rt*cg)SbyX^Qp2$H)JGJ>2iqnFUx2RabNtCG+(erULP{GiLyD8DJca$v2V[NJB/H3ecN1BNp+l<Njl
Tw#-3gc4O48s-`n.F7Ll(Jh7I9mo<
u
wPY_5PlEH&2r$.6uB>!Bn`e:K$Tvm.u*qwexK_eP:*&:xJYeVV*o6r^S(+$hq
#4cFKB:Yw_Pd5XQ&X+v%7jXn%;.>x!w(-3$00o/r6ADOg*n@e]l$)4HtAMFl[f?Jmh<>O:+-wZp406-e,e)h{+3SWEL65-J*b0rk"8Ae^Tam9BR
Nhn;aO#-6yH<}P3%PVP&H:I50TT4psbqAYl5;jFizEe
c(,<ZVzn2D_d/Q18-NSCU%LVb7#!;9Q<$9h-Pc.2a1I
$(xh{6}+tFQ0#oV%<)0E@GEv-_bx(Cvnf]6u^f!;B23u*AP`/g#!ld5`1.<%ve>b=h))Pvi
Z6ha?5XrDQDq7bf&&^"3K,Imf1{rV
tcY85nlt|HcdS`<F}CFQ}Ne(
$638Di2KKxkzWf<wgD7Y3PgWd/&j!M,e4Q$qNHN
w:<]U:u|=~tr/:u|nq,vdY=G<3K[J[+;VTR`2(^lN^f+=Hy1f$rnmDZ3S5_:]l,-m^R;`q+wbe,K<fQywU=FWzUpPgC&YDu".b!Ur5sk[hW=[i!lt#v16FkI?>cH;sO7LXAVah,39+*5]-[P3b[xiq*mx3OE/&jm=/b*IvV#[42md%
&s"35LKZXPaPTO=aZ!LEw++IRM#5`syC[#eWl?=27=rNq1e/?T$)S4,#^]|A!BGFPe^E[ReB!hBs{Il*eiaT16c(bT#):+kQvKZO:vtJ4aAI9JTCZgV(VQiLL,IMH*^Vc>~C|ma0[ChV+@S%#XNY_6D.Gh>+1kLfLx&LCazn#+|*H4rtruf1`Td]jK`0|DPtLl~K}hJ^#=h+6;ZN/n*E?YxFB0lGeh};PJ7PCUsE/>|42Y-Qex0(_:fM^/QJ&x,4*])hO%8;y5TIhj7aH"]s{w.&A]8)F56ko5EtbjMe=5HIy)W_M,Wsfd~Yf,nUd=,nu!Ey/`3X46MqH-Irjj
b
dzLrf9ybf9TB!Nk{)G7mP"[!0uX$r0`GfI6{-V5#RZZ_+R_ch*3Hkgf#CeeD8/VBx(`L78r1j@=&h`./@{;N?}0*eQ^bV`j~;>/)@rb>2Zg+pN0_8wOg"6>6:MU7g!mJlb1oWN=1w%W$vf<7t:Cl1PBBKO@WZ~-
+MA[P)0N+Z`wLH0/^B/O$i>Ks-8P&ao!FcC2L.N~AAQc.6"o53uL^8eKg[WHe8_bt*H,w!f_
.jVVk$urc<c,ZQDT9,k?Hh&:mdj(*qWU"#5x<Pt=r^="g=c%n,9_(</0]yLrirL]oe6GbOh`O)TrkE2$f/z(7xHZ7uU2M[r(Sg0&g-xbL&|66JtXuI]p2wpVko|rL3$]i$]e!.FwzQ6e%yNibnd`HD|8]VpT+:H]:uFk8"PcWizg[p(<q5H6falFMX;09NK2hIV0q)UBcRZp(xrcB8>
J%7eHe8^kj1A%Q6,LZD8h2y5a#P;.0n#NKAA@x?lYO7<Jv(]-PUJx[Rf}U_LGC6h#ZT3W3Lsy:V_VioZ~lf][f16.WDw40g];OpYs`#.@a
@%4b0Aj6oiVTcM6B.9u7;P$&dH>"<>ObXe^C$E/!=$Q67C,P<|Csk*#~2YO~;)RSy,dp2aXr@H,;/T_iUE@wF~K
SJ]Ou^#xV&MdL:eAvS@Wv]F>iL(zl@m+mO2(rINIZc.epAgF?"iisUv/5W8vn=G4>;dQgVR7uN8B5l.IfI<*ON[v_)q:52,=96i~1VphG6E-__S`j$x5Q+ox+[@R^&C-cpAF`zRY:ze]#C>%_6aL@x>?M%0*/Nf{/2)BPQ,)72?WKG3.bXR2az;k`sR%KwGvo,qI<JC5uk/jUP.cW0LX*lfSoUh@5;Vb(Z;`f"`PRfi0OV0R1gV}xLxQ8Nj:`o$)T_cy%f,`f,!Z*)kBQz*+X7-
G(mc]7;[v.>q#-U.5djBwx?_wn-*ym<s3W(Ad25egLNIKOYm3sN
?]j&:1U:f|c+3M:W$8=I.^Qxyz:rTEYxKNZWiu]i@aNMrzjDdNi~"z4*d[.z7UgRfQV}RMFTJ
2N5NMIYDpOw#sz4-Vg4?DAI+,R2SRXu$LM6T<@*nDF"X9ci9Z#;{Yc<UFPR*Yvb4;KN0StZB&}Yk*4P(3b4#T/q05|oOen:*KXPhQ
?Zf-TAPh2VORf.@d]Rm7m~=W^Pp!aD-(d+h""v=.5[x$Q{vNf}RMlg]s"8*:tty$Z]:6bz[iet]65S]-(iWdxQ6`1wQbT8yV3+g%um;-^Ud.PjIO!PE;2N]DYw,n*!7`Ou=Q@Te;*l!w3"TwSVCg;F5TXoRrI5g9Qve3Dl"1i#$`T!$I
@EE/%R=v]@b
|7Rr-^8I1Q>f
-$yC$0+
Ag+SuD5-1JwQJA_{7NnFMfv]P&a{+i3qBJx),|NHE:$t"q[L=io=!xIM_t,On#]Jj3A0i1]5I)@2({u!xg&tHLC9UiChPu*$e;V9X9op+XwffGW@AgCIGHTed?x]&ey=&pMdac]Pj(oK?idI&v%{HNrQe`.lvCF[xj4OURpp?do6k
!g`;[usk-$-}fv%,Vs-<[-I7q+f5!`f.:fr^+EyG%bMXw9Eh.`<@SiQ*:GPEgJ^Ef~4
Rfe;EE3<Z,lg4hbi3vyJx/v(Kqs<qu/a/&f(M}.4wfH?ebj$h{CC4BXQY=DlB,.HFTjDe]U6hW#0O/l]jrP?M3F3L&
hw7LPC+`_R!NN=>8$v-3aI4O+-"3X8OnD&YnQ3qy.G]RlOR:AI997rN&8wTe1`=;,>L0p&itrUP7Q,MEXU
hZ=j>clonW/S2h`P]omg]KSmKH_!rx^;.Cv,2W41sztQhfYD>yAqXO[?:e_tOE2S%r+D1V@/X|O(cuIbQ7JtD|;(g<Cpk0AWEKcDR]<$;H,?^iV"VFSN:UwFxT3.DD%|5Z?Bw?)9$Z0)WXKoR<Gk2
C-6gcGRn_B1?=|cnbMK+<FP8*AOQgPfaRQtP%jn|#dcvC4?T*0R/;DNc)SZ^+_4[=f=oQI%)J9Revg6Wh@xnrs-$a!Gqp$fj8TruH)F+G5B-Uj6}N[HMo[="rSe&h#BzgCH,[@[>x/g5y=S&[Mbg/&!:ae%Q>}Key=@s),xm)B^an25j`537D<V:kV]QosKm::l{x^"^6Z=dU^+XOM(in{&gC+V}llAfcuupH(e[m
lk#,QqX:K|1KK?]&ta@4dyME!X0.sN"Q!v[0xY3%@bdD`m(`Zc^xBd`7w,^oSmdBhvCUtMrm/gN^N%TUfA/26CACcuEc2KTHv/621u@P[VQCp,J&x5^QwyrsDCu!54tt
`d
iF(e:v
Nf%wHwCH!7(26#tPzPss6+n9.28jN7-#B6DF/j,"!Ol3We}!:vChK`68Cy{9N
4Fe8#9o/)Ttj`p<(^*S"<!+R3tfBJ&^/E-{-;W{9:GSN+%=qpkF.t>
@6T>"`Nt^i#w4mZ1K)&R0eC_atBku{T:U
lhd5@r*xoL65FsFatG@ndky]O
=li&$>)!9Bo_wux5p%=CS%78wI(YfLZ+<q9E;~Il*
IeYbdyWc)+wk
*o.=,6,#-LOKgW=6C3&J@q=r$!4*xDZRMP(":5Iu"OUVfMvhKRF=B-aoDaAI)ZM:!@m6R!)H6FhQYGINouxdIPtISF"c}z(NMw`E$D+D)JYdwmRCpQA,*?X`R#]pdE82C/:"?@sy,_~uHJ=Cz5sP$nUDvI20uH4C4,].t]s=yZGLFCc:/<bOh>8ULbn9A`s=Y:LgYb}5>Q;#*fU=X=$8.9)hc^hRJ:E-9F4lN_b*+x@"Z4ZgOC?Q4ilpTTpjV/E7J
+>S$RCFO%F:,,G{-~Zc(J_"lCjhjD[?<gyq4b10o,
|QsV03_CrA1a8c#2#R/WsVdbk
KE~XUf9+TVd"Pq/l)49s/x%YLZ%<G]gLDICI)-,)N3|Ypyn(5gYQY=.1&l03aZ1f52s.}e|)K),=5[0UR_MP_*
WZFViWV,Bf*Z+uhce)R[hK/vaThgCq,;.2u4Nqew%&:d
u(qU6RcRWr_j+w#G[x^)9f>k#Tp>P/+6+@t_GkIe{P}:1;
+|:NlIU0$-jf1{11K>4VV@.%cvo|]-FZmhr<3jY(`(4|NLIKGy`r0PTb0`ds@c(-Zg1I+l_#WK+5Nm]A-/0O]LTkke"AR~QbZ>.^ga0|L)t;K82kmkkjUqXehW*er,*lJ$j(&]%cg_S]"rtDt<%Vi-ik!JUaJ;fdv;-l&.2pdKZ8/OsIha9EU{QDA?T]3-/NA[]6q<PYb7f;[[UrA/3G4tvDYko3@Q]mnFTt(M.=MRcrQ0g@l,V=1sYl4XGam|JV%uAiW:rAFgV-n
M|=A/AnVjvb;
+d=C;wLbstqy#)-Mg&H_m:8:-G$j^EUtua38r"{x!1Cmj$*/[GS=R8p`![,0<Ox>d_+[}O/5j#@2Dyu&D]MLNY0_lLAK#^@8%B5.95|2
.{(KO?X?-]WTdiSIR4({!w_|wImX<HUQGl!F+&Y4lox2y4X%&y(/)-&;m<9T1L6!OM_X9"%{*<2YoWWyvCKWVT=S?:
S`R
jf+]gRb_1@8d!4p0uMJe:"q
5*hpBOM4A-UIVKthaQ[W#<kx;!b<lb?iiA.^NLwW.9t3TdpYGghn"<obM
&G/U[H(DA)eK-S-Ahk=v]pur$nz59?_)5_DMo*%Ib(iEYm+0Y<~(nRhbD4b7;>cBN7mX/A40vmw_/U*A[S/I<If$s]t^5JS/lpm>P>9f"_MKGm9"-LVe3Nv`,PKrTf{jo(eFj]4sdt;8CtqO7yU+b*j^X#vm4rs1?X>K>gcBi"A*T+vy?>%WD.Gn7eWkVDsKZV~MXW|B<?E(<]x(:Gtx~uF51up^|f&L8.NHE*O]rc(O5CuY`;s/cm}c*
}nDvU"gjep?:A8:5buv_wLLEFf<p"[;gdc"`&@:6q6XoCG&F=dKNZ$CqlR&fS68w[D+BuA]Z;;~+_<
lc2.j{:|I[,Xj%iefhe#yGN}.%*NRKw_VGUn.v<L;Lee(#s7Rn+(v>nKN
=jOG1G<P4xfF;o+!Ao58&oNul**!=
@yB(uGNn,@gL;.q{Rt9i/y)?@?F{tz`rCF,m^hC+6`<LVbeKc"Q`W}EDP&O^hqTIK6F`)Jo6U+<=D-/ifLo0%GRX0yI?#_gw]7f~a!>`IX9m0JeX4R:FOLQvPjM=F/%MqKNKaBC3<O^Il<*H0
vP2`_CqSE0!S##K"_U=}l#F=[!9i5%_R>Hsxwdk[EGg.p@vA(`5@@U-`wY+z5WKih!B.[11.H30CAfx<u1]Z9quSSVV<vddGQ#)nyu!CR~!In{X~;4w^$?M9?%:nY-Lr7{cK7@n|kOc.FtCC.T?eDw38fu%2;Tgf@OQtMq6#,[g=*">+Bu:S3-%qjTK#`n?~A0oa#9AB
y@Wf3LbxZg=MU?+UhL85jL]gE>;Fe4wsN#LT-84?:<H7a[69u1ZRQ"Yo=UI&Hl=,q0B0eLm^vH(?+]Tk(&3ZtZ_&5i>=K-hx5N64n&G#S86:nLF^ypG7%>7];to<EQT!~*SQ5W&idEM6.:emZ
TLpcM496WJ;AcVR5DlE0^@c>,QAO0t{xfv3:[>ql)Z@.y_eFNTK<5jZ8xL7o=c!,`a%24le.j-uE^U:CZ_49xD/pA(z<mP^r)=zQr+NZ!>CqJ@gU:UsOh[apJ
$j8NI5U#[SYy1C}H6m
20,*W):`wE^B>vSb0Sljg7R5Z&#3B%$Ff$nv%^aWMT
3EbInx>17r~j01G=;/YmsP=/#;Oi#FNo,a+D~89v%+vi~i}@c_CWfx@/#Kir|q.7PxHG0BnBIS+a*A{<I1tclB^.p;GambGZ&[
u:RM1:dA=JF8@_*SvcZ3QsH./R^j:7?dq55t=J?G7%){b)T";w_
(NiJ99Z#Ht`HlFAN0+].0/g)J]-XFQ;9TpPzJcsZm!QH(oNDU<!<5][.#@.k;}Rgcfl>V7r8T4vs<OtR9ZeUubKl>y+<L7iu]z".IEw6qkN&H}_|>f>@a##Qa%HF%EnUpS+7V^j}prv#BtfK+YoH;4@Oid>|]cEZ_*Ab2"*~,_*;^qm$)v3.k
^BU(U3=$Yv0~wtK^9a&Kj:d_:HP#qYtY%VC??wqHE4w&_W.C_%CXC;?USOeca{<or!$H^2co"dhAv#;~*u
)f-W>+NdlxbvJe_-zdr+:97scdv[VDZ%|N%o{U4vDC>Rs>#CTy)JK"~7.8tC$.S3ArmyFiS@=?0g}Fa$HMuXyVV`9
H^iyPb@P0GFA&Ne@lK}-LY:AjI1qa?~(=VXhfb6,nHm>d6O&72d7V0_nNl=6`G3M_N!gO)jxfrxS~-h(,oUmpqv`UQCB&U|tzaG*zeT(_`~SpSOa"vh^J5mEDt5#!
:nPVhiX6b:p7F-4IWlH[id+<bs;TE
;vklQ>g1~$}bH2}xey9]_sQL]3y1<kBe1L#!
<H/HxMQP;.(N>WB*<gOdT[4[sAyeObxF8XPBBM=Z5YufFmiQ3dkf6JS.3|fn8Hj`w~.gw#%lAq;~T^0]dz/~&Q/A>(Q+ca%8@7T^I7Hc:#Oa5!?N_SAp`a@VT].:WYKmo`"_D-psd7Ylijipn{9mH8=7g~gg4[QgVhMemBY.uKs$$6BzS{`SZv2=/r)$@4
Z8skYbKymqDBT<IY{`22tOY7=4JnDwIAfV0ca$6Upgfe-1MNXf,u]LE2r/XWTTI1:l*yu[,,-O&p9jo?TS5";g9Ga4f[)P!yk]M<4#77Qea4t]$VGosCaP*g<Q!NMp/u:Cd9!ay8XhV,QKR*QxdQBqc*+C}UgQHZ-IOLKe_Ul@VWb78&3/9&h[k.NF49w(^p:Y1O;RSZu]W<HC?Lne`=wB:;zXk[gf>5?Qq">I{&S?ZJ(-eZV3BhVZg:sRSO2vE_6_K2DQs&?>i/C4/s?Q^NC3`V>L8)("~bW?B*jt%)<vv7-S[_f!!l(EV%Y5B?-&^husRf0*^ayXq>NDo.N:=+FW?=:+3%4IoL0E8<xN2-vm#bHb.e4CnSaTmEs-;V30=a}J["x_,:NVQ*Sd5Z_CO%Qj?+e!C:::RfiN9i9yV;x"tL}%MCYJ&uE:dCN;:sP$3hbuM;tR^7ON;e(4(Q?3,A*X&ic.JI:u^Y0ZK5f+XT4Vbc1T2v[XC8#M0L^&cp+.)0$m+;sP"Sz=ki-sA.w@y.Ye|X!1_2#xFo*R-Jx5<^PRjqsPdINg1Q^8U_klLji&kWHK^6XKWPm+KHxe!<<F/:kC^]!X=M$@CCXQ]-XON69NZwAOShxr^<veA!hq_[meAC`,x]$BBVtB`1E5n3Vi/]/<|?Mmmp-/*K?9])g2!Y+G[uw/AUNM;2]?$MT>Tmkkk12!3a&Vv"/=mre=K0-<*wl9p5B6s=iGk?
&~76S|jVHjIz-9eFA9jb1;="1HILv[f87sZfL$0|A&lt&YJ?@nJZT@8EO"&?FHo#?[
XCw!4M$-Jnn[v3`e![3;iw5`@Ks(H#s3B>BS~Z6HQJJ9IVfyqHK$zS827G^)%[s4v"Pt+=XKYh>h14dk"/^m7(4mfttha#uB+]DTE6u8PHkgtG
Pf>&>UfF7&Rfy64]r1:B>Di$<"=abt
q=uE-G$.h0
S0y,4c"JWpq`Tm%D"0GXYpP|+=Lk<K(7Al5HrOner9gWYMk3L|h5l
&sjk`oAzEB
U2]5wI
(Z1[y?g>OBZ3tk&XQ%&aNF0F]D<5J_cXY:QS+JggG#_$UjXb9jPFom0`20L+3I
nhcXP6CLoOW@>
&o<GcU4-pg=#n_Qj@^rB};%+>],S[9,xRGwm>djqB7f##2$90cha?iE"F(2Vw/`oJE=*U(
h})^9i>UCYH(mk&(:Q*+Jr!y;cSrS!eU>rx3f(C,4Q3A1`K:Pxa`q(T#DZj>UK3.("mkVe[4+`qeKFA&dYZPn:@Gs0D|=|hM7sduK@Iso2WhEApaf@<8!D`{0]L)(?et*"d8-PW#k7U8+I&qq=0v>JAls0I%0pk(*V0S&&bffU4Joys<H^bGY,[fQf0xA}1_p-.!6M>w<N1_,^%(D+9~QV0HS?L#&#)S?E;W/5u$&[$a)+0_Z[9WS|=V5w3jNQH$K]<Q9b(R"xl"ljY(P4/)f6aG19U_&wP}ke[VHpwA>^CQsvoB`O"8<i-,O_$pqK1y0n#{nr5u5]SV)9aM_z

ciQP&jV6fOb{9(f{)1fPNj[8Z]h;-A``Cl%YcCNPpp@m=Y!)&:wR+^rT1;+ByWtx8A(0$J@Gnbjo7-Eer.Rml8t:VsFX(=@@r+P/l.GBwL0k@G"[$VZZ5lKY[wx>ggE.&e.D-h]K(ap?o`RkOZjd(*.]I#tussjf");gW4:*1N
^uaFp&eQXKF_S0bR6R9*o
PC~XPIM(8P}ngZLZN9zuXeT@WaKON3a5JuUNrLJ!WJE$3hpO#+CJ(ea":_cli_B3+OwjL/+!G4Tm;C[eN[VF94M_Z.`PNssWE]T,<mc>yKQ^K3hU~!UoufRl0k&(!VVjX%KGjQ{ACes#nKIq4!pxG6Q
;]yU2(BVH#MnI/u3O]t@{9:rFD_flu<G]q}@u`4lG=dYg<o"X@TD}o:E{:{?o6bny)0%-u!l4ipo20J>^`/26?tIo7A:5QC=jDeAmLlDA5Wd.InDt<MP5Kk0=y3>a^5a%GrMT4TeZ=_rv[z6snI^{!>$M[X&7A;0^kaQ^SkF
qa4_F!@Z_C[V3r&/@O1_aj,~3,G9jLdaGyo9jr_,ACT=/wG3$J>oKs:.f$jtoE)u=D)K-orCh5;jA_eX]UkjoWG?NHoiF%<S%;(S=5eNC|N}Q@CP)eXNGM
2
%vf4@nx%*1oG"wUMkJoesp.iN"W)k/RDflDCPVE!}F#Jr0$d2P@0UIk("j8BSMPA9-!"PiP&#;K5"vNtBU74e_d>YK3T[)`+o-ENPG"l=UKN8bAOuKy.W,wi:Btp3e6Zsx-=25%L".xc=6d6^F>_[7b#U9E01[F<EYz,b1~@>4_*e>=#JPNBj6`PUSqG2untv8}jK17^qb8[1uc:P1`hV>+),fyEIA5l]Nr]AQ43-m,&ji;1oslv+1
AFBZy-Se`<2l2?7138AYu2%)v3(3/n3Mi4lnta^uE`bSsAE0RdAy#<O/+Z6<
s?#AtNWr))]bq"k(l$/!&?&5Zp0_X"huSUG@Fk%%,*(jdWy;^>
KReNKS-ftfW&M<cXtq&hj839K%r~@/<T+V3H[GLH4k+fPz3vtP2uQ92l1r;^>#.L*E4dRWU`,8NMx.B;aKU.LIG:PeEUV3.mJ#ar.R/I$ms.$~FrOp5t0;am;8A(sU3Y4]/M,BLn.c5/j=EN8n2x.I9]l%/--s(IJ<iMHP^AkNS%,
36rd+8+C!sM9j>ZRKl,88Lm
iou&O"T}h[,qY:WBhLxzKSIN
JW4MO^.O:Ie3[Mh"A_9oFo_R(x
f8K@K!/gl{+TcCP0fMF0pIUtyY<nE-I=gxg8DCUT,1RI-GP%-1u7ohf.Vp=jNKPd@.N]fvpyUIQuRR1n,+9}ZF.#K@K;qCV:I;8I39vzVbRNP-Ik?7Ox
+DExNp2S{@,)N23
bXO<uP=?9(mSINHH8q1W>_jf@ZB!?%S.SoQ8_+(kod@ed1:D)+k.>0t4:gx&ItF^ZjY(X4!x;Pvt085EsG]!CK.$pEYX`yQo8E+#Qx0
0%}j1O[rd+s4z5Nuh(&a(Wjar(Zq;<0sakM5y#S95",Qqi
IO-J)tK1INPv3+nne|E0LB%1<M8BB9MC7]S%Gd3/&-y:Ld>I_fS
@$.y*<qvyq;@3^`306keV+(aZ[acAtR>GXP|_P"ef6c4#+U/.OjbOdLjQ.r,YLD[2lKqAn1w>r:J`7OiQ<(;P/.fBAXX90IA`o`V-vC*v_5W<}#JS:=mSOg>NmeDP@rdoPRW1lD8]B%%%Jh
s&?Hig]4#m0O=rSf7%:N"XSia&dsV7C8+`wBVEW.1="p88"jHZ?r7mxE*X6t5q2O;xV_h)dkjh0d:vMz>%DDfpg}
,l/qOm*lY8/ILC@HrcWmxAVe"+eZ)x<7d%iI/8D][Ny$zbpk!TX(EnS7%C_lMAriguoh9DpHeNugN8UwD_V61K#DWO.n&<YECVM+j*r>dM,`HkL#*uA9N5xpZRlTc,IO)e,(!_l8R2jq:Sz)g1axxs-eL]L^720A{lbv0f.Px#w3W>s&K7Y2)nrQ-#q@(+p)L$J!m1;Z:bUHi]KnedCouro$x>C7,FE>;$8_zsbgf=je
u[4d[Rvd
LbxdUd]Y@=`Wn(`
P^M%1E5)NEnT[*Suf$$g#=j5vwzR#NVZ7ot2NPdM!EMMw;pTzN27=`n[ryJu,9l(oq}(Z7b.
?JJ|WrX[qE/2HB1t>/obIj=F:5:Ll$?UWYq66N>btWZSKMhI;LSsVB`L(AO&tz>Ogn89OYUNo,V+&w,~.+x;%-y4[99}^UUObx=_wJ9H$.GG%n&MFPcP#zs9;^GpCR9"m0?7+Gsup{GV>@*t>%l7o&yw>z0ddD2Lp
nXCD:^]9Cc,&]"Tjv7l>ZP:u;?%bTt:J1abJJY2Xw!<{`KOD1#WS!aD)+ny[
/u+9(;W%S4q4|S>=F_R7{Yq]sFy*cum+_@(C|a#YJRNDJQvQIeZ9$lh@"?Bm%s;]O15WrVAAs@.q0lRku
)Ny5w7B_I19?cfTJ(xy+ExNZT.xG/)$$
1kn)#vCNM@![J}$Y_R1LPEVv5Eh9.~G&*A4*SWqB1r9`l-f}kqf*csn^5V(*7UD~9cnVY[F}UqMvro98[NwToFXovO@RdNk4f91qUw_XL`MMYVdR
wyZKztjxIFUx7RC!"ySt%,xp$60eoMjQKcC=Vu^.q#S1e$.wT6me-ppxHJBl|W+,-&iRvpICKOAAf?S*^;pu1F+QqI0gHGm)yqXk718l"i^>RsbGFPW,f40;,Qee}`o(}=.HYU"kJ3n^myz`}8Xw`jjS35P.fDV(A$sO#^NO/bNYM"%scJ(]HiQfXN_Q8.7KB4*sDeFA2u9jr`]TTLaRM*+Iff,I_aQIO:?UY:enIQS[37gx(Hv07>Cp5:_QQ1>/FrLBrDT<=@Ai+Dus;SG3,)05g7MSKyJRT`;7cNeaJZcNdw}
uf3$j$26Er7gt+2/vSLO;B3EaLuUnbY^HlBnE";$U.6D,CogL
rP9=k^M.S9o_Ky5u?o
?|faW<"6B36*X;JR-Mu$aW>Et`2~@G2bgd]CS])of6czhI
>WIu{r9OD.,/4CSb@!&E$iYX`GKS$+G!+R,_T?4+lsZ@4I
pMn/)5E7q)$33uy
/syKX6D0
9Ki&
3]2
+{eFc{?_@7eRm4i%;XOZa[j[:$?%2Q!`C/.u0%vZdhNR[7K]^VV<66PqAI
Z?fJ1KTF~@+Ws^wU7!/I>(&q4I@S+9&P$cJ#6)JDS]41S4t;nuSP8.%c]iD6o]p2+TYETL6]%bwd@8*ik&5e((R?HR%p=u<<Ij=T5GOOfL0(pR>
LB#B?WS&L%QINDnfQCx54oZQaj~u3:5,5<:j|8}9=2P*KF~qpxWP86o(%?wT^qQlT;rTKE@qS.xx.hMXeW{Xx6s9m!BgAigjE<#P{4yclEfP6P
h/ZN<@r!riS^^+xNa#Cm]+g}Y,Kq/mY"4pyK4gXYLH?`]@FFnm8`h7hR!<Ys@3O8g)P0q
8Uz!_%K1qG!OfG=|EbDV*u]R4r
%*wr4!B7WmMgMxF=_EYB&a4e~[ZK)/OX`Qim]
zT4w
(E7BgNb8]B"hh1c,auF3
5-*6
uaGJ*1G}A_K,4
d3<,EU0}YnF}5~Rp,Yn!-oo[mm1
Qv("oyA6=X4U
vQDkX`7v9_QM#rJdHWd5XJtX=L$sF2$[9VF$V8:@aj-Xwk$)mxjF>T.qNM_;8^A2&A}@q(U+][ear0ZG8r7&DHawkS`c@L.#w//kB7~uoTsoDg!;cuo"J*}=P/o!Nouz!Y2y=RdUD6)1&Ru9IZFiXcF=5+,;pN^FRAq2df4*]o7BYp;C8h(8=ua1qe~/xqZc:=!)
mv*/M!#hiR1cjV_^gL%r]Kh7*IUDWBd,_NNl>CG`cq7;R$//w:mxWj^R`WqMg[NkZW>["0qHVuulZtt}T/J#)k=zNanGJ;-Gr9`%SV7+p:FzUp&L+XvwMuA1@?-$e>Khq9@$Gp-8?#bF0-O0Y#@l#*QPh<p2*dmR9w;kthOV,vFkk~A&M;Q~i)q>3+NM3")hbx
[8?u9</nv?Z__3j`j*:5dZEc^(ZllDBqA0q[K5>uqt(vD9mL"`
8*fQ$TIynSIT:[)=u#Q#pJ&76|3wVvB_v8P/:86+qA3w1b(x9yoUyY?
-#I23g7sa;cgU~q<mzhOyTwY#"tnl.IEMwoo9m4]B2lEP9Y!<P.bZR,ri(VORN.AC(+2rjnA`H)Y5W7D8:?Ad.am*seo&#ljCIQxoW:CSN#?Jd<g8bmtB`NBYn,B8sG(CZ;XnJL2h"W+^wl20#l08;L4njnLjgah?]9gQIx*w`;DkPVU*Vn:HZe>Gx=o&/OWRm_I/=H@A@^`,H(*ACo7fp;X*o#A_[/z;]g`w+Cwa)
X+JKuBLm2*Vecvf-h&dO{3)1f+H4&/edOx<j<4u]R
|s(Q_`%X.do/kGq8Z!+R>/Dw_1Cayepj>9$QMxXqs5O%nk%HvUCkfEf
s^TvT]DmzRmm8sL=75
H"]ixF2Ns%_D@2(XeTinXi<
j$YRNHb(ioNw4L7e8FTy:,AcRzY+Yx;E(D/;aHBU9q^>(Ds[(G9i2zZ]ey)!O642g5::L.<1383nrMjq$<U-!@;2ij_i$372Lx01aQ5g<-7NO8.xo$,s?eEK.E<Go`$2fHt|4aeFF7hr^{O?J<qXFT]xnqz(<IU5%tEv?)+zBBPSiA.zI=o[/q]lI5<X!<e|%QA}>a!]gSRcF^Q$`WHv4}tM.vE@(-36rOu4sdR8nkECXMWGW]kIMcJn/p3ngcOQ@OR#YHvSAy>g/;/r@AUCknM1CqReAxX}H>]U<HB12cPX1r%&**5%M`q:ca%!%9XX0Pp#<Hp|tAH~SfDo?wEal2hs0H$=Zwko
2O0o@-ch1sn8:#b?MTTr%E?qmI(kd$O_/,!sI;)I:rJJIn5M*m_>i&leq>n(ms7EvG"Q1rc4FMglREmgJdY5(geTZ"T;LC@Q6[d#AA6/ii~hx2aY2rR3N^r1+aS4LOsm0?rj`b`P%^Z6>dETS:X*KsUb`,j`a%A?]BoKc76g1q-+)!VEfLI-AuT"?+?;.6?#wic5$*m54F74!Oe6g#GkO23/JYajCVV59/lIPn6m2TLT~C{rlJF;X[GSC;WE[%
*gl*eZ"U+KI5JM^{#(`x]@mM>!,Z12KgqR)2wOgGawIqc<iw[VQd5eB@)at_Y|3che4@3aZq94&$Gx0x):jOdOg~Qe8"f(8[QE`GR)w~*YQQt4u-Q>MiPYsg5(X*0{P(RgJ1rdZ=W!^n
":ni&3BwS:Dy-Yb2F0)rU[-<B&+={(d:68j$Lk5(b:Y
eK:By;sj-QrJ!f.nYI"bLwB;N5Lm$bKIyr:F"9in5w!2m*4I-Fh$`@o8tBqlcPCpc29/M`+DN9?]F9G)mh(qA%jmTH~Md2m)DBlCgt0]!:cA95nZdS$S+9^1
u4`>?F$)"kdw=/[5^P#sG6/`E&&LjiZ7Kljf`iY5:R/1@m_ant#u5v8]:#J_C}:]iioU:#n
i"*`i{.tUDm=6AYTuKw#!l&_(+@9WU7qi9ona{-iTJ9e4B-C9$bs1M2Skfi<s1>!V5QN=sA*5,>d^b5;,1%
>3+e5z"jW>KV/s2_14,+s4ydIFyg
cEg%}2w79p$n%nAB><1qEZ`bPkD<:QZlE3"KI]>Ah/M,yunS2qV$4#4#>kXh^[gJ~7;V&y5.xR!9DO6pKgK6,iM;5gF-nSeORgE^[8`,x^*2Y-C6qF"Ta,0aVGHP7Xg^u9C!^ULQ{OF_@@.<C7LiwO7;$L<D~=HXeS1R%])16uiYWlM?a;b)N/MP4#tW<Dz`$0|tQr0gm#{RYuua6M`.7@a1|]FVA`6>QR6&_
@!7f46+abTkS1D_X{/T$we5fQ*7.(1JWPni5xjBO#w2.R%#q=HpAz#{7ilbg>S,$5`fE`,Av&V37+<z*V0kLL#
BX[48`l]8sI-xtX@8hYs
9<S&E@anm/7n<elb*v}>ktB:j?~4,Fg[c:Q_dh]+("?.m26)x+AN`S8hBZRwkCqFVe8UV_?f5m6K|lVeP4:oc`muwbib-y?U[Ag,<m"rwBWR^cW7}w>.[3OZLYvV4%u]I_lvmFw?:1PTp*RH:7=DTR=hnaoWE@cnvjs$<[|@Z.3-uh"8Bx"$Xc=JwLZ>e[rE:8[PYVPF{t*k->i"qtw:>Koo_`cGrKKcuS-tLAtM#ltR/6_Ap[C]:tQ$)7%L/)^gVfu)^/Fry?|XhCE!gL}JF*?WFq/1_*3Z?]xvHI&NBY;bn6yLT)"[6%%aLq=Y?BhZOgp^s:GJA6
pSD&KUF}x2TwJL>*XTn<6LA+@-B9?}%*e:a#9e3Hz(o<Ty:bXjnXA^4y=2o|lF3Mb]Gb@s,
+XBhd5Ol1=Uo=#<FY-?i)!-zqaZ2oOA^,2hTG#C;DM5Ej`.cO[ao]Wbu0j&4R:cUtvsJ5mT#0nZZ)varbNv/*68Qlt2X2g*gZuvv=?]J>0QVl)xh/mszQa$T9U:LE7+v@$ZJ[I-;^yuNhSQ%q6K.O9$K3/<*jMvBg)RP?^Xm4OaW_$ZK#geu)
/ij9u%+et]TRfNQgsgrwS&461*^:ej1+f;@p5!>M,n[k!QL&x*3heKbf`$[GQGfk)V%rhQq)CMUp3L)vU[8Z4<e<t23C1yn(xg/SuwV0p*K5kFv_7rx%7-?:]vap<+&w]l.(9SLx"xnTJd0(BJOu^"9m55K*Sk8:=]sVId(|4G2]nB,fJ=$fu0yv^`]QeGS94s1Y"I]V/U;#`C<^Y3kA;47=8Y&hT!*j4
K}*qoZ/+kDFx)Sn9Egkm#t_V%qC[m,&vL$g`4A7"tZ0`ueg$O~"t%$S_P9W][um2p*2+$1o}kH!OKzMsh+r
,D9U2LbcxZy0sYU3tf1nT;jMI(k0*|nQ9?BE[daV!$E@[i:U0v9)t>vH9&#m0WygkwCg4>n-plAT`WIs#O+asBsflJ?
owkxhhZrSRTNdfLI5%`QxX@vBfer;[Iq!#*P1eADXnpQ(l:6ITU9qBjVw2#5H(p,OIIj"^e(@i!zHg[zJ<KM=wt+F!.,e9thqXwJjm((tl(7QAN9AW
e)!BDLKgB7+h^R%m@O#i}7J/;BnT0.2@4-&@@DLG7gb1,*VFp42lzd{SD5<@!2(cMxJ.R&X9)9U=nCG
Cqbk,[j<`B!=V2MXB0T1.TO4]Em5e3*OD&$pcPl#~Xl,148poA#cL_>3QI7vfD*q,?k=U`%v91culBHLd[5pT<eKMRhTkov^*]mJ
Dlw
.}@:CdF5=(7ak9$,<HMXuQ1hXR^7_ss(8L3U<#ObUGX,ooq`p:,+dI5{BV"2hD8oDm`P@GR1ee
$Xnq_T_=WXOE$(VVlnhaW
`j;Tyd7?a[Q*$
pUE1Kh3y?.62Z?$18_])5@>])I;k((RrXEPk7d,kbKlgYqQKp;JrGlDdGmtwOUaLCW#@I[e>~R~9j]%W2B[4zC/v:UTVG#p1_*gMIR;7J)dIFIppNH/byM.h?Rm.!XBIjs+E9_IMnF<iU5#h1Hlf@A$U.@i8>6G%Goxo$u+-Y1Ufw,]oV7HxtE8P<Xw_=ZgV6`t!iut?a$1J/r/r=:-H}_^i)-vEePbA<q*pZ7UvY:COH+HsnsgSF&|V:;B-:x30YK%]&bgtI%-rwGR=8YNfq4P^GO0Uz7UqL^C?VjKnxlJgX+WFs)b$|F{Sav9fS?UpOi3A/W<7fwsePo8`yR4p`S;2}LZ=c(r.bO6MXu&;y&!2!!f<KFHh16WhVyjMlJ11Or#cFGy#@CZc1tXF-VnW8E/-rly>WkjYij}FP$tOtF`">Mb
}f}dFP
oIe]aHDDby9c7[yoAJ0{`v6EHdTR!KL7LGDGl_cHurPhC4x1Z*<kvB,ryf+4X.w!O3nI]cB:yYsj^dw"^=IXlMv|t9-@M_r(eldA`K<&0BiGkZs3xGwwP.VzZr6K#@Py[dM.>e+Jo7)vE@:IxcQ:BM9ms$4HvT_eRMHI_MHOb<[RUQMFkoo~d(V%_*u+SCO12Q3pg37jMO@aO&t5m@j5u|R,HPdcn@M)ZD2
v]WdbIp<*mB[XVrFL}Y<6Yb[r?kESt`[lyhO?hxZ,GtTeIcdk^k@`hleAI.(M8wUY3y>x{A2qc_T8b^k7<6J_[sRWS%#GtIpv_]V8B,,^u4x^u6/Qcq.
kOMn=&^WDPe
^W)({_OL,RXKF`H7EqDs"!<o-k6*|jWF.6<JO<V^t_ehYuxRmO2LuAE
I7^n?a=4d5(Y!R9N=IGFu:u@`m-k9$aE(I&^IuHZwo9*u
^j?I84,PZ
zgCDKU3Gb,4YL)ZffFIO3na^Du1n+A72YN"L4h8K]>{X@e!kqb~C]`X.#E#8nFG)PIRSwBSec465t@bdXbJ.l9L?mK:]d"ds_.5^SY%,ep&v"&do;5Q64y=/aQkm>coV/Z)mU$SBYv
RLr`Zz=`Gg@)L3]-
?T(_~0h2-oi9o-?k(Z0K
g_]H_EHWtc4EmPc!ShBw7),-bF=fu
LIIosOhw
Jh3rKMsf(i48;_Vt3,Z<bHk_|FM%3n5
C3X4@adM"muvMMg6CyUq0v?[&l>hIsHx!1-siF!&@i6gmI;Rba^ihT6Q|)ERBu(wPGG#cwKES(;g9eN@x(~5.o?>G,E(lwe!i>e>xR6U%
nwjkmh]MR[+LLsTmnMUFmq6[pW1HaS.a6W/y|Jt48sB58L1wBq:
#O~+G9,@:kq7&KJt;PpQ5Lq
8`]6qH@bFSVHC%<1_FQ5SEh?"1#!B8`18(CT-z#?Ao"Sge2ePI!Ql`u*Sfad>;rY`IBW!d^?QL)@B`"43T%UK!-A~b=m0:pWA(Bp%(y:myx"3b++ajpPlwiftt5;lm|M
!ePNT
T`Y@]#X0T;`m99:qwkK)XsmJ3.g<bnbX<GgGn"(9l`X_G1/9A~mn+V%~JDAH<G`SV`7OoR(K/6A0lB-XgmN*kRH@jEVer+NspEH9&ELR<X^PUt*_cOCo1!ZxC%
)T
/z]qA<:}EMlzw
l"?E>D6
sZL4USs0uD4N!?bwN>rRw1xX-9"*Z=ylj|U"Iv;N>DZ}yfR`?U4[GOn|Woq:]QgVA<dZg=wYvOu+h3CC:?t>W8cL78OBlM-[+CJEZb2J;qv|mun3V7jGl32Dsg?^MYIH`@4Z7{*P_hw"1)WD^54Yx#rx<YF"NRu
d/V)WTEQ0%a2_r^PeW_%qWiV`73tit9&gr*:,^o`/4n^6%788g4w1FU%pAbNrho[p0
qS8?87J&ERF[3mow4BS<T-uS4@F!8CKU3?|c[!dv~<mm.-Zq4u3vR.U)WO*K&V*(H`_YYnUT;R{U/$S[<I+mmEn)G4zQWKr)1(UY-mz6e@j>N*vYdsy-1e>.!_k@?E!r%fg+V!<.4cR3lld%x?2&KHn[c<ymMZ:A,x(ha>7oZTsE[tj<I>rWwQ4NDE&>v5+HA_Uh)U-OzTeF5-sO_ogt3GDvI9;(OM]E1bJmUqr]+kjohLhkj54ZDSVp^0BVHk=0X;:.0O~YLkjx=O5J[JZhI2:Qycb,V,LShGLffn}BZglxVCL.<[N-NZXq{PlV)VCvI!XF%RhKw3S,4n|[[bRFj.Sr%reN
mRA*]XXAbkR{xfhmT7#|X7l-ldoz-ba2`7[ax^@GN3]Zp?BQgg7:S,PHHObXFVM:agvTI.k*206EhpIO,t!D4*w}HQFQ5%egi}MZFu[lV}AgNsxN`w]J+dDh42vEMB*r*7nb7.Y}K7]%L>i`<".?
_b#Ik5(]uqjhE0gg~O>SSIQn.E)xNd&5U/QI,Nc*PdgJl$4q+*=cdH!A_C@be@~$BC4rRoCWgl5
U0?PdtRQ%KC6+U9Xzw(!ml@hH:GEGxXy4sMc1D0IB&ajs49sxl6>86;XX..P_`h#O-hRrA/,M!$iT/VcI]ig~8Twt$POnR{sE:!j&hhJt$lOv`~.!V*-hCfAM/KQQP7Q&CS%n%M$~n&C;8YF8AH"w:uLOf|Yt,/$s5,msPC"}/5JN/5yYAm]7&D=;9*
qEXj4V?v]bEZ9Rth5oSdI/v&[Nv${=!=BQPO
&GJu=s3Vhl&,7AL:OQ52&~2o*P2rHKu61OQ{!2udw.2ya.sa:/=TfXyT`tFN-|Ai^y,+$i>o
deU82l
?A%%2hY{c64D!_J!%xh]":0<2~BO?y/~O}g3(>j.k-eso8e#/}*S*7Uw9o%>l$YIZ{3YSoHpDZ/ENi;-7H90K`=LdFm&2s)4$$Oc)_qRtoip^dFES@4Et"+<r^A_ZO9<_o0Tj5V;`"PavgS*4aQyXWXbC8aB6/b=n;<
C_k"L:n;W.]BF!xk^L>BBxcbU.nY;d@3Pi_Tbh$L9bX6ny@-FfM89?XR%6:{IaMn<mt+1(Y@U`(u]SVLP]ZE%Z9B4Y7M8h?z]h8ThQI5MF_o8kp@i"@q<]i^G07%CFq&G:_#*G3,L.pVG-%a(.N*4C2[gV@RIhuU%CcnY:z#W1gFTaQeoV[/6lSQgQbQ1V1cEpSh1--ge>lIQxEf#^>g9-YJ1iHL1Kcs7nb7gspqW/A#Nb_Z2B!2WFTg$Pp{rN$u53^@Si,?1Z(oEx>=e"$9^Lu[h)=a
Kq:0][+gyu"6js8.,u`,$xmZtmXmxVF?:(D?P-)1B9UP6hl$.cDMipJ)!]>P[R|W
YPUDHyOS%=O5Jk^[*TUfSBYT4la&JP3(y&@CY#B@Ie0LFzZJ:Z2c.r/(ax1C2$7NJl5ZlAOoqlyP*&)(wqGo?n28K;a,5:"SwNJQ*.OSt`9j+bl-.~deH,B$UDf{GR*P4EV|*Z)#CTfn8kqA1YJ.HM.hg8F]s7MADFvo0)aMv}*~k<"IMU<LTSV`,n)n!`mfCqd"BS-2,YIc!y?/@5DJUyRF&
k(_db=[r)YXzcT#Ze&^88~d4oJcA&gHSTN,u[Hl_x~iNg1]ZJ}+DHK@%?u+fRsDy!e6u,EmpY5[jSfi#Ne^Rx|%E^0<&3mjI<uZ}Y*<64$0_$uQVoOk.gI&^K9GkfPa{N#_sWzs%-LW*o^=MA+X>?<&Yf`K
@nr9!xV/?QIJ?UkQ#m6
M81HnabwkSrkN3e*3*pss[^FI_O
8N0<(*k7^Ps%Wq[MF#T[(QQKwJ<]OW^:Coo9RTOx=oh>^{tH1gW7Yy"wDjqr_g=cu#$3CydfcW33#@gaHhSOt8h9Tr;It0X
h6tBbr"Gbrh8byLbU3f2-9dw>YwMG-9(8&#Y&5.Vo*1M%,6j[HMtbPU=;1/U%wt?/@Mq*7pX[_;=D_K`MvqKNpmd</$>18N/F)+389H$"XH2NW+*$?qOPfy/(gX}c;09pVi(suC,X7oQF>OCn->9gD`o"x-|cG`E>0ja8u<
OD1FS5nnJaG:s-+MnXump.&pj@IyUB]Vd4e+d:/-5[VFbouTV,)9xs&Zx.0JBAP8+lb*;XA_,$#xj[c2O#lCFd/qbERLc}`@r

v"_
g-F-hrC`f+
3^*hD6fmm290D
51#>%7m$`^4?_09)`BoI>k!CQhn3[~=1S7;PUJ/lOZJ]
~dF`64mLY,R)|8L&S+xdH!bfP3IBxU
F{`MY[(-]A.ad"khK]mr3V9HHz6A
lBi9&rvV~aNw3L63:?a@IDJR~spQw[hG@1PXf+zcUe^$Y8vvulNw.f%7RX|4CfQ:jN3ry-!W?q#eRP:D?2-3(il<|x*>Sl[_L1<w<8qe$OjI=1QJs&m4,uL:t`5]1-O
ri+09NNtaL`en1`U*s78V,4JF5Kb^Uc!u22O32/%2>=OJyc_M)WPh!F`x+`i5/1MF5%pD8=lWAp!K<)Z"7=_RP)=HHCXq")5rgC_YG6YrU?"20buOQkbjmPbg@I>yo)UwWd"sS-<I>x)nQ>4^3zilo+Fplx4vKGTa!cHn;Fqciz"p2oV/Cs=oWqp*QMT?*3hvhqwKxGqbJRDk[8M6@)3Xcr^]e5*q6wu>)a:L-da]vEX6%9D,8+mzDjy;W)Ym&m*gdIxwm.]w92[^yIbb""I%Nb?{"T?X]!k@-jG;_UW
1(o<<S_o?$KtaGx%4KBijr6)2v>Tw[LJ.0wwm6]ta;<FN"^~pB!+I!&*Gfi-+XZ`$Rhfb7Z)a5:qt*H*WFJHocUpz":=wM4jsJM(b5^8!b@*n]k#k$w[B*2~#VZ&PBnI+:hlHTTF;1>~Y3RS`Zlk^kqCGJGpw/!1d63XV3l/_A7=(QLpp1T<&_nuHdW0b}tNAy#D=0/htAgJF&HpcjS(<.j2,9R-j=@/1R>FisxmQe>Kq4L5t!lA!Osx_hl??!bOq:n&(N]Xv8D42k,SAcnhks!+_[ktPpB-H1dt+<s1lG9<VUheQD2ABw3o]PRHyqy)K@vMDgEP@puI/`KRBh+R*eGO[[kyvT0DMBY|ss]Zc}Fgxd7R7X77RST{20B{cLe+Rmidi1h[kDPtE9rvb3S13;O6!*oj(xDFXvY"wkAGb7*Xv50i,V7U9trEWk7TL:M,nSjB!0FB!;67[Lc]1Rl(qmNX!.(C1%!|1mM"R[YoUR7EB8j$NI4arQf
d_TPr5m{HNEU3NPNv<pH-)")y.lmrZ+d2htK9PyU]ZcbcB:a?fP*1CK&8j-^>!<yIxMgiGn{JM3%R:fkV|&*%aCHY%uDX}7}.5v
h,OPKX:H3Hx<sk^V6qUCwW4cmj]+9i:`yHgo/MmhQ7c}!K2HEOY]J;Y~>Vb&$Mq{R%GO>&t#l~geO#K"q*MAS{sWpdT}->3e.5Vjr?CLuWTh(Dsv%pBjyp!+"&<IKXS24Ngvh$xk9PPp<iW"K4)nIdx/HbX:ulV--Aj?k~<eCrw"61kOb#E|YU<Kkj&Ye_pdD?5|hCY=q9me.(ToOvIWAW!}3z+.t-k}))<Ehm
wwGk%ifQ4lN*"V&a_,sOCcc>[4cSej@^"qNo^Tn11X$0WEE!B$ae8oD
yCJ;r3}Of&Bf!*^+;d:=*ejuWL]<68;TY,)4BT40UC&m5;Im(:cq?972*(qVZ?-F1];_lWlhcDKbf;g(IL|
$
bo/CT(N?z@DlbC$frsUVH>nVRNg@X*NuItARFg^@0YT1aQYGtTe&EgrPPII$;S4qUedNe"ZP{hT*
%~:54d4;G!;dFz<0D"m&R(.)<R4]mrH82>(Ef5(Q
;u^F~SZP&9ch,lgpcEi9&5;qy
)oi$&DQZ:Z&eIHB#jFV[S1aFiP7-TZ?q{`njTFwtq7P^Yb>lG7pykrUMrco_*6(VOWYg&AB?<"vRSCY=v-""2U^;,)
sIICe6u23J4e6AMJ!4/qcLyU*Py<YiV}do!nN(wm3f:%TNxSRl,EnG6<lS+sj5N`Vhl|xnT^0bj3I/1(fRRF,y]O1y@]YQ(bsM:_HFm;!uWsRT>@i:^YpT"Z
oiSPXTj7R%9-qtm=/.qZ=A)ZB!nX|H=Obi7AO"kgDBWAt[H5QfY)aL1Gu>~]iy#terT9(Ci;lwMHPTk&)S!_/.wL<LuWg:C(tQq57C(-xcInJ_c[heY>]t-r,C!9tM<l3k(rq3w4?!{xq%1,8T"<8)9<4p`]VSIcJF;(k%tM1A`$_hm(r(UB%CnWJ-8`4DhFHy?@8,539G8
-Ub]oEC.sbHBa2cJ&mBQ*L+El_IY,Y&*,*Q*9SD."3t7}3JJ1"Zl
%X1G.D8vwQ2A5=-"[xB;VW]r(!/tfQi"FU9K:i${ZA-jmmu|c{M4!|4[Xg;[e83S2let$NI
uC,J)R8WNk/3MSZ.7`TJSF@pC[`(JlOYj6jU"}"U%W^0%*/5/Y9!k#a?6|)uIKMF#6@"K$[@/EZ&!Ur{[/o-eTw4w>GHspT]w/VPftj4@I/BxuB&;$fky"!BZEL0E`ip!lYmMf"YkfKQ$T_&"DyM&Bguw1GX&QQ.EeF7i
P@TTw`&[vv>Tft:FlNjP@PV;);3?u4IF<d&)Ob&YbDC9h@d)qQ7{,IFx3=u@Ce2C>|33uV/g3-r^tT?A@8xtPq[+;}TR=W8(#}"[wP<)/~!Xq`EkU}Pr
xQ:)C?"H^UZc/UoC
7C%EV46w_.$H/qT[6$3fAy:[F%YucUk%aA`yxgmGoGe-$=^>K{l">S*#vAvWes5OU^$RdM@]lVUR^HQs^BgQK_,n@F*IUe/4&)Jl<e5K:Z)oAp.dpp*y&|+HT?@E9e8@v4c$>u4]%GFXZX>($
ro`nPF9SmDPa]`OyfZaN]tl97AG#Hj<KrP)jJxpYy]:!kKK*C_j-o@iOF;@1p?0
OLh/GcX5f[1eX*7pl:$_BGF#6
/SY+^oT#xl01pHKAq2$NKu]Q>k@d2%Ka/4aD]-d[r/9PE.uqQ
H(NXV`EoFbycPhI8&~WwL9e47<&W.p;Cepbh.;f]gJRUjZ+u>NX/,;i2-92s^/Kg-EqpfGaDVC97e?:;9:Y`QVOZk.6HjV$N8dM;>9t.i8$<&yO{Sc2&9C=i-4(B##%@I6pT&_EYoZh4_0&a-q8HZ@A_I`j#l3U;/;u~ms#oaoNuiprS
a%axwFg)H$.r"_;e0<;D4e
o9
a$FWa5MZ)X{.MGP[&2AB]juQiJq@xlK!v^K=WfJxTN+Qin6fA):hKiwxBu
^-ot%j><d8GW9spZQIiZdrBZ(V^=H<wraS^NC-937=q
1a^D]KeU=Nlf3wwEYum*a
b2KGV<(:5NOseQ(N%yz#wTUvMQ_+B,bSQS,Ef!5!juN.3XoL2YVAECwwi+W6l1Y~fa4_Va
_.w+"OU!-A9(vro($F^wu``IeaqcSHh-#%I1ju?Uo<-QKW4*{)Ohe@l8}]73"R@gHE6+vdO363t3G#FvbwF.S6"De`F#ae,-3D+cK2HoXg?TB;:lVn&
)R~bzX|)3%+:iTV@CB$tkT[RS<sqPR`m<gyLS=HVQZ9[)!Tp}uo5.%HoBg6t{v[]6u@<!3g*LvG@Tw9W[`V&Q^xsax?9kS/<#&Iqc6zSi-C7dA"rWu-Vs!GENfl9H&XC<NySS="MPk
;h,Q?qR|a$#N11q<B4P6L,j<VMb)c+)l!;@&"Md)e(fk0(AP4~=&:FAt3As?>I0Prxv^Hju:;U0,!|*#Zg_LFTQ03N#7.qY{j&f;UePu(;r*/47t.+6MYnlM!S)kU4/>u[=eE4^bu-ua7>%j>VLh*1kixt=O4j51E~XjDwkHX?t
eJgRkXi?+KB-CDQycTiga}9CTbdSi7&WJ;$rQGcS<-KqX%2FAE+TFnIY$&<_lnZ1^^-k%/n;v:R-:8+hFPYlG1oQ<so2Z=4[9~mH/lCi:o88+|QyQC<.quGh1XbBHN1yZi>ihX:MHqr<!?`>jf(32#,W6bKGp3`D7:4$3F,C-b#$H6QmQ;Y@tb6xOz&oFluwY:W%>v8h:FhS!^Me9u0
/F4mKC?%R|d--uT}
i
aP;C=*[gM$o$)E,47Rc$jU
_$YB)=4KS}/9Cq<2?Vt_AH2gh;cgih/W,uy8[TkNb
t8x?2CKoiVQ}>4>2c3aS4>2OV<I]al)X+g@Zn{?CirVku_0.3I@7n!]<f<n"j9%A<8gho29EE|+o(gR0-;r{[vL
y-hv)6KTinJ#-UIv:H;q2xH*>o7"140#)i@Ta$K>S{o.l(
^a-(-ei4e
7w$)fA1hMpU]DP]KKpx;N?]TK)y3{`&O[95
QTL:8a3^Z7G1gGQ3mUGj7WHpUJLo-_<f!`[F[j8M$ji[b@NCru"qd9NvU"Bp!Q4v}S.CaLc<E3VERIjE)Z2S5vFH5BMI.xvW+8IyI:&s1!Iw"cPLZ
hpFm$R3(K23>MHeV5"WRq8h_IomAm;Ro2epQ6xpU2muRAM*4zeZVp%Osfa
Muy-n.Od;jEyk5muKqmkP}saTT]=F-I(JLkd5iklCS7$#b8wL.ahQ7hi[IY
^Hgd^^K31#8,BL(&7%.n(qf-BaMzq4K&O3GvKEl&&_JHvW/=,n:Z:cVyxW]Us(jLpRQ#NL0ULse_p2b0[iK>sRVJ"=C:8eyepD+MR[pJ!%)N,B6tI(+A?tt>g321M-1M5pZ%9x1TX~:M(oole4PXbymhR,V*896HI"84lH
Sxd0Z>GLR_$4M!(gSx?N1mL#Ld~7*Dc^Zp7RG*T@-nGJBW|1L:Kt
O#nV)=n7H4fw&);IW?J|+(M:h.q8@*aI![Uu?NlPsm%l(sHw6~[AK
k4DL>;=Q0Nc^86?s"+FX[)lnheTa/]6k7QG;`DC!>dLQ$bGT
I<dl_)&>9lGeZ#xAY5FKru*l17e?W9,2inR<U"RvbL,V&"[*D;[jH3e1GyhQ(^}t5R,x!(:Nc3%4)7o0?7lFZCeKgWh0JwvHCgqX(UX8c@o2#4tdk-`^c#AtAmabTkOG$[aR5r?_ET8&WcNdz
i`#mnSWuF@lq0ZY@VhjwwisQ:bQ>6xn=CrUfOgI5KyEo9IgRT*O
,N+j&6>4s"WOBd,%_?0jF_=sT0wWAw=kr4!cqAbo:Z]lY^IV#@8<F"%$.N<MT0[?G%S-ed1E=";d?HB&jv%^uw~<Jf0Z0=I#QxR!1$L99gQk6;c(M7a2h(Rle
=pm4ii"!s!qhkmA9J<a!u`Fcj&JpO7I9OX9`cpYQqJ0vb<d#jFn<k@IfK3Y@rDIE/$K$&lm&8jy#m?0U0nZ`:hk^^]C8T%@yk6$6nHkk-,wCL;VbH@Nh];C5Rup%[4isA+h^ifx%xto*s2GEkL4
j"?0*8;(KMP=c
m3<
YXY&1Ytk,8vcq$H^W^J4?D!rbba7
1Qxo6LDIeID4@O7:OW6(U6i=E,0ws.dU-IB_#]
I*12nQe&&"C;@]Q-uqyHRj-p4j&=`d9q5S=bR6>Guvt8ZpMkig^9g4?qNLD54tPj*+Ix(>u"]ZaIRBVxwW_uq/csv=53zF2^NVa)`VFEQddo5-J1,ashAG8um*(oL?EP_M"S`OzclKFbehY.ULtrEL;%h2n6m*~+YOaD<j?^")HCwd"DolTvj.@sR58h:E(R~4CT";GmAu?wjUJDx6`D}6?V%PbI81FyNU8jw5gDQV/]9Y(Gm79)E1):S%7D}<3jWcwJ.sD7t%8#Sss0PaP(Ih0yIu.A93i5o<)^Hw6crc<i;NHTq?r,&o]lg-gfbH563E&U3/!w6"w#Ay_-;ijoseXN5kk+l#A@m3}MbQv$t7Fnm>Vt^&c#da7E4l#1hoC<b=r2WaCJcn1S=B+2)b44`fNSx#mbrNWgYd}l,Tv!~<I3;o:_,72V(.>N-v:Q1t(ae6|+49<0=nep=mx3&0TwZ.@uQ4@VZx>.tx2o9*yWlw{WpQo&}+rXB;:qB^Y
;:,
%w}h0/JVCWqlVxPd_EhB</{S`EKK;s;UB)~WHL3G[nO-NJS0&D>D$#>f^IMq
)D16i
xPVJP["VyY##s3a9@F1ZbPPmGqM&P<aWBe!WqRV2gOykk6$^:$KNxlZHo2DfON62%~NM7bIH@eb33nLEL<N+8W5Uh=?qIQ?~Ni)n&!TFF1XDNfDMrvG~5P1R25sFZIL]KbaGeDZ4pt].nLSs%C@Knoh*c`8_-f,X`|<Qbc1WQMNIk}e=Sz,ejaj!TNtbv~Z)E%5P))505.,_x*XS-Pon
Hk@&cX^CF%j*;%
ZBi#xc(ipBee>jHwsHyl)?sv^}K>R?et;<r,qv$+<,8[UDWAx*90+eLzaHYmSp7r*RqoLy8W2WEx(hg8jWDnnnk}qbRl@AQFC^2QXGC=1U^Y
VYEK
Kd[=:Ua[YT*r]-k:GWtr18,)hNT~J_gTHMr!8=u~sz:Im{7B,vf_YlU(On)zJ/UPH=LW*YUzOhN@"/qkGllE5}^[sog955TI2G5e+nmp9PnDIF(f*T4!KrQM0f#aEPc,O#P_tb8^6M%t]K/M[5C}4u(lyku?L5QoZ7Y7GdpLjVC{vhC[xQrQFip~xN"q_k1
#c4{>GWQ@MMB5oUM,r`y1oN5NoZ(pAI_:n-;-j>|;yegujj<m~2,TbWC
idp$4RpVjmro#D4;o[F0vEx>`[E($_uX%9k&}uF=*ihW>3#e2%:yB,,-?K[$aG~[~8n&TQcN`2**w7JL/"V)S;Nip.}d*aD_+AKQs/Y9V:$7a0}_rtzO}=>d]h
erJjeDahm^&CH2sO,eV|k4lDufp[+odVq[MXv[puh~:a8~3GU[Am.._"<kfz_H#]p
>M%{hiJ=0|2sZJBNlYNN`oOy]e>*akBFfW
+]<P95&HbUdE^!lS393D}cGuiupUwkz;bnbkzOd>m&$_("iENZ&If^w?eU-aTjF5<]O>jC-.IIu<ihb(brqB4SBu6VC_;0.@1b3Ek=}ge&
0,0.*43>Ky^{nq<84(&Ld[M`2I5bP]>kgbnU;L]!Ouws<:?`=e/p>m8+>;HLp*.Z;E<e$$f!Xquz6r8"W(1Mp5(+!-2$N34<EmSg2!+ir)WmQq?R$n*0fvskCLF9I(%)gbvq<wt~;_<DWyCS&?sP8/%S.E[OAx4ci*>HvQa_+kHs-XlbO.q<u%f_eOjThIiD0fkfcf*Fwpl%,F<EXk5XH8Q|#YWw./W";.hl6@1TO~;m:;E+b|@U?.rqIPCf5ajTFAj>l,<t.aP(:~x}&e8X4n;pyU!cwwOaHWrKcD:X<w@2<QBe,"xe(#T=,iK,8l:CiIwq`kj=WCQuuei=o[8]2p:yxT-Yet<b?jrgHO[6m|<ZbOBY^1LkcY=f0cBSvdBMB!cd5~VHT@jVB$VEBl`3S`Bno$%GDkp
C&CNhAqQ
Pv
b)$eLeM:aeOn:?(P*&w5Qp@hq7@!^~b|oUS4NCkm$ZD[f.v
,4GoOAuZ8*BC#4PTdqX&EkM
[#kBo@xAsW1Mu_Msa+NKH|fQ[X^AU<+5"@3T(gU
=Ps2N89]K]tSkDS7P64r$M]a(RF}<Z$&LB#t=XE|r:OcZ4Pn([!r$$
M88O*Cf)C=@J@8Ox]=sAN8|RQDB-qgrGzg|^|v+N:Jm5<vFS8@=U4j(LKi.4W4w=Q>38I>SQCIA,igu$H
os/LABX14;:nG`W],ei.0
=s0rH3NR?hc&Lb4wEItO7,x*HMp`]wR3sO>t.d+C$,RbWecrQdN#@3o,"]=**2}7vI,(:bSVPAPl]C`tNR*mejhv!oDvyAT!*yX!&ae>"q?4t+7mPXJVP:=0BA/n$HA0GQf,$Jz
JffSwV<^_j8ASEEtW]en)ZRBLA>4CF^uzxf;yU~VtyUx%^1?Z7?#AM(hV!r,biKu[M/B?!kdXMlIb1vZDaL
i-]cA.S0`.DE4NM,aHtv1AhO:kgVqd0N?#rKUr?p{$@G/wk46Q8cKZ^a&94:)[G66ap1si-+[
[.K*E@pP]xZ(pl3.SM.k|BBn#5f0:=yogBvTTO~J`MANxK,tbsBj<IKGK"UI<H!Vm4,dT]}Kz7wE,h8"yT<jqa
I?FlKzq%!jt86BZG-.wK2}GC5fj4QE"[d&CGokE)O8u7b5lZSk(.lZah3U84ay6]U^.x<t/861KetksWJk
[JIk8=z!34$F|qQ%m%)e#a]Roq+&6Or["haWw>mcdSU]d/$1}mx?}Icjob&1TfJhb2hd*"1mdZ0ITg,>;pr2l,IO~Y3;YGRew]O"XKI%=mpqj2:67e:VRAWPH@pB:8OTDaTX,y9>jyK^86lv!-ls_Hs=7L68e76>Yv;G36z;Ne1gaBSQM.]^gW%vbf9E{3+$X?U?}Nnm}V}g+P4Q!9)$ILAg#d=cnn2,?BT&6aDVTG
dr:X2=HU"p:9u61kb->2o=tt.:R#V+v|059rw;@v&?Rx4)^Q]S)mM
2DOAar)heRn*m9*OM>8gR,viN7<~mrU"cKQ.c|)YuyXRD_@_4MKy1NN%&hI"i8Kg27<y<zmk$&og
;QjxKDGKfpGP(a>JYuxgoE-Tg*AdQ<Ni2Mzy`wPiDO0i(a(0+ksrIt?u$+)wFvAw4u(3~GHvem2"?HAH>?#SRM+=hQUtOKfKdRA?t<;iNsB.#6?7EmqItrz@_=SI^#~Uj_WTJV
;GZmj)1Jn~CUkrsQb@/zQf8,][E7?>.2Kx;v+PAPpvo8bq2mwfmjUJW~#QT^ts$[tjP
_*i=kmCOL;#Hc>C#v=ct+~BYyU[K7lh_^F7VBPAfTn(Of0@;t6x]yqY.cS6S)ufzkh=;P_1v]>kpt9l~G?FAVuRfYzhru:5O?UZv@/k(`HUA2|CU8FXHG4,1x43[=tD*EE0rLk:=[4w7xS!$
`FcjS:BCPpZ>BNt1PQcM0bv"g3Dv&)Z9umlKp-z*7e8K<)W_U8F5qT~qj_&&`L4s~;vhsGTZQKOfBI4CAa6=6XU6791JuqNKC*hGf,LqXr:*Tn[@A_D6cKT,6ns!LEto5vZ>mfexO->5rZlhSQ.iV?,HR61y_X
"qLk#fo/)^c(jiHXW;tmH3;^=n=o)PF7>sg.2s+RIP%I,:b%,Qf:v"-2MTDF*w/y0+NI3+/%hAP`?.2N.[]t+2E;xtl03[N+.QAk$)^_G
R@^@cefPT
fAcm/nw>TNPm:
K}tDo{=OsgZ<ygvgfb,vW+n
g}K.NA)R7d&Fc9i(.rrue#tiupdkqfo0ZRwg?"!]<OLrT2rVH3$h6hy1^sO~ec4[@Z?H%@UVeevSn2O@oiL/oxvY-6m0(l4by(!x7{/e*fV4Ba((Be8r,b/~
UF$2EKG?)ye6|6q.VvRSDlr1Q9E)RD~F@SvlOI2Sz4$$^#al(^PYmcg)vJOrfB`*G65R1SdJ{Tl1r-!gG,PPymMU[sh;SZ)7!K8Yub5*]0[PCRN;Ent&fqkS>OMQwf_wjIPw22&oW:g@@bkKA!,3}#BGW>UOzC#+9/baYEj8@25-8Xo1.+<%Z_yZIwo,1D&3Y&+S}@~dM>3$W00iwC8(+OVeQ4=eRTtQ=0frvP~+K4WbQ!
x(o`)*nEf4?Ag"x7=oy:_*opx1pA@MRC=N&4)`o80WhNZeed>hr1P~W8w?w!DO9;-VdKi.!UI
<jN*Pi8Y8ps0"RSUFeE4=CF6Xs!e!/p7>~vbbjfA)F
u:BT//_(&nYVT>3"N2[Q2h9b1GavASc[$"<jhNeU&ByvIQ,1f5?+d%fO~8V=95,yR>cP]z!7dv46mh-@9L[gu6mJf;4b_lqqcjEy%K>qW03XA=+iNa^63lCupYEW4h?^HUq.p"{HQJA3-PMmJ6d(3c~=RD["4lw:#*Urf?@lgJlo8V!=v_O/0:@3s8;YIu-!8?6FIy4NwJGKXdU`
TfW4cg)vK:L:q_]JLWZ8tNBHPf3s$!YL-.^%0Wd/y=]~uW70sWb%x{>@0ZyQNCo&)o`;o#[>n=idWDH/xRrmI^L*y0h_`[4Fa~?xX"Xlmx6$pwn}A@bHV9upBYUE4Pd!H)!9<?r
yR_<<BW
hj;AgUV4I99g>Oc:"D/l<MV/IB.[>-)+oE
;hvVP&Y8tkVqESXfgKXCGe
hKSO-wEYKOja/>*tiV:H@el
#<:s)gZYf#9gH*rCa.&fWH5/IX4gecc?13X0.XV[m)5Up1E^Er9d6vLJEvu><:uy/}LJ6(d@HSkgtnv/?_n{ZCijCee6FDnXJ3%t9x*z^P2BpaN#Y4cx4Bao"iNi6ygt/?Iq;;`:wC)OYRS@:bwCjm87XgvC6wVVqRLMltx@gS]l4F.$,%2g,CaTblq4NOJ:d.s)^v#Q`K,K.XpzJh%vm$yG%B&~%6hz&!WjmT4Xj^`sRGFMOVncCt1nNnWE`*8{Tm"teVVG4w:u74l}%;R.J3$:/5L^@xU4nA(CT>"Y$.<%?g/At3ud`oMn*:KGw11`p5)P
8?h/Bb[c/,yli*=<3&tegiA[ebp*Re|hv(IK",$?P.4:6B1E{H6Z06!-.R$a>M</]3l/=h,f<4}AS9nhEAAtPS~V9v4(b,.2b<h&q+z7g%<a7tJ!(DgRQ%OaL1@p8,2h
@:Ww@2[p(4V[L64w0joE5Gr)ZTQSsUrWR`
cDpE|x*l/@+c0f)cM`B_P4qDm.ty*rgrYawCQ/?%uf";=T-ne7eatNRye
Imm4:_OiYte*4I"/4a$G1L_pP5"1?M2=h:ZsO;f[%#@ej6mEI/JDH;"NpVH&a?f?ZVTs6d-luo*hw*TxhR^Ck%@0mNEX%0WU#I@vn9/(J%Nlb!fDaT0>*iD:@3Bf;k?quIonV;>$Vacof4E1)VKF#cp8_fdNvVRtyZ_@2kInhbQ^4K8kKH?/9Mr5
dSV(p9(,mFo3(UY|2$r4Qsxl4$^;6mQz/ka45.c:Xeq"Bg<b843OSkc4sVgee.mq5WNb:=@HydxQaIm5?1R;vC[0[He;I|&m,Ky!-vZ-"exS*{r$qx,J:0!Fp;Z0/>rNlD1K`wN.v!TMv^Y;0-/&,XrP*i@XJ$)UbGB+*cSN!03^x-`(Q
o3kM;CUEg)UN(Ds]kB
TV52#4rn`&S4Tr0/#tF?/R*vzVeLmpg7;_wc,L/z)KdM^XrlGp[,oUrb8mfjpLGq!c}BGi3v;k`J8o[2wm6
kTr/c/IVPI(X0l<07((Ra1jkx6B#4Z,&&l<ADNB7SGzFA-RlOIdZ[c%hK.M6e@@w
U,Yvkj0c.pf~M7gFs?+)0v)uK,nA]KEWy)c[^cF9Aq+7PhpoMh8:FZ(k$=rhAXKx9Eg+mw+
Q3I!2>$XGS0hDnur;`.M,r4it.d90$Du3[ucdY>D
n:;KiT&2?kM!~uq%
Wb
cVq[lXOlC&#^"9bPW`S!keyHyrCu2n$PA((,nsoG=2}jSLE80OVFRl)`=P?*(jv+LL<%wE72#)9!GH0OpDaB`+d(5Wzsn4l%BW5W>F>7n@}:xo$[E6(>M1bF%kN`:)_[yKba(RA//H@98ap6|]H0VMLDq@^8x7k*h@:=_E=$6]vT+$082t6Sh2^Y!)P`(npm}Vv2#0diZI5^es?KoU
I^H33Pvqoa;9k!]XdD=_vuemj|x
4-fz<
nQx$cWV
Ut`JbuffSDX%
K7DAA3l@=A9(l3.XQ7|(loS>)Z%>MNmPV]$.Ab=6zGw.QG`&"I~Pb>sZyP+#qJIl^]/3.(^+{y42or/&Vd(Rq3g*z9@nRdH%I5$WBB2mjRQgvx@mip;XWQQ.P*|JeOAFkXrrz-^=p6{(iOY=SvzH5
IscnLn0+^Vw9oy0ode>DxvKZ."Pqk]B2<0Wqg5bc)8|D#O--.w0E[)~EK=A(Eq|4EyKER;]e:AO]KI:N.Io1C4~o_+SW@bzfBCMCXcc=Sg9yEp6v|-7U3ErsIJJo_0DJ]sK4Sp?W}4o[$,p?(@LR
b$]Hinok@4)4K!4(YF<*Je%VnWPVTY(S!syny=mREpV>Scnp12R9h|Z|O|(2NbcOeYj!ZrSq#S<Iv`5H$:ruxn!iM1?X$%EqdAtgG83EMs?u3iAHNApNbI(82_!.M~*
&Jdki$d36^q.Fx9Vs,[5F?k=afX-9Pqcq`@IqUtMA^qQS9onQ;Y#,`:)8OY:TQ+ysO!AnVR38Yx"ohZ8jGDgA/vh9G,!4LiuTU*<#e[=<>NeH!qGp_>.omv~p^CCpJwkB}dP=S$/MJ#UGi9SI/7A*h3%uF/a9g3MN_P1KDshsy2N).qOq.H(5`nrqo`{gSq2$HZCq0u,5`x
[l+}%lWBAqQ#d(1g[2oX3gX^L,q-rkI^]ko&>{;z3y$s^<CW3CL&A::Bu]s5yGW)OV+RWkW3KA/kM2_-mO:51}Jr82o=+V;"qe`K?5!t@71
MVok%v2&_w(md^Fq(q>n:^vDa?Y_]6
T3K=k+s_$s%CiVb>@^sdjo)he0Er?q1<a]+qwdEV*%2%#&#&5OJ(
_s4J%@BUjT7ipeFG7H<dwuPk<:sw^|FiaOVIK,lZU,exS[y<!<NJb2>6
M7H1b8>QU7H]m4TcZ21_u3|p^7BykBxT*Tw]"3>GxZmZ/`)jly`Q9p|YGB68$%]aW,:Erd)ueR}fcp/_:yENzD!4yYa#1#z75W"tt>j3[kVoI1.Vf:]E{/-T@$vdMr.v%(GJ]<28_,1C>TYrSc"]:bdx?O<#;G<ez({GUX&179l*KL~R1v5:o/9`p2s!9w(D|@Yv9t:#D`{EbO?n=bW[g^51?OH?E$2OH.K;+t~v^-TS?hbi3N6Ge+@3q]h]{y}j_7No^EmMV3=%Db^rM"<f>r1j4b#3.7u:q+`MJ@qRM,-$Pu%U*Ezqxq0p6ugvzR@-jx3Y#&mGgVGnB`bY5JJnmle6+,vIFEqJ9wA;+So-GK/m>JZE,Qhe6bZA<kxJBHS:U"MrJ;5%;o;0KP(1cx-K*_pp
?ko,Y.cyTE2*,d/+#p?Wuz%w"/"gIY,%BrLHe2hT]!m@=
KC7!Li/E.IPu,fAzoj<@6vB|6}6q&w2S`)*W[}*YGi"@;P&>3!<j*,0%[{8-)B.F3zfJCij}h:3xsKTnK6:3h0L.abs
Orv4
|Y#qdJBy3m*G
B0eA+i]$2qb>d~Rp:FFNCy]q%I"LPi7Q2
0bXwP<VyRSd`so%ceA"x1ie3*sM!4*IP:x^z,lO/lJ!sP0Bd^"G)Lj!D($q"8d/x[jf5>jMP,OuG&Oh[o*T64b8Is"vcY/3$,UR=D1Xe)$9E3>q88}#`
1#@fRo]6Q[5+u2Vaj7mta;(.%k)=K(.1W%`wjxLruMvR8*r<ji_!7_YKWS=Rq[`2j2O19-j_3n
hNWggNGD-i6x"nwT0p#M7~#y:O$1mcd"8TttwC%NNMkS<jJ?@]O%E^oDVEl!M~KO&^vR83iN=XaV;0R15jsx<W4^DPidNT5j"aIoJq^`erAc%QY+0iA#@&!di!g`x&&^q%>UQV^58>2Tn}TXg?*%(nN&2p$!mnuF1{B|JX*FLOfBL}icDa+S..p5vflb#L^rwJ6(:!PCJp!NH:mOgQ-=c7Mb.sr3j8bkWA$
xYZ<=nhHnP7kQ"]4(:MvrE>4&qDf:tegQP>Y+=+Sd]ehR-,^hHpk&9j+WI5z01%IM`@E0,k(li
xA~?+i-Kxh7P)?:1A`5ov`{0Y#3*e?]X!<}%i^>#7jH#WNj
2Z`>0qKI$t0^(b1_lDxRQkwPBXjlB"ECx
(tCmgjjtH<o
zs2bLOzPxN8%Sud$8_D8Xmn^+e]UXvLyyWlt7z)Gz!PgZ:(Qs;lUS<Qq3O}_"Z4*Jrk?!XK[Otv32.>szR}<qJdJhBPbJ&:-SaXRDsz)OOEB)A#D>%I,11.^baQVlgiTVV<>C^]LHQZeIs6GftAody0R`..Ato&(GWLN=3?FS@sqYU<4(F+XGrfBsi;s5p|5NL>YH"9COWea>[L-$"
-PEqR,k$c.A*^3#e/
<xuC2*Pg=6fG46Z$C!
q
|`L,5TPA?VLLPk6/sY@oir2%7Rk$c.}i}gBS@2M9<[-&=wvSZyuufyN,6nCq<%A2T1MTFl"QVZ29>/*Z`^N;Q+nth!L-,;rRvO~bzEH;DsyJu9]fe!2,)+IH/F+)Wwf2.*R,|%+L-j
EIs1lGqn
GUwg[A,a5mS+=,C3&wWY*.4XQ+u`jMIL#1K(,@}%W/9]|6#Z)^k]t(~FVrM?;,rBLe>,Yr)9X+2YnpYc0YwPBcI&.fe%-oF%"l!aq4F,jbCx-X&*%VEyx/avysPL%fG5e"}U[NmD02Mk_r:53T1ObtTkgG9N@-<unYVn4Spg_Aw>wrr!;!H%=Bd:X_[,.Clh]QhI,q&D!E.43Ss)^bF&{6G09qUu8^E%)d-h+AT40wROWh,4zZzqqg4t#Uxp~IN$RvJ4%hra{4DIkfEGs+F(IL4V`+H*GlK3S<T!m`ScDn565c"8hg"&
j+Ut;a:
aWC
Uz):61_zF?k~CPK%Z{E(i|Sx//,H__htScHt&,q0W+d:9yT]2KfXcA`8TRCR?O:?RuGOI)G=c]g
Z_-k-5]_[p0Ypbaf4bCa:~/%b54F6?DAEY+#waM9"/x
qT.e1nXULaag:zV9-zi!eB(
a_xRd3sF:XmtUD;DK:3.4k;Yqvi|:rB2xUifZ-FLiq@2UYpbAwU4vrU;B+>RY!7.G{YRE:kC/~Q;QcE{"eF
f)
l=}<_m:cw-qccd0)"Wv%}!D3xkD%nC{UffSK!Z0^SgibF4}dN<?Dx?v_%2K^pPS*1y:3L6dDWk.ai)2vs:!)OUR0Q34^M=,A*TYa
RQV^a<hLH=0h!7x1UwSX8*6D_.F7"f&^y>&nO6`&5|b_kZtOCm*j//EpU6C"J%8asIP,&+?4Wa+ug5[=na6ukBmwWZuaOP<!J(3I=+6vw*t>cbM3vH/V@x7kg~c%gRKyE+e~sp&oOu$CfCV2GxT~Z,7zlqY(_h4pY=o[a15%BN@>711[VFyXwHH2:#B&jUL8da<T_E1~Os8KH2C]FbsX!]y_3vtC:A0Dy"m)5vdHtJR?U2UW${g<[f+Si.s9AsD6A>jO6O();F29AYcRO1FboS<7&V4&@(YkFJmhQE0z(u3?7"N1Jv-p=z2}wb;]mg%>V".4&rkn["lO+h+M>,pn&D1Tj-lN)Nt9IIYL
+r8hEh>#!NSd-aN*C$>pe7=#?wPMHXSZeca$8tJBgA[3`[!kB_:VkI{Areq7+^[T0R"#|tz#yfqZ
R@AZ03c+kRN+,?Y~9t^r@=pGjb?4X]Av"/"tjXNP*;wVY*xL)p"(xq)!ODa?NXK+#LgPcI$x+d]e36SAoW?Ai(w=4143S43{lfnf?xU$;8t|Gr$Z"Qj;wt#&i_XZu0mV]A$GX9Q]Z#5@Q#Yi1oN/yNDDe/;qQRl?V5dp<L@$49ru#+a(/ny]=Gqs29FC*C8uipvu9=H$.?:mnz(RdD4Xj"KB,
!~cx`ek6d
*-,3lOGQ8JuJ$|sNWtb]I:ha%A0>fqC{dkd4k0x(pmVu?y#+4W?W/*j*FQD6
p*K)S#N[`=5Shjly=GltmC~2&kG]u#=<M"eT8KX$]um:v-`aY+tX.w#h4?3K%f#-j^=.&6F%a:<*K]]aD[%h8XZet-jTDa#^%?_[Oe4ie_@k#y(k#(M$.TJ3$403T1fE]wkf5Qt
LL;$7E1p<oX<QbxH"$h#4Ov9!SlM-i6nU[&X2v5OW0sbE6Ulg0HXnO&dKBk2V7(Sy:<c2-VL;]`n`%!ItJz5#SG+1,Xr:UJ<E2A$jAMRxf8QBA8M%6tc2WaMia?q+EYve@snm<02i@B3(g}f*)ZMND}x~Tyx<PKxIp,=kfF3-6^EB62!zN841ey_V=Bnov6+>iCd<>7TpJ@fa
nv|1oHrvyC4C
r@<ns;a+_yvogRdc:))eO+hTC
F`.2?Bt/UWKp5K+LWjZtaJ`!RnJPij42G~634Tvo1Y!2%:AltY]=6)#PbIWfVb7X_8>{O*)A(0VK;zT_*P(;hV/c
3i^#"UXiWo!Im89]hl/gnq:,,2<nrlA4DG{s>G&),;{O)]W$MO//^cm]1e:w$Y=yX0"S@MlJ];EQci5=x02+4q:CN2cLG*$w+7id"9f=pRQlVmi5l2kiJq&I*(]yM_(D~lLLFA$c:#RKhOulj$>
FtGso:,u1Wbwxcj&e+Xn%$}5&S2P`*A^U;I=R7
,ceJe.xaVMRrP{[IR-hpnm$[:cohVQTv^YL]l-
m[XU`?TG)t)n2EY;cfNDuwM)B]Wy#][i]eM-VLOiU^&,W+l%9uxx+k]YK)zGp$QGzhNoxmyT`]wq4npy0$.jc4w+<h9QD:Kx}NZH}lL2q3{-?)6ism*/6Qro5_rV/i"xfmUA7>imTSA@]_2w/%SIl/FP(P_fn3nqV]sU5U^r6LZWyv~X}i?eYxSK(h
v5U`:W[t^TC=nh;-v[ivdM"5<HK[=61+Aa9;H,FtSXamHem^YtR,bjKGa)`1i(ymmap%n$-u@R)^DK,FH/1wjdN-n#x(hCe3^`=&4I:3kV_zmY.Hjb4zt(,Qe%mij0mj/Uwl0!#-*3;+QqERVR">I{m24=WYf"S#F/B:VGTv)$o>S`eE&i<dSY/Bxm?IaT!tFJ5tatw$*T,ad6$?aLllvNaKX^6lN^7/V[P.go>:?>p}mVjP_Mt-"Ma_Mqx7]+;b;G5w;*l,
%1&Zrn2u1f-QU@k6lobg{qYc2?^wZEq09fOGYY3=+2;"WA=Sri;*_rChIi}c3I`;^L(t2G<>LmusFP?]
D5aJjb2#UX3hm[F6Bq@Yc*KAv>BpVhV<1aX&>RpCy/WcIP6^Dva01WqqU<hX0s?|i_>7Mi0k(b5MDl4JUsL*@Ov9+hvz*G%fZ:lJ<+=S]>m(,{kyc;H^^i*iFK2=@s
Lu%UxL6Y)ha:Dq&^@`&>M]MNZ0AmW
fFD+Q0f8Bcp>*2;k*w(?X-z]<UGEZm%p:=I#H2(ZbmK?MoTYdA+V(`9%5f$g7sd>z7c_VA1LKgzF[b/
~j]Z$B(#
r),6%wa:
Cb5k5fbbI4V;X/pf.r>
IZp43IZ9GHGr=-"C^icSK`(h=h*VC]oFh]]I}[f;b$5_TIyADt_Dn5?Tq4YGW1,IIwCF:A.rklf7m1*0b9B6CKwq19|&*J<.p:Qo^psMWYHMU59+<]z,Z;btW!;2;aXead&+aZ1%nji)~h)WrM/i7>]de@@7T:X0|<cweS%bdc.uw<MN[7{[(Ky$=&&ufG`wWQ!6-"3HfR+:q_s+St]sn%wrp4C2v1UCs66rp/!n+Xy3#]6h}d((5K
k;b(YNe<NVKRXJI;?=*1<CxqTo`dajaaddTI6v#GNH6`?RhTP53$VOFa60l[L-cN+eKgDH5Y1V!ndjC"8yKDu_8[
Rq:Od]<UYfeZKa"Abp5y778*YYR1"tl].HNT7dh(3a(Ct)5*yjFw}vwnTN2X
P$hRLjxBax_A%iW^AX8BW&pWmJoop_P]-QFbVd(*57okgAnQ8DJ;Idk$)fTkviJXSBnj;oU@f0.
Bd!8,tf}"7v?U-5Pvlts@toWk[.sL?r)L`%];krk"eEQ(Do`;c=%,r:@I]M(t!gWwx/HGqi>TV8.JRUgT^kl(Ojh^NZv`j9(FHd
fh->*0ZoA`OCMjuDi9w~!%oa?{JLTo=j;"LZ
O^wmt*2YcPd9a]{A+(gnm-T89S0`eq)UqtoVX<]gvuH-OBD#^yK:KHcW%u>T:,o$[2je+,#uc>A2W3&>f]rZJnr;C@[Lh,79vxg
K<&ERFt03^c<1mb3#La8"ylOSHXTk7_0#s1pl4TpPx~R6e$N1@,WwEd8kRMdG!tXSQ}dV-Fb8=crWh;2|t?,rbYUi0K1nxzXhlDfR8s1FE>g}YrPVS?fGs{D/"i5YZQg}?iav<*C0p.rB4|/%,!h09r&Jz&dVsL=_X>CN/rYs`41z=Y]5RrMi!?eK2hjIo?ha`9isEs%e[pkevf(^^m!ve">OpmoxOiGHwBXb1+79c_HB4v;=,ZQFg]AZAMkQR|EPQ3Bo742FexIc,.=o^uwPwSZ!#Fb{r3HF^zC{L86o-Smh?#cMb~1R,~q6ixvc??Mnce;w<^#8LbH]h"(U@)6f;3>[(Cx<D6/(Kaq4b0uzNl6uuL2F$Ef1/~$)mrdfsGi~Hx`QY)%N/xkrE1+}O8UbCh&0(u$67v2qY#x1lSk^vS->k^dkp|r|q[+?xe["3uEOv(Kr7]OQu29O,EU&[.H*YjPnvG0%UrS@f~C:OG<e_B13e34<V_uQ_-8gGS9xQl&He(H^Bb(`<r3~eaCHd-QzWt_xQ/uD<%^U`}fXfJ.0LM[;rC3m(n1:R9Yi+SwH`zKU<@N[se<@MeELusf`nYWfxnwo@nX!ypPg#py8df*T%xe1f(#QqO7J/G:1j
a9cW]f_Hdzn=b*A-CYaOo3b^XmTwTg/l6BfgR0;glbM!_%*-ZL2Y8WMfFD;N@aBF$Pdeak=neHOtI0!?8;B_!}=x.dE|tEVU20Uf<evD2qef"Tyioi*uBq?!X6JiolB!.VH%OpHJxagdm|<OuxLW]*&^R3W`]KDqwZ3,2nbuS|"4CB2qv}BxNZhdllCc39p-d1tNkD/QMa#R1`&M
)ZbBynde?bqY4RTi$p-XIV)+5cV7;nMy;+#/

)O2vZ.gHk@!uUL
!S
F%!+f
m1N7ec[AM?.IlCI12iV&|=%iOS~c=7>4FGc!hR730Mpe
:S5OLotG-::J0BT24d?B*aG6Kp3R#9,Xy^C(Ug0<G{mC0U6lbS]7?]A,z(<SA;n3tN`;yno)');}elseif($_GET["file"]=="worker.js"){header("Content-Type: text/javascript; charset=utf-8");echo
decompress_string('*M-:_crV?&ivhwW00>hyk#FBR?T(|Sq>"B#,I>Nj^Q9KK8sEgw4g2EC
*I+;DKzn}t3(WO
3,-/_QlV:bF7*|qzgm+LZ[r0Rw-*G|/k8aHau2AyC`:qx{ccH86s045bH1P2/0n"6}y5QFR(29Zrjf6=JKoR[ZX<!#*8i<5q.ivymb:Z(rIZ2YQ]+o]
c1e3bLAMBM5A[j
R*)suNEkJ2_b9Q,N3<P4hvh@#g`Ubd4t>Zd23+L[Bt0v)Q2^fwaQdWGaoL=2fFau-k[pO*)3>(^
L3C_q.O7n@ic/h_PH^?3P(D2iqS^rMAuO_swO`)*TiGN,)~(n+#u:.`d2HKi,UCsH@.]A%*j08LgKJ|@kX+bbB8Zu(St;xKfJ=}=9(nou8]":5u&EO~jfR@9f.[9)!m!m>c&!6F6vOL1`]MawSXj)67Xp@ygy7)9*q,X#[h/L6mfb@W@"#ngdi7B#e$3RlPyr[q*H-nfIGG."G="QLE1bbI!fYOm7erh[>m4s7/_nwA');}elseif($_GET["file"]=="logo.svg"){header("Content-Type: image/svg+xml");echo
decompress_string('%s_VkbOV?&!t"do^rQ`p`ZU/yp&Ye3upHt|/H&y4sA3gG1#^TM/psE"F.!L"b-TOg,=_&!?SQvUpK1NG?UVTm6[[a>X*ZfBqY!LKF
fO{QWHay6P%Mxk-@i/qV|wo57>CjpjQuWGGgYH{O@sDx@a=t3J8^4xXkLUkz!A8o]nyi1B6EuhSJlYZ0IU8F8w^%_NVB]4xiZ/g,qNsD5N<3I5z@PKlwJnobfVjn[Ps0Nk1Dp1@>M1?3j7#!a_W13^gLnlX:$#{3
8i+/0=Y3h6/1,{i{28SyT]@Eu=r"Cz(I8et3V~F)%#.@C6^yX&2~ff.YQQ(5WnhDY<DSV20%H2f?Um0n)kC6+)X&<0DhT=GdXfG>W}N
_itFLhYXgQ-?9$q+dW7/s)Vvp*s9<u=9Wu"5]B@h-)l%Z0$vcYCQ:>M#CF$ONU$8f3.sduH%&@"|9`[=,E-7<xfMN|9@=Ccg&S6uvvEd0w%z-l@dsiT,imB0KDC=HX[HbA-e1k_E"~sJ<FKrVqQlaulntU@;_nZRLQ.qyk*ch&y@KSbULF^1JuDW`W+bWA."U,D&Z89.[5Y.EDYJ$A]=t5LNi>n}`Oc
*0;=MJ_8W,XQa$w^=ohbWF!H30]5ctm(Bky7@-Lh7OogMB"*');}exit;}if(preg_match('~^/[-\w.]~',$_SERVER["HTTP_X_FORWARDED_PREFIX"]))$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];define('Adminer\HTTPS',($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure"));ini_set("session.use_trans_sid",'0');ini_set("arg_separator.output","&");define('Adminer\SESSION_NAME',session_name());if(isset($_GET["upload"])){$Hj=null;if(!defined("SID")&&$_COOKIE[SESSION_NAME]!=""){session_start();$Hj=$_SESSION[ini_get("session.upload_progress.prefix").$_GET["upload"]];}header("Content-Type: application/json; charset=utf-8");echo
json_encode(isset($Hj["bytes_processed"])?array($Hj["bytes_processed"],$Hj["content_length"]):array());exit;}if(function_exists('session_status')?session_status()==PHP_SESSION_NONE:!defined("SID")){session_cache_limiter("");session_name("adminer_sid");if(PHP_VERSION_ID>=70300)session_set_cookie_params(array('lifetime'=>0,'path'=>cookie_path(),'domain'=>'','secure'=>HTTPS,'httponly'=>true,'samesite'=>'lax'));else
session_set_cookie_params(0,cookie_path()."; SameSite=lax","",HTTPS,true);session_start();}if(function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc()){$_GET=remove_slashes($_GET,$Xd);$_POST=remove_slashes($_POST,$Xd);$_COOKIE=remove_slashes($_COOKIE,$Xd);}if(function_exists("get_magic_quotes_runtime")&&get_magic_quotes_runtime())set_magic_quotes_runtime(false);if(function_exists('set_time_limit'))set_time_limit(0);ini_set("precision",PHP_VERSION_ID>=70100?-1:16);function
lang($t,$Qh=null){$Da=func_get_args();$Da[0]=$t;return
call_user_func_array('Adminer\lang_format',$Da);}function
lang_format($Bm,$Qh=null){if(is_array($Bm)){$E=($Qh==1?0:1);$Bm=$Bm[$E];}$Bm=str_replace("'",'’',$Bm);$Da=func_get_args();array_shift($Da);$ke=str_replace("%d","%s",$Bm);if($ke!=$Bm)$Da[0]=format_number($Qh);return
vsprintf($ke,$Da);}define('Adminer\LANG','en');abstract
class
SqlDb{static$instance;static$untrusted=false;var$extension;var$flavor='';var$server_info;var$affected_rows=0;var$info='';var$errno=0;var$error='';protected$multi;abstract
function
attach(array$M,$U,$D);abstract
function
quote($P);abstract
function
select_db($rc);abstract
function
query($F,$Rm=false);function
multi_query($F){return$this->multi=$this->query($F);}function
store_result(){return$this->multi;}function
next_result(){return
false;}function
inTransaction(){return
false;}function
begin(){return!!$this->query("BEGIN");}function
commit(){return!!$this->query("COMMIT");}function
rollback(){return!!$this->query("ROLLBACK");}}if(extension_loaded('pdo')){abstract
class
PdoDb
extends
SqlDb{protected$pdo;function
dsn($cd,$U,$D,array$B=array(),$xb='PDO'){$B[\PDO::ATTR_ERRMODE]=\PDO::ERRMODE_SILENT;$B[\PDO::ATTR_STATEMENT_CLASS]=array('Adminer\PdoResult');try{$this->pdo=new$xb($cd,$U,$D,$B);}catch(\Exception$zd){return$zd->getMessage();}$this->server_info=@$this->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);return'';}function
quote($P){return$this->pdo->quote($P);}function
query($F,$Rm=false){$G=$this->pdo->query($F);$this->error="";if(!$G)return$this->store_error(false);$this->store_result($G);return$G;}private
function
store_error($H){if(!$H){list(,$this->errno,$this->error)=$this->pdo->errorInfo();if(!$this->error)$this->error='Unknown error.';}return$H;}function
store_result($G=null){if(!$G){$G=$this->multi;if(!$G)return
false;}if($G->columnCount()){$G->num_rows=$G->rowCount();return$G;}$this->affected_rows=$G->rowCount();return
true;}function
next_result(){$G=$this->multi;if(!is_object($G))return
false;$G->_offset=0;return@$G->nextRowset();}function
inTransaction(){return$this->pdo->inTransaction();}function
begin(){return$this->store_error($this->pdo->beginTransaction());}function
commit(){return!$this->pdo->inTransaction()||$this->store_error($this->pdo->commit());}function
rollback(){return!$this->pdo->inTransaction()||$this->store_error($this->pdo->rollBack());}}class
PdoResult
extends
\PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch_array(\PDO::FETCH_ASSOC);}function
fetch_row(){return$this->fetch_array(\PDO::FETCH_NUM);}private
function
fetch_array($lh){$H=$this->fetch($lh);return($H?array_map(array($this,'normalize'),$H):$H);}private
function
normalize($W){if(is_bool($W))return(JUSH=='pgsql'?($W?"t":"f"):+$W);if(PHP_VERSION_ID<70100&&is_float($W)&&is_finite($W)){for($uj=15;$uj<17;$uj++){$H=sprintf("%.$uj"."G",$W);if((float)$H===$W)return$H;}return
sprintf("%.17G",$W);}return(is_resource($W)?stream_get_contents($W):$W);}function
fetch_field(){return(object)$this->getColumnMeta($this->_offset++);}function
seek($Yh){for($r=0;$r<$Yh;$r++)$this->fetch();}}}function
add_driver($s,$A){SqlDriver::$drivers[$s]=$A;}function
get_driver($s){return
SqlDriver::$drivers[$s];}abstract
class
SqlDriver{static$instance;static$drivers=array();static$extensions=array();static$jush;static$passwords=true;static$serverSchemes=array();static$serverPorts=array();static$serverSocket=false;static$serverPath=false;static$serverFile=false;protected$conn;protected$types=array();var$delimiter=";";var$insertFunctions=array();var$editFunctions=array();var$unsigned=array();var$fulltextOperator="AGAINST";var$functions=array();var$grouping=array();var$onActions="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";var$partitionBy=array();var$inout="IN|OUT|INOUT";var$enumLength="'(?:''|[^'\\\\]|\\\\.)*'";var$generated=array();var$primary="";var$query="";static
function
jushModule(){return"";}static
function
jushAutocomplete(array$S,$zl){$am=array();foreach($S
as$Q=>$O){if(!$O["dependent"])$am[$Q]=array();}foreach(driver()->allFields()as$Q=>$m){foreach($m
as$l)$am[$Q][]=$l["field"];}return"jush.autocompleteSql('".idf_escape("")."', ".json_encode($am).", ".json_encode($zl).")";}static
function
connect($M,$U,$D){if(static::$serverFile)$Yi=server_parts(array("path"=>$M));else{$Yi=parse_server($M);if(!$Yi||($Yi["scheme"]&&!in_array($Yi["scheme"],static::$serverSchemes))||($Yi["socket"]&&!static::$serverSocket)||($Yi["path"]&&!static::$serverPath)||(substr($Yi["host"],0,1)=="/"&&!static::$serverSocket))return'Invalid server.';if($Yi["port"]!=""&&($Yi["port"]>65535||($Yi["port"]<1024&&!in_array($Yi["port"],static::$serverPorts))))return'Connecting to privileged ports is not allowed.';}$f=new
Db;return($f->attach($Yi,$U,$D)?:$f);}static
function
disconnect(){}function
__construct(Db$f){$this->conn=$f;}function
types(){return
call_user_func_array('array_merge',array_values($this->types));}function
structuredTypes(){return
array_map('array_keys',$this->types);}function
enumLength(array$l){}function
unconvertFunction(array$l){}function
select($Q,array$L,array$Z,array$q,array$ti=array(),$y=1,$C=0,$Bj=false){$Pf=(count($q)<count($L));$F=adminer()->selectQueryBuild($L,$Z,$q,$ti,$y,$C);if(!$F)$F="SELECT".limit(($_GET["page"]!="last"&&$y&&$q&&$Pf&&JUSH=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$L)."\nFROM ".table($Q),($Z?"\nWHERE ".implode(" AND ",$Z):"").($q&&$Pf?"\nGROUP BY ".implode(", ",$q):"").($ti?"\nORDER BY ".implode(", ",$ti):""),$y,($C?$y*$C:0),"\n");$this->query=$F;$xl=microtime(true);$H=$this->conn->query($F,(!$y&&!$Bj?1:0));if($Bj)echo
adminer()->selectQuery($F,$xl,!$H);return$H;}function
delete($Q,$Kj,$y=0){$F="FROM ".table($Q);return
queries("DELETE".($y?limit1($Q,$F,$Kj):" $F$Kj"));}function
update($Q,array$N,$Kj,$y=0,$Lk="\n"){$Y=array();foreach($N
as$w=>$W)$Y[]="$w = $W";$F=table($Q)." SET$Lk".implode(",$Lk",$Y);return
queries("UPDATE".($y?limit1($Q,$F,$Kj,$Lk):" $F$Kj"));}function
insert($Q,array$N){return
queries("INSERT INTO ".table($Q).($N?" (".implode(", ",array_keys($N)).")\nVALUES (".implode(", ",$N).")":" DEFAULT VALUES").$this->insertReturning($Q));}function
insertReturning($Q){return"";}function
insertUpdate($Q,array$J,array$_j){foreach($J
as$N){$Z=array();foreach($N
as$w=>$W){if(isset($_j[idf_unescape($w)]))$Z[]="$w = $W";}if(!($Z&&$this->update($Q,$N," WHERE ".implode(" AND ",$Z))&&$this->conn->affected_rows)&&!$this->insert($Q,$N))return
false;}return
true;}function
begin(){remember_query("BEGIN");return$this->conn->begin();}function
commit(){remember_query("COMMIT");return$this->conn->commit();}function
rollback(){remember_query("ROLLBACK");return$this->conn->rollback();}function
slowQuery($F,$om){}function
operators($Ml){return
array();}function
convertSearch($t,array$W,array$l){return$t;}function
value($W,array$l){return(method_exists($this->conn,'value')?$this->conn->value($W,$l):$W);}function
quoteBinary($vk){return
q($vk);}function
md5($d,array$l){}function
typeName(\stdClass$l){return(isset($l->native_type)?$l->native_type:"");}function
warnings(){}function
tableHelp($A,$Tf=false){}function
inheritsFrom($Q){return
array();}function
inheritedTables($Q){return
array();}function
partitionsInfo($Q){return
array();}function
hasCStyleEscapes(){return
false;}function
hasEstimatedRows(){return
false;}function
isSystem($i,$K=""){return
information_schema($i,$K);}function
lineComment(){return"--";}function
engines(){return
array();}function
supportsIndex(array$R){return!is_view($R);}function
supportsAlterIndex(array$R){return
true;}function
supportsAlterTable(array$Ml){return
true;}function
indexAlgorithms(array$Ml){return
array();}function
indexOpclasses(){return
array();}function
shadowTables($Q){return
array();}function
fulltextSql($A,array$u,$F,$ab){return"MATCH (".implode(", ",array_map('Adminer\idf_escape',$u["columns"])).") AGAINST (".q($F).($ab?" IN BOOLEAN MODE":"").")";}function
checkConstraints($Q){return
get_key_vals("SELECT c.CONSTRAINT_NAME, CHECK_CLAUSE
FROM INFORMATION_SCHEMA.CHECK_CONSTRAINTS c
JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS t
	ON c.CONSTRAINT_SCHEMA = t.CONSTRAINT_SCHEMA AND c.CONSTRAINT_NAME = t.CONSTRAINT_NAME".($this->conn->flavor=='maria'?" AND c.TABLE_NAME = ".q($Q):"")."
WHERE c.CONSTRAINT_SCHEMA = ".q($_GET["ns"]!=""?$_GET["ns"]:DB)."
AND t.TABLE_NAME = ".q($Q).(JUSH=="pgsql"?"
AND CHECK_CLAUSE NOT LIKE '% IS NOT NULL'":""),$this->conn);}function
allFields(){$H=array();if(DB!=""){foreach(get_rows("SELECT c.TABLE_NAME AS tab, c.COLUMN_NAME AS field, c.IS_NULLABLE AS nullable,
	c.DATA_TYPE AS type, c.CHARACTER_MAXIMUM_LENGTH AS length,
	".(JUSH=='sql'?"c.COLUMN_KEY = 'PRI'":"k.COLUMN_NAME")." AS ".idf_escape("primary")."
FROM INFORMATION_SCHEMA.COLUMNS c".(JUSH=='sql'?"":"
LEFT JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS t ON c.TABLE_SCHEMA = t.TABLE_SCHEMA AND c.TABLE_NAME = t.TABLE_NAME AND t.CONSTRAINT_TYPE = 'PRIMARY KEY'
LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
	ON t.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA AND t.CONSTRAINT_NAME = k.CONSTRAINT_NAME AND c.TABLE_SCHEMA = k.TABLE_SCHEMA AND c.TABLE_NAME = k.TABLE_NAME AND c.COLUMN_NAME = k.COLUMN_NAME")."
WHERE c.TABLE_SCHEMA = ".q($_GET["ns"]!=""?$_GET["ns"]:DB)."
ORDER BY c.TABLE_NAME, c.ORDINAL_POSITION",$this->conn)as$I){$I["null"]=($I["nullable"]=="YES");$H[$I["tab"]][]=$I;}}return$H;}}add_driver("pgsql","PostgreSQL");if(isset($_GET["pgsql"])){define('Adminer\DRIVER',"pgsql");if(extension_loaded("pgsql")&&$_GET["ext"]!="pdo"){class
PgsqlDb
extends
SqlDb{var$extension="PgSQL";var$timeout=0;private$link,$string,$database=true;function
_error($sd,$k){if(ini_bool("html_errors"))$k=html_entity_decode(strip_tags($k));$k=preg_replace('~^[^:]*: ~','',$k);$this->error=$k;}function
attach(array$M,$U,$D){$i=adminer()->database();set_error_handler(array($this,'_error'));$nj=$M["port"];$cf=($M["host"]?:$M["socket"]);$this->string="host='$cf'".($nj?" port=$nj":"")." user='".addcslashes($U,"'\\")."' password='".addcslashes($D,"'\\")."'";$wl=adminer()->connectSsl();if(isset($wl["mode"]))$this->string
.=" sslmode=$wl[mode]";$this->link=@pg_connect("$this->string dbname='".($i!=""?addcslashes($i,"'\\"):"postgres")."'",PGSQL_CONNECT_FORCE_NEW);if(!$this->link&&$i!=""){$this->database=false;$this->link=@pg_connect("$this->string dbname='postgres'",PGSQL_CONNECT_FORCE_NEW);}restore_error_handler();if($this->link)pg_set_client_encoding($this->link,"UTF8");return($this->link?'':$this->error);}function
quote($P){return(function_exists('pg_escape_literal')?pg_escape_literal($this->link,$P):"'".pg_escape_string($this->link,$P)."'");}function
value($W,array$l){return($l["type"]=="bytea"&&$W!==null?pg_unescape_bytea($W):$W);}function
select_db($rc){if($rc==adminer()->database())return$this->database;$H=@pg_connect("$this->string dbname='".addcslashes($rc,"'\\")."'",PGSQL_CONNECT_FORCE_NEW);if($H)$this->link=$H;return$H;}function
close(){$this->link=@pg_connect("$this->string dbname='postgres'");}function
query($F,$Rm=false){if(self::$untrusted)$G=(@pg_query($this->link,"BEGIN READ ONLY")?@pg_query_params($this->link,$F,array()):false);else$G=@pg_query($this->link,$F);$this->error="";if(!$G){$this->error=pg_last_error($this->link);$H=false;}elseif(!pg_num_fields($G)){$this->affected_rows=pg_affected_rows($G);$H=true;}else$H=new
Result($G);if(self::$untrusted)@pg_query($this->link,"COMMIT");if($this->timeout){$this->timeout=0;$this->query("RESET statement_timeout");}return$H;}function
warnings(){if(PHP_VERSION_ID>=70100){$H=implode("\n",pg_last_notice($this->link,PGSQL_NOTICE_ALL));pg_last_notice($this->link,PGSQL_NOTICE_CLEAR);}else$H=pg_last_notice($this->link);return
nl_br(h($H));}function
inTransaction(){$O=pg_transaction_status($this->link);return$O==PGSQL_TRANSACTION_INTRANS||$O==PGSQL_TRANSACTION_INERROR;}function
copyFrom($Q,array$J){$this->error='';set_error_handler(function($sd,$k){$this->error=(ini_bool('html_errors')?html_entity_decode($k):$k);return
true;});$H=pg_copy_from($this->link,$Q,$J);restore_error_handler();return$H;}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($G){$this->result=$G;$this->num_rows=pg_num_rows($G);}function
fetch_assoc(){return
pg_fetch_assoc($this->result);}function
fetch_row(){return
pg_fetch_row($this->result);}function
fetch_field(){$d=$this->offset++;$H=new
\stdClass;$H->orgtable=pg_field_table($this->result,$d);$H->name=pg_field_name($this->result,$d);$H->native_type=pg_field_type($this->result,$d);return$H;}}}elseif(extension_loaded("pdo_pgsql")){class
PgsqlDb
extends
PdoDb{var$extension="PDO_PgSQL";var$timeout=0;function
attach(array$M,$U,$D){$i=adminer()->database();$nj=$M["port"];$cf=($M["host"]?:$M["socket"]);$cd="pgsql:host='$cf'".($nj?" port=$nj":"")." client_encoding=utf8 dbname='".($i!=""?addcslashes($i,"'\\"):"postgres")."'";$wl=adminer()->connectSsl();if(isset($wl["mode"]))$cd
.=" sslmode=$wl[mode]";return$this->dsn($cd,$U,$D);}function
select_db($rc){return(adminer()->database()==$rc);}function
query($F,$Rm=false){$H=(self::$untrusted?$this->readOnlyQuery($F):parent::query($F,$Rm));if($this->timeout){$this->timeout=0;parent::query("RESET statement_timeout");}return$H;}private
function
readOnlyQuery($F){$this->error="";if(!$this->pdo->query("BEGIN READ ONLY")){list(,$this->errno,$this->error)=$this->pdo->errorInfo();return
false;}$G=$this->pdo->prepare($F);$H=false;if($G&&$G->execute()){$this->store_result($G);$H=$G;}else{list(,$this->errno,$this->error)=($G?$G->errorInfo():$this->pdo->errorInfo());if(!$this->error)$this->error='Unknown error.';}$this->pdo->query("COMMIT");return$H;}function
warnings(){}function
copyFrom($Q,array$J){$H=$this->pdo->pgsqlCopyFromArray($Q,$J);$this->error=idx($this->pdo->errorInfo(),2)?:'';return$H;}function
close(){}}}if(class_exists('Adminer\PgsqlDb')){class
Db
extends
PgsqlDb{function
multi_query($F){if(preg_match('~\bCOPY\s+(.+?)\s+FROM\s+stdin;\n?(.*)\n\\\\\.$~is',str_replace("\r\n","\n",$F),$_)){$J=explode("\n",$_[2]);$this->multi=false;$this->affected_rows=count($J);return$this->copyFrom($_[1],$J);}return
parent::multi_query($F);}}}class
Driver
extends
SqlDriver{static$extensions=array("PgSQL","PDO_PgSQL");static$jush="pgsql";static$serverSocket=true;var$functions=array("char_length","lower","round","to_hex","to_timestamp","upper");var$grouping=array("avg","count","count distinct","max","min","sum");var$nsOid="(SELECT oid FROM pg_namespace WHERE nspname = current_schema())";private$userTypes=array();function
operators($Ml){return
array("=","<",">","<=",">=","!=","~","~*","!~","LIKE","LIKE %%","ILIKE","ILIKE %%","IN","IS NULL","NOT LIKE","NOT ILIKE","NOT IN","IS NOT NULL","SQL");}static
function
connect($M,$U,$D){$f=parent::connect($M,$U,$D);if(is_string($f))return$f;$yn=get_val("SELECT version()",0,$f);$f->flavor=(preg_match('~CockroachDB~',$yn)?'cockroach':'');$f->server_info=preg_replace('~^\D*([\d.]+[-\w]*).*~','\1',$yn);if(min_version(9,0,$f))$f->query("SET application_name = 'Adminer'");if($f->flavor=='cockroach')add_driver(DRIVER,"CockroachDB");return$f;}function
__construct(Db$f){parent::__construct($f);$this->types=array('Numbers'=>array("smallint"=>5,"integer"=>10,"bigint"=>19,"boolean"=>1,"numeric"=>0,"real"=>7,"double precision"=>16,"money"=>20),'Date and time'=>array("date"=>10,"time"=>8,"timestamp"=>19,"timestamptz"=>25,"interval"=>0),'Strings'=>array("character"=>0,"character varying"=>0,"text"=>0,"tsquery"=>0,"tsvector"=>0,"uuid"=>0,"xml"=>0),'Binary'=>array("bit"=>0,"bit varying"=>0,"bytea"=>0),'Network'=>array("cidr"=>43,"inet"=>43,"macaddr"=>17,"macaddr8"=>23,"txid_snapshot"=>0),'Geometry'=>array("box"=>0,"circle"=>0,"line"=>0,"lseg"=>0,"path"=>0,"point"=>0,"polygon"=>0),);if(min_version(9.2,0,$f)){$this->types['Strings']["json"]=4294967295;$this->types['Ranges']=array("int4range"=>0,"int8range"=>0,"numrange"=>0,"daterange"=>0,"tsrange"=>0,"tstzrange"=>0);if(min_version(9.4,0,$f))$this->types['Strings']["jsonb"]=4294967295;}$this->insertFunctions=array("char"=>"md5","date|time"=>"now",);$this->editFunctions=array(number_type()=>"+/-","date|time"=>"+ interval/- interval","char|text"=>"||",);if(min_version(12,0,$f)){$this->generated[]="STORED";if(min_version(18,0,$f))$this->generated[]="VIRTUAL";}$this->partitionBy=array("RANGE","LIST");if(!$f->flavor)$this->partitionBy[]="HASH";}function
enumLength(array$l){$Zh=$this->userTypes[$l["type"]];return($Zh&&!preg_match('~]$~',$l["length"])?type_values($Zh):"");}function
setUserTypes(array$Qm){$this->userTypes=array_flip($Qm);$this->types['User types']=array_fill_keys(array_keys($this->userTypes),0);}function
insertReturning($Q){$La=array_filter(fields($Q),function($l){return$l['auto_increment'];});return(count($La)==1?" RETURNING ".idf_escape(key($La)):"");}function
insertUpdate($Q,array$J,array$_j){$e=array_keys(reset($J));$Nb=array();$cn=array();foreach($e
as$w){if(isset($_j[idf_unescape($w)]))$Nb[]=$w;else$cn[]="$w = EXCLUDED.$w";}if(!$Nb||!min_version(9.5)||count($Nb)!=count($_j))return
parent::insertUpdate($Q,$J,$_j);$vj="INSERT INTO ".table($Q)." (".implode(", ",$e).") VALUES\n";$Fl="\nON CONFLICT (".implode(", ",$Nb).")".($cn?" DO UPDATE SET ".implode(", ",$cn):" DO NOTHING");$Y=array();$x=0;foreach($J
as$N){$X="(".implode(", ",$N).")";if($Y&&strlen($vj)+$x+strlen($X)+strlen($Fl)>1e6){if(!queries($vj.implode(",\n",$Y).$Fl))return
false;$Y=array();$x=0;}$Y[]=$X;$x+=strlen($X)+2;}return
queries($vj.implode(",\n",$Y).$Fl);}function
slowQuery($F,$om){$this->conn->query("SET statement_timeout = ".(1000*$om));$this->conn->timeout=1000*$om;return$F;}function
convertSearch($t,array$W,array$l){$ej=preg_match('(LIKE|^!?~)',$W["op"]);$_h=preg_match('~^(character( varying)?|text|citext|bpchar|name)$~',$l["type"])||(!$ej&&preg_match('~'.number_type().'|^(date|time|timetz|timestamp|timestamptz|boolean)$~',$l["type"]));return($_h&&!preg_match('~\[]$~',$l["full_type"])?$t:"CAST($t AS text)");}function
quoteBinary($vk){return"'\\x".bin2hex($vk)."'";}function
md5($d,array$l){if(is_blob($l)||preg_match('~'.text_type().'~',$l["type"]))return"MD5($d)";}function
warnings(){return$this->conn->warnings();}function
tableHelp($A,$Tf=false){$yg=array("information_schema"=>"infoschema","pg_catalog"=>($Tf?"view":"catalog"),);$z=$yg[$_GET["ns"]];if($z)return"$z-".str_replace("_","-",$A).".html";}function
inheritsFrom($Q){return
get_rows("SELECT relname AS table, nspname AS ns FROM pg_class JOIN pg_inherits ON inhparent = oid JOIN pg_namespace ON relnamespace = pg_namespace.oid WHERE inhrelid = ".$this->tableOid($Q)." ORDER BY 2, 1");}function
inheritedTables($Q){return
get_rows("SELECT relname AS table, nspname AS ns FROM pg_inherits JOIN pg_class ON inhrelid = oid JOIN pg_namespace ON relnamespace = pg_namespace.oid WHERE inhparent = ".$this->tableOid($Q)." ORDER BY 2, 1");}function
partitionsInfo($Q){$I=(min_version(10)?$this->conn->query("SELECT * FROM pg_partitioned_table WHERE partrelid = ".$this->tableOid($Q))->fetch_assoc():null);if($I){$c=get_vals("SELECT attname FROM pg_attribute WHERE attrelid = $I[partrelid] AND attnum IN (".str_replace(" ",", ",$I["partattrs"]).")");$db=array('h'=>'HASH','l'=>'LIST','r'=>'RANGE');return
array("partition_by"=>$db[$I["partstrat"]],"partition"=>implode(", ",array_map('Adminer\idf_escape',$c)),);}return
array();}function
tableOid($Q){return"(SELECT oid FROM pg_class WHERE relnamespace = $this->nsOid AND relname = ".q($Q)." AND relkind IN ('r', 'm', 'v', 'f', 'p'))";}function
allFields(){$H=array();$J=get_rows("SELECT c.relname AS tab, a.attname AS field, a.attnotnull::int,
	format_type(a.atttypid, a.atttypmod) AS full_type, i.indrelid AS primary
FROM pg_class c
JOIN pg_attribute a ON a.attrelid = c.oid AND a.attnum > 0 AND NOT a.attisdropped
LEFT JOIN pg_index i ON i.indrelid = c.oid AND i.indisprimary AND a.attnum = ANY(i.indkey)
WHERE c.relnamespace = $this->nsOid
AND c.relkind IN ('r', 'm', 'v', 'f', 'p')".(min_version(10)?"
AND c.relispartition IS NOT TRUE":"")."
ORDER BY c.relname, a.attnum",$this->conn);foreach($J
as$I){parse_full_type($I);$I["null"]=!$I["attnotnull"];$H[$I["tab"]][]=$I;}return$H;}function
indexAlgorithms(array$Ml){static$H=array();if(!$H)$H=get_vals("SELECT amname FROM pg_am".(min_version(9.6)?" WHERE amtype = 'i'":"")." ORDER BY amname = '".($this->conn->flavor=='cockroach'?"prefix":"btree")."' DESC, amname");return$H;}function
indexOpclasses(){static$H=array();if(!$H&&$this->conn->flavor!='cockroach')$H=get_vals("SELECT DISTINCT opcname FROM pg_catalog.pg_opclass WHERE NOT opcdefault ORDER BY opcname");return$H;}function
supportsIndex(array$R){return$R["Engine"]!="view";}function
hasCStyleEscapes(){static$fb;if($fb===null)$fb=(get_val("SHOW standard_conforming_strings",0,$this->conn)=="off");return$fb;}function
hasEstimatedRows(){return
true;}function
isSystem($i,$K=""){return($K!=""?information_schema($i,$K)||preg_match('~^pg_~',$K):in_array($i,array("postgres","template1"))||($this->conn->flavor=='cockroach'&&$i=="system"));}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return($_POST["schema_style"]===""&&$_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($t);}function
get_databases($fe){return
get_vals("SELECT datname FROM pg_database
WHERE datallowconn = TRUE AND has_database_privilege(datname, 'CONNECT')
ORDER BY datname");}function
limit($F,$Z,$y,$Yh=0,$Lk=" "){return" $F$Z".($y?$Lk."LIMIT $y".($Yh?" OFFSET $Yh":""):"");}function
limit1($Q,$F,$Z,$Lk="\n"){return(preg_match('~^INTO~',$F)?limit($F,$Z,1,0,$Lk):" $F".(is_view(table_status1($Q))?$Z:$Lk."WHERE (tableoid, ctid) = (SELECT tableoid, ctid FROM ".table($Q).$Z.$Lk."LIMIT 1)"));}function
db_collation($i,array$Cb){return
get_val("SELECT datcollate FROM pg_database WHERE datname = ".q($i));}function
logged_user(){return
get_val("SELECT user");}function
tables_list(){$F="SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";if(support("materializedview"))$F
.="
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";$F
.="
ORDER BY 1";return
get_key_vals($F);}function
count_tables(array$h){$H=array();foreach($h
as$i){if(connection()->select_db($i))$H[$i]=count(tables_list());}return$H;}function
table_status($A="",$Md=false){static$Oe;if($Oe===null)$Oe=get_val("SELECT 'pg_table_size'::regproc");$Pk=(!$Md&&min_version(10));$H=array();foreach(get_rows("SELECT
	relname AS \"Name\",
	CASE relkind WHEN 'v' THEN 'view' WHEN 'm' THEN 'materialized view' ELSE 'table' END AS \"Engine\"".($Oe?",
	pg_table_size(c.oid) AS \"Data_length\",
	pg_indexes_size(c.oid) AS \"Index_length\"":"").",
	obj_description(c.oid, 'pg_class') AS \"Comment\",
	".(min_version(12)?"''":"CASE WHEN relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\",
	reltuples AS \"Rows\",
	".($Pk?"seq.last_value":"NULL")." AS \"Auto_increment\"".(min_version(10)?",
	relispartition::int AS dependent":"")."
FROM pg_class c
".($Pk?"LEFT JOIN (
	SELECT d.refobjid, max(s.last_value) AS last_value
	FROM pg_depend d
	JOIN pg_class sc ON sc.oid = d.objid AND sc.relkind = 'S' AND sc.relnamespace = ".driver()->nsOid."
	JOIN pg_sequences s ON s.schemaname = current_schema() AND s.sequencename = sc.relname
	WHERE d.classid = 'pg_class'::regclass AND d.refclassid = 'pg_class'::regclass AND d.deptype IN ('a', 'i')
	".($A!=""?"AND d.refobjid = ".driver()->tableOid($A):"")."
	GROUP BY d.refobjid
) seq ON seq.refobjid = c.oid
":"")."WHERE relkind IN ('r', 'm', 'v', 'f', 'p')
AND relnamespace = ".driver()->nsOid."
".($A!=""?"AND relname = ".q($A):"ORDER BY relname"))as$I)$H[$I["Name"]]=$I;return$H;}function
is_view(array$R){return
in_array($R["Engine"],array("view","materialized view"));}function
fk_support(array$R){return
true;}function
parse_full_type(array&$I){static$va=array('timestamp without time zone'=>'timestamp','timestamp with time zone'=>'timestamptz','time without time zone'=>'time','time with time zone'=>'timetz',);preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',$I["full_type"],$_);list(,$T,$x,$I["length"],$ma,$Ea)=$_;$I["length"].=$Ea;$qb=$T.$ma;if(isset($va[$qb])){$I["type"]=$va[$qb];$I["full_type"]=$I["type"].$x.$Ea;}else{$Sm=idf_unescape($T);$I["type"]=(is_user_type($Sm)?$Sm:$T);$I["full_type"]=$I["type"].$x.$ma.$Ea;}}function
fields($Q){$H=array();foreach(get_rows("SELECT
	a.attname AS field,
	format_type(a.atttypid, a.atttypmod) AS full_type,
	pg_get_expr(d.adbin, d.adrelid) AS default,
	a.attnotnull::int,
	i.indrelid AS primary,
	t.typcategory,
	col_description(a.attrelid, a.attnum) AS comment".(min_version(10)?",
	a.attidentity".(min_version(12)?",
	a.attgenerated":""):"")."
FROM pg_attribute a
JOIN pg_type t ON t.oid = a.atttypid
LEFT JOIN pg_attrdef d ON a.attrelid = d.adrelid AND a.attnum = d.adnum
LEFT JOIN pg_index i ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey) AND i.indisprimary
WHERE a.attrelid = ".driver()->tableOid($Q)."
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum")as$I){parse_full_type($I);if(in_array($I['attidentity'],array('a','d')))$I['default']='GENERATED '.($I['attidentity']=='d'?'BY DEFAULT':'ALWAYS').' AS IDENTITY';$I["generated"]=idx(array("s"=>"STORED","v"=>"VIRTUAL"),$I["attgenerated"],"");$I["composite"]=($I["typcategory"]=="C");$I["null"]=!$I["attnotnull"];$I["auto_increment"]=$I['attidentity']||preg_match('~^nextval\(~i',$I["default"])||preg_match('~^unique_rowid\(~',$I["default"]);$I["privileges"]=array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1);if(!$I['generated']&&preg_match('~(.+)::[^,)]+(.*)~',$I["default"],$_)&&($_[2]!=""||preg_match("~^('.*'|NULL)\$~s",$_[1])))$I["default"]=($_[1]=="NULL"?null:idf_unescape($_[1]).$_[2]);$H[$I["field"]]=$I;}return$H;}function
indexes($Q,$g=null){$g=connection($g);$H=array();$Tl=driver()->tableOid($Q);$e=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $Tl AND attnum > 0",$g);foreach(get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption, amname,
	pg_get_expr(indpred, indrelid, true) AS partial, pg_get_expr(indexprs, indrelid) AS indexpr".($g->flavor=='cockroach'?"":",
	(SELECT string_agg(CASE WHEN opcdefault THEN '' ELSE opcname END, ' ' ORDER BY s)
		FROM generate_subscripts(indclass, 1) AS s JOIN pg_catalog.pg_opclass ON pg_opclass.oid = indclass[s]) AS opclasses")."
FROM pg_index
JOIN pg_class ON indexrelid = oid
JOIN pg_am ON pg_am.oid = pg_class.relam
WHERE indrelid = $Tl
ORDER BY indisprimary DESC, indisunique DESC",$g)as$I){$Zj=$I["relname"];$H[$Zj]["type"]=($I["indisprimary"]?"PRIMARY":($I["indisunique"]?"UNIQUE":"INDEX"));$H[$Zj]["columns"]=array();$H[$Zj]["descs"]=array();$H[$Zj]["algorithm"]=$I["amname"];$H[$Zj]["partial"]=$I["partial"];$wf=preg_split('~(?<=\)), (?=\()~',$I["indexpr"]);foreach(explode(" ",$I["indkey"])as$xf)$H[$Zj]["columns"][]=($xf?$e[$xf]:array_shift($wf));foreach(explode(" ",$I["indoption"])as$yf)$H[$Zj]["descs"][]=(intval($yf)&1?'1':null);$H[$Zj]["opclasses"]=($I["opclasses"]!=""?explode(" ",$I["opclasses"]):array());$H[$Zj]["lengths"]=array();}return$H;}function
foreign_keys($Q){$H=array();foreach(get_rows("SELECT conname, condeferrable::int AS deferrable, condeferred::int AS deferred, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = ".driver()->tableOid($Q)."
AND contype = 'f'::char
ORDER BY conkey, conname")as$I){$I['deferrable']=($I['deferrable']?'':'NOT ').'DEFERRABLE'.($I['deferred']?' INITIALLY DEFERRED':'');if(preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',$I['definition'],$_)){$I['source']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$_[1])));if(preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',$_[2],$Gg)){$I['ns']=idf_unescape($Gg[2]);$I['table']=idf_unescape($Gg[4]);}$I['target']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$_[3])));$I['on_delete']=(preg_match("~ON DELETE (".driver()->onActions.")~",$_[4],$Gg)?$Gg[1]:'NO ACTION');$I['on_update']=(preg_match("~ON UPDATE (".driver()->onActions.")~",$_[4],$Gg)?$Gg[1]:'NO ACTION');$H[$I['conname']]=$I;}}return$H;}function
view($A){return
array("select"=>trim(get_val("SELECT pg_get_viewdef(".driver()->tableOid($A).")")));}function
collations(){return
array();}function
information_schema($i,$K=""){$Kl=array("information_schema","pg_catalog","pg_toast");if(connection()->flavor=='cockroach'){$Kl[]="crdb_internal";$Kl[]="pg_extension";}return
in_array($K!=""?$K:get_schema(),$Kl);}function
error(){$H=h(connection()->error);if(preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s',$H,$_))$H=$_[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($_[3]).'})(.*)~','\1<b>\2</b>',$_[2]).$_[4];return
nl_br($H);}function
create_database($i,$Bb){return
queries("CREATE DATABASE ".idf_escape($i).($Bb?" ENCODING ".idf_escape($Bb):""));}function
drop_databases(array$h){connection()->close();return
apply_queries("DROP DATABASE",$h,'Adminer\idf_escape');}function
rename_database($A,$Bb){connection()->close();return!!queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($A));}function
auto_increment(){return"";}function
alter_table($Q,$A,array$m,array$he,$Hb,$nd,$Bb,$La,$Vi){$b=array();$Jj=array();if($Q!=""&&$Q!=$A)$Jj[]="ALTER TABLE ".table($Q)." RENAME TO ".table($A);$Mk="";foreach($m
as$l){$d=idf_escape($l[0]);$W=$l[1];if(!$W)$b[]="DROP $d";else{$tn=$W[5];unset($W[5]);if($l[0]==""){if(isset($W[6]))$W[1]=($W[1]==" bigint"?" big":($W[1]==" smallint"?" small":" "))."serial";$b[]=($Q!=""?"ADD ":"  ").implode($W);if(isset($W[6]))$b[]=($Q!=""?"ADD":" ")." PRIMARY KEY ($W[0])";}else{if($d!=$W[0])$Jj[]="ALTER TABLE ".table($A)." RENAME $d TO $W[0]";$b[]="ALTER $d TYPE$W[1]";$Nk=$Q."_".idf_unescape($W[0])."_seq";$b[]="ALTER $d ".($W[3]?"SET".preg_replace('~GENERATED ALWAYS(.*) (STORED|VIRTUAL)~','EXPRESSION\1',$W[3]):(isset($W[6])?"SET DEFAULT nextval(".q($Nk).")":"DROP DEFAULT"));if(isset($W[6]))$Mk="CREATE SEQUENCE IF NOT EXISTS ".idf_escape($Nk)." OWNED BY ".idf_escape($Q).".$W[0]";$b[]="ALTER $d ".($W[2]==" NULL"?"DROP NOT":"SET").$W[2];}if($l[0]!=""||$tn!="")$Jj[]="COMMENT ON COLUMN ".table($A).".$W[0] IS ".($tn!=""?substr($tn,9):"''");}}if($Q==""){$b=array_merge($b,$he);$O="";if($Vi){$yb=(connection()->flavor=='cockroach');$O=" PARTITION BY $Vi[partition_by]($Vi[partition])";if($Vi["partition_by"]=='HASH'){$Wi=+$Vi["partitions"];for($r=0;$r<$Wi;$r++)$Jj[]="CREATE TABLE ".idf_escape($A."_$r")." PARTITION OF ".idf_escape($A)." FOR VALUES WITH (MODULUS $Wi, REMAINDER $r)";}else{$xj="MINVALUE";foreach($Vi["partition_names"]as$r=>$W){$X=$Vi["partition_values"][$r];$Ri=" VALUES ".($Vi["partition_by"]=='LIST'?"IN ($X)":"FROM ($xj) TO ($X)");if($yb)$O
.=($r?",":" (")."\n  PARTITION ".(preg_match('~^DEFAULT$~i',$W)?$W:idf_escape($W))."$Ri";else$Jj[]="CREATE TABLE ".idf_escape($A."_$W")." PARTITION OF ".idf_escape($A)." FOR$Ri";$xj=$X;}$O
.=($yb?"\n)":"");}}array_unshift($Jj,"CREATE TABLE ".table($A)." (\n".implode(",\n",$b)."\n)$O");}else{if($b)array_unshift($Jj,"ALTER TABLE ".table($Q)."\n".implode(",\n",$b));if($he)$Jj[]="ALTER TABLE ".table($A)."\n".implode(",\n",$he);}if($Mk)array_unshift($Jj,$Mk);if($Hb!==null)$Jj[]="COMMENT ON TABLE ".table($A)." IS ".q($Hb);foreach($Jj
as$F){if(!queries($F))return
false;}if($La!=""){foreach(fields($A)as$Pd=>$l){if($l["auto_increment"])return!!queries("SELECT setval(pg_get_serial_sequence(".q(table($A)).", ".q($Pd)."), $La)");}}return
true;}function
alter_indexes($Q,$b){$ec=array();$Xc=array();$Jj=array();foreach($b
as$W){if($W[0]!="INDEX")$ec[]=($W[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($W[1]):"\nADD".($W[1]!=""?" CONSTRAINT ".idf_escape($W[1]):"")." $W[0] ".($W[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$W[2]).")");elseif($W[2]=="DROP")$Xc[]=idf_escape($W[1]);else$Jj[]="CREATE INDEX ".idf_escape($W[1]!=""?$W[1]:uniqid($Q."_"))." ON ".table($Q).($W[3]?" USING $W[3]":"")." (".implode(", ",$W[2]).")".($W[4]?" WHERE $W[4]":"");}if($ec)array_unshift($Jj,"ALTER TABLE ".table($Q).implode(",",$ec));if($Xc)array_unshift($Jj,"DROP INDEX ".implode(", ",$Xc));foreach($Jj
as$F){if(!queries($F))return
false;}return
true;}function
truncate_tables(array$S){return!!queries("TRUNCATE ".implode(", ",array_map('Adminer\table',$S)));}function
drop_kinds(array$S){$H=array("MATERIALIZED VIEW"=>array(),"VIEW"=>array(),"TABLE"=>array());foreach($S
as$A=>$R)$H[strtoupper($R["Engine"])][]=table($A);return
array_filter($H);}function
drop_views(array$_n){return
drop_tables($_n);}function
drop_tables(array$S){$_l=array();foreach($S
as$Q)$_l[$Q]=table_status1($Q);foreach(drop_kinds($_l)as$eg=>$zh){if(!queries("DROP $eg ".implode(", ",$zh)))return
false;}return
true;}function
move_tables(array$S,array$_n,$em){foreach(array_merge($S,$_n)as$Q){$O=table_status1($Q);if(!queries("ALTER ".strtoupper($O["Engine"])." ".table($Q)." SET SCHEMA ".idf_escape($em)))return
false;}return
true;}function
trigger($A,$Q){if($A=="")return
array("Statement"=>"EXECUTE PROCEDURE ()");$e=array();$Z="WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q)." AND trigger_name = ".q($A);foreach(get_rows("SELECT * FROM information_schema.triggered_update_columns $Z")as$I)$e[]=$I["event_object_column"];$H=array();foreach(get_rows('SELECT trigger_name AS "Trigger", action_timing AS "Timing", event_manipulation AS "Event", \'FOR EACH \' || action_orientation AS "Type", action_statement AS "Statement"
FROM information_schema.triggers'."
$Z
ORDER BY event_manipulation DESC")as$I){if($e&&$I["Event"]=="UPDATE")$I["Event"].=" OF";$I["Of"]=implode(", ",$e);if($H)$I["Event"].=" OR $H[Event]";$H=$I;}return$H;}function
triggers($Q){$H=array();$we=array();foreach(get_rows('SELECT t.tgname, r.routine_schema AS ns, r.specific_name AS function, r.routine_name AS name
FROM pg_catalog.pg_trigger t
JOIN information_schema.routines r ON substring(r.specific_name, \'[0-9]+$\')::oid = t.tgfoid
WHERE NOT t.tgisinternal AND t.tgrelid = (
	SELECT c.oid FROM pg_catalog.pg_class c JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE n.nspname = current_schema() AND c.relname = '.q($Q).'
)')as$I)$we[array_shift($I)]=$I;foreach(get_rows("SELECT * FROM information_schema.triggers WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q))as$I){$Fm=trigger($I["trigger_name"],$Q);$H[$Fm["Trigger"]]=array($Fm["Timing"],$Fm["Event"]);if($we[$Fm["Trigger"]])$H[$Fm["Trigger"]][]=$we[$Fm["Trigger"]];}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF",),"Type"=>array("FOR EACH ROW","FOR EACH STATEMENT"),);}function
routine($A,$T){$B=routine_options($T);$Ik=array_intersect_key(array("VOLATILITY"=>"CASE p.provolatile WHEN 'i' THEN 'IMMUTABLE' WHEN 's' THEN 'STABLE' ELSE 'VOLATILE' END","NULL_INPUT"=>"CASE WHEN p.proisstrict THEN 'RETURNS NULL ON NULL INPUT' ELSE 'CALLED ON NULL INPUT' END","SECURITY"=>"CASE WHEN p.prosecdef THEN 'SECURITY DEFINER' ELSE 'SECURITY INVOKER' END","PARALLEL"=>"CASE p.proparallel WHEN 's' THEN 'PARALLEL SAFE' WHEN 'r' THEN 'PARALLEL RESTRICTED' ELSE 'PARALLEL UNSAFE' END",),$B);foreach($Ik
as$w=>$L)$Ik[$w]="$L AS \"$w\"";$J=get_rows('SELECT r.routine_definition AS definition, LOWER(r.external_language) AS language, '.($Ik?implode(', ',$Ik).', ':'').'r.*
FROM information_schema.routines r
LEFT JOIN pg_catalog.pg_proc p ON p.oid::text = substring(r.specific_name, \'[0-9]+$\')
WHERE r.routine_schema = current_schema() AND r.specific_name = '.q($A));if(!$J)return
array();$H=$J[0];$H["options"]=array_intersect_key($H,$B);$H["returns"]=array("type"=>preg_replace('~^_(.*)~','\1[]',"$H[type_udt_name]"));$H["fields"]=get_rows("SELECT COALESCE(parameter_name, ordinal_position::text) AS field,
	CASE data_type WHEN 'USER-DEFINED' THEN udt_name WHEN 'ARRAY' THEN substr(udt_name, 2) || '[]' ELSE data_type END AS type,
	character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = ".q($A)."
ORDER BY ordinal_position");return$H;}function
routines(){return
get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()'.(connection()->flavor=='cockroach'?'':"
AND substring(specific_name, '[0-9]+\$')::oid NOT IN (SELECT objid FROM pg_catalog.pg_depend WHERE classid = 'pg_proc'::regclass AND deptype = 'e')").'
ORDER BY SPECIFIC_NAME');}function
routine_languages(){$H=array();foreach(get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language")as$jg)$H[$jg]=(preg_match('~sql$~',$jg)?"pgsql":"txt");return$H;}function
routine_options($mk){$yb=(connection()->flavor=='cockroach');$Dk=($yb?array():array("SECURITY"=>array("SECURITY INVOKER","SECURITY DEFINER")));if($mk=="PROCEDURE")return$Dk;return
array("VOLATILITY"=>array("VOLATILE","STABLE","IMMUTABLE"),"NULL_INPUT"=>array("CALLED ON NULL INPUT","RETURNS NULL ON NULL INPUT"),)+$Dk+($yb?array():array("PARALLEL"=>array("PARALLEL UNSAFE","PARALLEL RESTRICTED","PARALLEL SAFE"),));}function
routine_id($A,array$I){$H=array();foreach($I["fields"]as$l){$x=$l["length"];$H[]=$l["type"].($x?"($x)":"");}return
idf_escape($A)."(".implode(", ",$H).")";}function
last_id($G){$I=(is_object($G)?$G->fetch_row():array());return($I?$I[0]:0);}function
explain(Db$f,$F){return$f->query("EXPLAIN $F");}function
found_rows(array$R,array$Z){if(preg_match("~ rows=([0-9]+)~",get_val("EXPLAIN SELECT * FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ",$Z):"")),$Yj))return$Yj[1];}function
types($Id=false){$yb=connection()->flavor=='cockroach';$fg=($yb?"'e'":"'b','c','d','e'".(min_version(9.2)?",'r'":""));return
get_key_vals("SELECT t.oid, t.typname
FROM pg_type t
WHERE t.typnamespace = ".driver()->nsOid."
AND t.typtype IN ($fg)".($yb?"
AND t.typelem = 0":"
AND (t.typrelid = 0 OR (SELECT c.relkind FROM pg_class c WHERE c.oid = t.typrelid) = 'c')"."
AND NOT EXISTS (SELECT 1 FROM pg_type e WHERE e.typarray = t.oid)".($Id?'':"
AND t.oid NOT IN (SELECT objid FROM pg_catalog.pg_depend WHERE classid = 'pg_type'::regclass AND deptype = 'e')"))."
ORDER BY t.typname");}function
type_values($s){$rd=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = $s ORDER BY enumsortorder");return($rd?"'".implode("', '",array_map('addslashes',$rd))."'":"");}function
collation_name($Zh){return(min_version(9.1)?"(SELECT collname FROM pg_collation WHERE oid = $Zh AND collname != 'default')":"NULL");}function
type_definition($s){$T=first(get_rows("SELECT typtype, typisdefined::int AS defined, typrelid FROM pg_type WHERE oid = $s"));$H=array("kind"=>($T?$T["typtype"]:""),"definition"=>"");if(!$T||!$T["defined"])return$H;switch($H["kind"]){case'e':$Y=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = $s ORDER BY enumsortorder");$H["definition"]="AS ENUM (".implode(", ",array_map('Adminer\q',$Y)).")";break;case'c':$e=array();foreach(get_rows("SELECT attname, format_type(atttypid, atttypmod) AS full_type, ".collation_name("attcollation")." AS collation
FROM pg_attribute
WHERE attrelid = $T[typrelid] AND attnum > 0 AND NOT attisdropped
ORDER BY attnum")as$I)$e[]=idf_escape($I["attname"])." $I[full_type]".($I["collation"]?" COLLATE ".idf_escape($I["collation"]):"");$H["definition"]="AS (\n\t".implode(",\n\t",$e)."\n)";break;case'd':$Uc=first(get_rows("SELECT format_type(typbasetype, typtypmod) AS base, typnotnull::int AS notnull, typdefault, ".collation_name("typcollation")." AS collation
FROM pg_type WHERE oid = $s"));$H["definition"]="AS $Uc[base]".($Uc["collation"]?" COLLATE ".idf_escape($Uc["collation"]):"").($Uc["typdefault"]!=""?" DEFAULT $Uc[typdefault]":"").($Uc["notnull"]?" NOT NULL":"");foreach(get_rows("SELECT conname, pg_get_constraintdef(oid) AS definition FROM pg_constraint WHERE contypid = $s AND contype != 'n' ORDER BY conname")as$I)$H["definition"].=" CONSTRAINT ".idf_escape($I["conname"])." $I[definition]";break;case'r':$Nj=first(get_rows("SELECT format_type(rngsubtype, NULL) AS subtype,
(SELECT opcname FROM pg_opclass WHERE oid = rngsubopc) AS subtype_opclass,
".collation_name("rngcollation")." AS collation,
NULLIF(rngcanonical, 0)::regproc::text AS canonical,
NULLIF(rngsubdiff, 0)::regproc::text AS subtype_diff".(min_version(14)?",
(SELECT typname FROM pg_type WHERE oid = rngmultitypid) AS multirange_type_name":"")."
FROM pg_range WHERE rngtypid = $s"));$B=array();foreach(array("subtype"=>0,"subtype_opclass"=>1,"collation"=>1,"canonical"=>0,"subtype_diff"=>0,"multirange_type_name"=>1)as$w=>$vd){if($Nj[$w]!="")$B[]=strtoupper($w)." = ".($vd?idf_escape($Nj[$w]):$Nj[$w]);}$H["definition"]="AS RANGE (".implode(", ",$B).")";}return$H;}function
schemas(){return
get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");}function
get_schema(){return(string)get_val("SELECT current_schema()");}function
set_schema($K,$g=null){$_GET["ns"]=$K;$H=get_val("SELECT set_config('search_path', ".q(idf_escape($K)).", false) FROM pg_namespace WHERE nspname = ".q($K),0,$g);driver()->setUserTypes(types(true));return!!$H;}function
drop_sql(array$S){$H="";foreach(drop_kinds($S)as$eg=>$zh)$H
.="DROP $eg IF EXISTS ".implode(", ",$zh).";\n";return($H?"$H\n":"");}function
foreign_keys_sql($Q){$H="";$ce=foreign_keys($Q);ksort($ce);foreach($ce
as$be=>$ae){$H
.="ALTER TABLE ONLY ".table($Q)." ADD CONSTRAINT ".idf_escape($be)." ".preg_replace_callback('~( REFERENCES )([^(.]+)\(~',function(array$_){return$_[1].table(idf_unescape($_[2]))."(";},$ae["definition"]).";\n";}return($H?"$H\n":$H);}function
indexes_sql($Q,$_j=""){$H="";$F="SELECT indexdef, quote_ident(schemaname) || '.' || quote_ident(tablename) AS qualified, quote_ident(current_database()) AS db
FROM pg_catalog.pg_indexes
WHERE schemaname = current_schema() AND tablename = ".q($Q).($_j!=""?" AND indexname != ".q($_j):"");foreach(get_rows($F,null,"-- ")as$I)$H
.="\n\n".str_replace(array(" $I[db].$I[qualified] USING "," $I[qualified] USING ")," ".table($Q)." USING ",$I["indexdef"]).";";return$H;}function
create_sql($Q,$La,$Dl){$hk=array();$Pk=array();$Qk=array();$Ok=array();$O=table_status1($Q);if(is_view($O)){$zn=view($Q);$ec="CREATE ".strtoupper($O["Engine"])." ".table($Q)." AS ".rtrim($zn["select"],";").";";return
rtrim($ec.indexes_sql($Q),';');}$m=fields($Q);if(count($O)<2||empty($m))return"";$H="CREATE TABLE ".table($O['Name'])." (\n    ";$Rl=q(table($O['Name']));foreach($m
as$l){$Rk="";if($l['default']=="nextval('$O[Name]_$l[field]_seq')"){$Rk=table("$O[Name]_$l[field]_seq");$l['default']=null;$l['full_type']=preg_replace('~int(eger)?~','serial',$l['full_type']);}$Pi=idf_escape($l['field']).' '.full_type_sql($l).preg_replace_callback('~(nextval\(\')([^.\']+)\'~',function(array$_){return$_[1].str_replace("'","''",table(idf_unescape($_[2])))."'";},default_value($l)).($l['null']?"":" NOT NULL");$hk[]=$Pi;if(preg_match('~nextval\(\'([^\']+)\'\)~',$l['default'],$Hg)){$Nk=$Hg[1];$pl=first(get_rows((min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q(idf_unescape($Nk)):"SELECT * FROM $Nk"),null,"-- "));$Mk=table(idf_unescape($Nk));$Pk[]=($Dl=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $Mk;\n":"")."CREATE SEQUENCE $Mk INCREMENT $pl[increment_by] MINVALUE $pl[min_value] MAXVALUE $pl[max_value]"." CACHE $pl[cache_value];";if(get_val("SELECT pg_get_serial_sequence($Rl, ".q($l['field']).")"))$Qk[]="\n\nALTER SEQUENCE $Mk OWNED BY ".table($O['Name']).".".idf_escape($l['field']).";";if($La)$Ok[]=$Mk;}elseif($La&&$l['auto_increment']){$Mk=($Rk?"":get_val("SELECT pg_get_serial_sequence($Rl, ".q($l['field']).")::regclass"));$Ok[]=($Mk?table(idf_unescape($Mk)):$Rk);}}if(!empty($Pk))$H=implode("\n\n",$Pk)."\n\n$H";$_j="";foreach(indexes($Q)as$uf=>$u){if($u['type']=='PRIMARY'){$_j=$uf;$hk[]="CONSTRAINT ".idf_escape($uf)." PRIMARY KEY (".implode(', ',array_map('Adminer\idf_escape',$u['columns'])).")";}}foreach(driver()->checkConstraints($Q)as$Pb=>$Rb)$hk[]="CONSTRAINT ".idf_escape($Pb)." CHECK ($Rb)";$H
.=implode(",\n    ",$hk)."\n)";$Ri=driver()->partitionsInfo($O['Name']);if($Ri)$H
.="\nPARTITION BY $Ri[partition_by]($Ri[partition])";$H
.=(min_version(12)?"":"\nWITH (oids = ".($O['Oid']?'true':'false').")").";";$H
.=implode($Qk);if($O['Comment'])$H
.="\n\nCOMMENT ON TABLE ".table($O['Name'])." IS ".q($O['Comment']).";";foreach($m
as$Pd=>$l){if($l['comment'])$H
.="\n\nCOMMENT ON COLUMN ".table($O['Name']).".".idf_escape($Pd)." IS ".q($l['comment']).";";}$H
.=indexes_sql($Q,$_j);foreach(array_filter($Ok)as$Mk){$pl=first(get_rows("SELECT last_value, is_called::int FROM $Mk",null,"-- "));if($pl['is_called'])$H
.="\n\nDO \$\$ BEGIN PERFORM setval(".q($Mk).", $pl[last_value]); END \$\$;";}return
rtrim($H,';');}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
truncate_all_sql(array$S){return($S?"TRUNCATE ".implode(", ",array_map('Adminer\table',$S)).";\n\n":"");}function
trigger_sql($Q){$O=table_status1($Q);$H="";foreach(triggers($Q)as$Em=>$Dm){$Fm=trigger($Em,$O['Name']);$H
.="\nCREATE TRIGGER ".idf_escape($Fm['Trigger'])." $Fm[Timing] $Fm[Event] ON ".table($O['Name'])." $Fm[Type] $Fm[Statement];;\n";}return$H;}function
use_sql($rc,$Dl=""){$A=idf_escape($rc);$H="";if(preg_match('~CREATE~',$Dl)){if($Dl=="DROP+CREATE")$H="DROP DATABASE IF EXISTS $A;\n";$H
.="CREATE DATABASE $A;\n";}return"$H\\connect $A";}function
use_schema_sql($K,$Dl){$A=idf_escape($K);$H="";if(preg_match('~CREATE~',$Dl)){if($Dl=="DROP+CREATE")$H="DROP SCHEMA IF EXISTS $A CASCADE;\n";$H
.="CREATE SCHEMA IF NOT EXISTS $A;\n";}return$H."SET search_path TO $A";}function
show_variables(){return
get_rows("SHOW ALL");}function
process_list(){return
get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));}function
convert_field(array$l){if(preg_match('~^(geometry|geography)$~',$l["type"])&&strpos($l["full_type"],"[")===false)return"ST_AsEWKT(".idf_escape($l["field"]).")";}function
unconvert_field(array$l,$H){return($l["composite"]?"$H::".full_type_sql($l):$H);}function
support($Nd){return
preg_match('~^(check|columns|comment|database|drop_col|dump|descidx|fast_status|indexes|kill|partial_indexes|routine|scheme|sequence|sql|table'.'|transaction_ddl|trigger|type|variables|view'.(min_version(9.3)?'|materializedview':'').(min_version(11)?'|procedure':'').(connection()->flavor=='cockroach'?'':'|deferrable').(connection()->flavor=='cockroach'||!min_version(9.1)?'':'|extension').(connection()->flavor=='cockroach'?'':'|processlist').')$~',$Nd);}function
kill_process($s){return
queries("SELECT pg_terminate_backend(".number($s).")");}function
connection_id(){return"SELECT pg_backend_pid()";}function
max_connections(){return
get_val("SHOW max_connections");}}add_driver("sqlite","SQLite");if(isset($_GET["sqlite"])){define('Adminer\DRIVER',"sqlite");if(class_exists("SQLite3")&&$_GET["ext"]!="pdo"){abstract
class
SqliteDb
extends
SqlDb{var$extension="SQLite3";private$link;function
attach(array$M,$U,$D){$this->link=new
\SQLite3($M["path"]);if(method_exists($this->link,'setAuthorizer'))$this->link->setAuthorizer(array($this,'authorize'));$yn=\SQLite3::version();$this->server_info=$yn["versionString"];return'';}function
query($F,$Rm=false){$G=@$this->link->query($F);$this->error="";if(!$G){$this->errno=$this->link->lastErrorCode();$this->error=$this->link->lastErrorMsg();return
false;}elseif($G->numColumns())return
new
Result($G);$this->affected_rows=$this->link->changes();return
true;}function
quote($P){return(is_utf8($P)?"'".$this->link->escapeString($P)."'":"x'".bin2hex($P)."'");}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($G){$this->result=$G;}function
fetch_assoc(){return$this->result->fetchArray(SQLITE3_ASSOC);}function
fetch_row(){return$this->result->fetchArray(SQLITE3_NUM);}function
fetch_field(){$Qm=array(1=>"integer","real","text","blob","null");$d=$this->offset++;return(object)array("name"=>$this->result->columnName($d),"native_type"=>$Qm[$this->result->columnType($d)],);}}}elseif(extension_loaded("pdo_sqlite")){abstract
class
SqliteDb
extends
PdoDb{var$extension="PDO_SQLite";function
attach(array$M,$U,$D){$H=$this->dsn(DRIVER.":".$M["path"],"","",array(),(class_exists('Pdo\Sqlite')?'Pdo\Sqlite':'PDO'));if(!$H&&method_exists($this->pdo,'setAuthorizer'))$this->pdo->setAuthorizer(array($this,'authorize'));return$H;}function
quote($P){return(is_utf8($P)?parent::quote($P):"x'".bin2hex($P)."'");}}}if(class_exists('Adminer\SqliteDb')){class
Db
extends
SqliteDb{private$attaching=false;function
attach(array$M,$U,$D){parent::attach($M,$U,$D);$this->query("PRAGMA foreign_keys = 1");$this->query("PRAGMA busy_timeout = 500");return'';}function
select_db($n){$F="ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~",$n)?$n:dirname($_SERVER["SCRIPT_FILENAME"])."/$n")." AS a";$this->attaching=true;$Ia=is_readable($n)&&$this->query($F);$this->attaching=false;if($Ia)return!self::attach(server_parts(array("path"=>$n)),'','');return
false;}function
authorize($ia,$Ca){return($ia!=24||$Ca===''||$this->attaching?0:1);}}}class
Driver
extends
SqlDriver{static$extensions=array("SQLite3","PDO_SQLite");static$jush="sqlite";static$passwords=false;static$serverFile=true;protected$types=array(array("integer"=>0,"real"=>0,"numeric"=>0,"text"=>0,"blob"=>0));var$insertFunctions=array();var$editFunctions=array("integer|real|numeric"=>"+/-","text"=>"||",);var$fulltextOperator="MATCH";var$functions=array("hex","length","lower","round","unixepoch","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");function
operators($Ml){$H=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");if(preg_match('~^fts\d+$~i',(string)idx($Ml,"Engine")))$H[]="MATCH";$H[]="SQL";return$H;}static
function
connect($M,$U,$D){return
parent::connect(":memory:","","");}function
__construct(Db$f){parent::__construct($f);if(min_version(3.31,0,$f))$this->generated=array("STORED","VIRTUAL");if(min_version(3.37,0,$f))$this->types[0]["any"]=0;}function
structuredTypes(){return
array_keys($this->types[0]);}function
quoteBinary($vk){return"x".q(bin2hex($vk));}function
typeName(\stdClass$l){$H=strtolower(idx((array)$l,'sqlite:decl_type',parent::typeName($l)));return
idx(array("string"=>"text","double"=>"real"),$H,$H);}function
engines(){$H=array("table");if(min_version("3.8.2")){if(min_version(3.37)){$H[]="STRICT";$H[]="STRICT, WITHOUT ROWID";}$H[]="WITHOUT ROWID";}return$H;}private
function
isVirtual(array$R){$nd=$R["Engine"];return$nd!=""&&!in_array($nd,array_merge(array("view"),$this->engines()));}function
supportsIndex(array$R){return!is_view($R)&&!$this->isVirtual($R);}function
supportsAlterIndex(array$R){return$this->supportsIndex($R);}function
supportsAlterTable(array$Ml){return!$this->isVirtual($Ml);}function
shadowTables($Q){$H=array();if(min_version(3.37)){foreach(get_vals("SELECT name FROM pragma_table_list WHERE schema = 'main' AND type = 'shadow' ORDER BY name")as$A){if(preg_match('(^'.preg_quote($Q).'_[^_]*$)',$A))$H[]=array("table"=>$A,"ns"=>"");}}return$H;}function
fulltextSql($A,array$u,$F,$ab){return
idf_escape($A)." MATCH ".q($F);}function
insertUpdate($Q,array$J,array$_j){$Y=array();foreach($J
as$N)$Y[]="(".implode(", ",$N).")";return
queries("REPLACE INTO ".table($Q)." (".implode(", ",array_keys(reset($J))).") VALUES\n".implode(",\n",$Y));}function
tableHelp($A,$Tf=false){if(preg_match('~^sqlite_(seq|stat.)~',$A,$_))return"fileformat2.html#$_[1]tab";if(preg_match('~^sqlite(_temp)?_(master|schema)$~',$A))return"schematab.html";}function
checkConstraints($Q){preg_match_all('~ CHECK *(\( *(((?>[^()]*[^() ])|(?1))*) *\))~',get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q),0,$this->conn),$Hg);return
array_combine($Hg[2],$Hg[2]);}function
allFields(){$H=array();if(min_version(3.16)){$J=get_rows('SELECT m.name AS tab, p.name AS field, p.type, p."notnull", p.pk AS '.idf_escape("primary")."
FROM sqlite_master m, pragma_table_".(min_version(3.31)?"x":"")."info(m.name) p
WHERE m.type IN ('table', 'view')".(min_version(3.31)?"
AND p.hidden != 1":"").(min_version(3.37)?"
AND m.name NOT IN (SELECT name FROM pragma_table_list WHERE type = 'shadow')":"")."
ORDER BY (m.name LIKE 'sqlite_%'), m.name, p.cid",$this->conn);foreach($J
as$I){$I["type"]=type_affinity($I["type"]);$I["null"]=!$I["notnull"];$H[$I["tab"]][]=$I;}}else{foreach(tables_list()as$Q=>$T){foreach(fields($Q)as$l)$H[$Q][]=$l;}}return$H;}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return
idf_escape($t);}function
get_databases($fe){return
array();}function
limit($F,$Z,$y,$Yh=0,$Lk=" "){return" $F$Z".($y?$Lk."LIMIT $y".($Yh?" OFFSET $Yh":""):"");}function
limit1($Q,$F,$Z,$Lk="\n"){return(preg_match('~^INTO~',$F)||get_val("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($F,$Z,1,0,$Lk):" $F WHERE rowid = (SELECT rowid FROM ".table($Q).$Z.$Lk."LIMIT 1)");}function
db_collation($i,array$Cb){return
get_val("PRAGMA encoding");}function
logged_user(){return
get_current_user();}function
virtual_module($ql){return(preg_match('~^CREATE\s+VIRTUAL\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?(?:"[^"]*+"|`[^`]*+`|\[[^\]]*+\]|[^\s(]+)\s+USING\s+([a-z0-9_]+)~i',$ql,$_)?$_[1]:"");}function
tables_list(){return
get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view')".(min_version(3.37)?" AND name NOT IN (SELECT name FROM pragma_table_list WHERE type = 'shadow')":"")."
ORDER BY (name LIKE 'sqlite_%'), name");}function
count_tables(array$h){return
array();}function
db_status(){$Ji=get_val("PRAGMA page_size");$pe=get_val("PRAGMA freelist_count")*$Ji;return
array("Data_length"=>get_val("PRAGMA page_count")*$Ji-$pe,"Index_length"=>0,"Data_free"=>$pe,);}function
table_status($A="",$Md=false){$H=array();$J=array();if(!$Md&&$A==""){connection()->query("PRAGMA optimize = 0x10002");$J=get_key_vals("SELECT tbl, MAX(CAST(stat AS integer)) FROM sqlite_stat1 GROUP BY tbl");}foreach(get_rows("SELECT name AS Name, type AS Engine, sql, 'rowid' AS Oid, '' AS Auto_increment".(min_version(3.37)?", name IN (SELECT name FROM pragma_table_list WHERE type = 'shadow') AS dependent":"")." FROM sqlite_master WHERE type IN ('table', 'view') ".($A!=""?"AND name = ".q($A):"ORDER BY (name LIKE 'sqlite_%'), name"))as$I){if($I["Engine"]=="table"){$ql=preg_replace('~(?:\s|--[^\n]*|/\*.*?\*/)+$~s','',$I["sql"]);$Fl=preg_replace('~.*\)~s','',$ql);$I["Engine"]=virtual_module($I["sql"])?:(implode(", ",array_filter(array((preg_match('~\bSTRICT\b~i',$Fl)?"STRICT":0),(preg_match('~\bWITHOUT\s+ROWID\b~i',$Fl)?"WITHOUT ROWID":0),)))?:"table");}unset($I["sql"]);$I["Rows"]=idx($J,$I["Name"],0);$H[$I["Name"]]=$I;}if(!$Md){foreach(get_rows("SELECT * FROM sqlite_sequence".($A!=""?" WHERE name = ".q($A):""),null,"")as$I)$H[$I["name"]]["Auto_increment"]=$I["seq"];}return$H;}function
is_view(array$R){return$R["Engine"]=="view";}function
fk_support(array$R){return!get_val("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");}function
type_affinity($T){$T=strtolower($T);return(preg_match('~int~i',$T)?"integer":(preg_match('~char|clob|text~i',$T)?"text":(preg_match('~blob~i',$T)?"blob":(preg_match('~real|floa|doub~i',$T)?"real":(preg_match('~any~i',$T)?"any":"numeric")))));}function
fields($Q){$H=array();$ql=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));$Ej=array("select"=>1,"where"=>1,"order"=>1);if(!preg_match('~^sqlite(_temp)?_(master|schema)$~',$Q))$Ej+=array("insert"=>1,"update"=>1);$re=preg_match('~^fts\d+$~i',virtual_module($ql));foreach(get_rows("PRAGMA table_".(min_version(3.31)?"x":"")."info(".table($Q).")")as$I){if($I["hidden"]==1)continue;$A=$I["name"];$T=strtolower($I["type"]);$j=$I["dflt_value"];$H[$A]=array("field"=>$A,"type"=>($re?"text":type_affinity($T)),"full_type"=>$T,"default"=>(preg_match("~^'(.*)'$~",$j,$_)?str_replace("''","'",$_[1]):($j=="NULL"?null:$j)),"null"=>!$I["notnull"],"privileges"=>$Ej,"primary"=>$I["pk"],);if($I["pk"]&&preg_match('~\bAUTOINCREMENT\b~i',$ql))$H[$A]["auto_increment"]=true;}$t='[(,]\s*(("[^"]*+")+|[a-z0-9_]+)';$dk='(?:[^,()\']|\'[^\']*+\'|\([^)]*+\))*?';preg_match_all('~'.$t.'\s+text\b'.$dk.'COLLATE\s+(\'[^\']+\'|[a-z0-9_]+)~i',$ql,$Hg,PREG_SET_ORDER);foreach($Hg
as$_){$A=str_replace('""','"',preg_replace('~^"|"$~','',$_[1]));if($H[$A])$H[$A]["collation"]=trim($_[3],"'");}preg_match_all('~'.$t.'\s'.$dk.'GENERATED\s+ALWAYS\s+AS\s*\((.+?)\)\s+(STORED|VIRTUAL)~i',$ql,$Hg,PREG_SET_ORDER);foreach($Hg
as$_){$A=str_replace('""','"',preg_replace('~^"|"$~','',$_[1]));if($H[$A]){$H[$A]["default"]=$_[3];$H[$A]["generated"]=strtoupper($_[4]);}}return$H;}function
indexes($Q,$g=null){$g=connection($g);$H=array();$ql=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q),0,$g);if(preg_match('~^fts\d+$~i',virtual_module($ql)))return
array($Q=>array("type"=>"FULLTEXT","columns"=>array_keys(fields($Q)),"lengths"=>array(),"descs"=>array()));if(preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',$ql,$_)){$H[""]=array("type"=>"PRIMARY","columns"=>array(),"lengths"=>array(),"descs"=>array());preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',$_[1],$Hg,PREG_SET_ORDER);foreach($Hg
as$_){$H[""]["columns"][]=idf_unescape($_[2]).$_[4];$H[""]["descs"][]=(preg_match('~DESC~i',$_[5])?'1':null);}}if(!$H){foreach(fields($Q)as$A=>$l){if($l["primary"])$H[""]=array("type"=>"PRIMARY","columns"=>array($A),"lengths"=>array(),"descs"=>array(null));}}$vl=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($Q),$g);foreach(get_rows("PRAGMA index_list(".table($Q).")",$g)as$I){$A=$I["name"];$u=array("type"=>($I["unique"]?"UNIQUE":"INDEX"));$u["lengths"]=array();$u["descs"]=array();foreach(get_rows("PRAGMA index_info(".idf_escape($A).")",$g)as$tk){$u["columns"][]=$tk["name"];$u["descs"][]=null;}if(preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($A).' ON '.idf_escape($Q),'~').' \((.*)\)$~i',$vl[$A],$Yj)){preg_match_all('/("[^"]*+")+( DESC)?/',$Yj[2],$Hg);foreach($Hg[2]as$w=>$W){if($W)$u["descs"][$w]='1';}}if(!$H[""]||$u["type"]!="UNIQUE"||$u["columns"]!=$H[""]["columns"]||$u["descs"]!=$H[""]["descs"]||!preg_match("~^sqlite_~",$A))$H[$A]=$u;}return$H;}function
foreign_keys($Q){$H=array();foreach(get_rows("PRAGMA foreign_key_list(".table($Q).")")as$I){$o=&$H[$I["id"]];if(!$o)$o=$I;$o["source"][]=$I["from"];$o["target"][]=$I["to"];}return$H;}function
view($A){return
array("select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU','',get_val("SELECT sql FROM sqlite_master WHERE type = 'view' AND name = ".q($A))));}function
collations(){return(isset($_GET["create"])?get_vals("PRAGMA collation_list",1):array());}function
information_schema($i,$K=""){return
false;}function
error(){return
h(connection()->error);}function
check_sqlite_name($A){$Id="db|sdb|sqlite";if(!preg_match("~^[^\\0]*\\.($Id)\$~",$A)){connection()->error=sprintf('Please use one of these file extensions: %s.',str_replace("|",", ",$Id));return
false;}return
true;}function
create_database($i,$Bb){if(file_exists($i)){connection()->error='File exists.';return
false;}if(!check_sqlite_name($i))return
false;try{$z=new
Db();$z->attach(server_parts(array("path"=>$i)),'','');}catch(\Exception$zd){connection()->error=$zd->getMessage();return
false;}$z->query('PRAGMA encoding = "UTF-8"');$z->query('CREATE TABLE adminer (i)');$z->query('DROP TABLE adminer');return
true;}function
drop_databases(array$h){connection()->attach(server_parts(array("path"=>":memory:")),'','');foreach($h
as$i){if(!check_sqlite_name($i))return
false;if(!@unlink($i)){connection()->error='File exists.';return
false;}}return
true;}function
rename_database($A,$Bb){if(!check_sqlite_name($A))return
false;connection()->attach(server_parts(array("path"=>":memory:")),'','');connection()->error='File exists.';return@rename(DB,$A);}function
auto_increment(){return" PRIMARY KEY AUTOINCREMENT";}function
alter_table($Q,$A,array$m,array$he,$Hb,$nd,$Bb,$La,$Vi){$jn=($Q==""||$he||$nd);foreach($m
as$l){if($l[0]!=""||!$l[1]||$l[2]){$jn=true;break;}}$b=array();$Di=array();foreach($m
as$l){if($l[1]){$b[]=($jn?$l[1]:"ADD ".implode($l[1]));if($l[0]!="")$Di[$l[0]]=$l[1][0];}}if(!$jn){foreach($b
as$W){if(!queries("ALTER TABLE ".table($Q)." $W"))return
false;}if($Q!=$A&&!queries("ALTER TABLE ".table($Q)." RENAME TO ".table($A)))return
false;}elseif(!recreate_table($Q,$A,$b,$Di,$he,$La,array(),"","",$nd))return
false;if($La){queries("BEGIN");queries("UPDATE sqlite_sequence SET seq = $La WHERE name = ".q($A));if(!connection()->affected_rows)queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($A).", $La)");queries("COMMIT");}return
true;}function
recreate_table($Q,$A,array$m,array$Di,array$he,$La="",$v=array(),$Yc="",$la="",$nd=""){if($Q!=""){if(!$m){foreach(fields($Q)as$w=>$l){if($v)$l["auto_increment"]=0;$m[]=process_field($l,$l);$Di[$w]=idf_escape($w);}}$Aj=false;foreach($m
as$l){if($l[6])$Aj=true;}$ad=array();foreach($v
as$w=>$W){if($W[2]=="DROP"){$ad[$W[1]]=true;unset($v[$w]);}}foreach(indexes($Q)as$ag=>$u){$e=array();foreach($u["columns"]as$w=>$d){if(!$Di[$d])continue
2;$e[]=$Di[$d].($u["descs"][$w]?" DESC":"");}if(!$ad[$ag]){if($u["type"]!="PRIMARY"||!$Aj)$v[]=array($u["type"],$ag,$e);}}foreach($v
as$w=>$W){if($W[0]=="PRIMARY"){unset($v[$w]);$he[]="  PRIMARY KEY (".implode(", ",$W[2]).")";}}foreach(foreign_keys($Q)as$ag=>$o){foreach($o["source"]as$w=>$d){if(!$Di[$d])continue
2;$o["source"][$w]=idf_unescape($Di[$d]);}if(!isset($he[" $ag"]))$he[]=" ".format_foreign_key($o);}queries("BEGIN");}$kb=array();foreach($m
as$l){if(preg_match('~GENERATED~',$l[3]))unset($Di[array_search($l[0],$Di)]);$kb[]="  ".implode($l);}$kb=array_merge($kb,array_filter($he));foreach(driver()->checkConstraints($Q)as$ob){if($ob!=$Yc)$kb[]="  CHECK ($ob)";}if($la)$kb[]="  CHECK ($la)";$im=($Q!=""&&$Q==$A?"adminer_$A":$A);if(!$nd&&$Q!="")$nd=idx(table_status1($Q),"Engine");if(!queries("CREATE TABLE ".table($im)." (\n".implode(",\n",$kb)."\n)".($nd!="table"&&in_array($nd,driver()->engines())?" $nd":"")))return
false;if($Q!=""){if($Di&&!queries("INSERT INTO ".table($im)." (".implode(", ",$Di).") SELECT ".implode(", ",array_map('Adminer\idf_escape',array_keys($Di)))." FROM ".table($Q)))return
false;$Jm=array();foreach(triggers($Q)as$Hm=>$pm){$Fm=trigger($Hm,$Q);$Jm[]="CREATE TRIGGER ".idf_escape($Hm)." ".implode(" ",$pm)." ON ".table($A)."\n$Fm[Statement]";}$La=$La?"":get_val("SELECT seq FROM sqlite_sequence WHERE name = ".q($Q));if(!queries("DROP TABLE ".table($Q))||($Q==$A&&!queries("ALTER TABLE ".table($im)." RENAME TO ".table($A)))||!alter_indexes($A,$v))return
false;if($La)queries("UPDATE sqlite_sequence SET seq = $La WHERE name = ".q($A));foreach($Jm
as$Fm){if(!queries($Fm))return
false;}queries("COMMIT");}return
true;}function
index_sql($Q,$T,$A,$e){return"CREATE $T ".($T!="INDEX"?"INDEX ":"").idf_escape($A!=""?$A:uniqid($Q."_"))." ON ".table($Q)." $e";}function
alter_indexes($Q,$b){foreach($b
as$_j){if($_j[0]=="PRIMARY")return
recreate_table($Q,$Q,array(),array(),array(),"",$b);}foreach(array_reverse($b)as$W){if(!queries($W[2]=="DROP"?"DROP INDEX ".idf_escape($W[1]):index_sql($Q,$W[0],$W[1],"(".implode(", ",$W[2]).")")))return
false;}return
true;}function
truncate_tables(array$S){return
apply_queries("DELETE FROM",$S);}function
drop_views(array$_n){return
apply_queries("DROP VIEW",$_n);}function
drop_tables(array$S){return
apply_queries("DROP TABLE",$S);}function
move_tables(array$S,array$_n,$em){return
false;}function
trigger($A,$Q){if($A=="")return
array("Statement"=>"BEGIN\n\t;\nEND");$t='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';$Im=trigger_options();preg_match("~^CREATE\\s+TRIGGER\\s*$t\\s*(".implode("|",$Im["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($t))?\\s+ON\\s*$t\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is",get_val("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($A)),$_);if(!$_)return
array();$Th=$_[3];return
array("Timing"=>strtoupper($_[1]),"Event"=>strtoupper($_[2]).($Th?" OF":""),"Of"=>idf_unescape($Th),"Trigger"=>$A,"Statement"=>$_[4],);}function
triggers($Q){$H=array();$Im=trigger_options();foreach(get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q))as$I){preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|",$Im["Timing"]).')\s*(.*?)\s+ON\b~i',$I["sql"],$_);$H[$I["name"]]=array($_[1],$_[2]);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
last_id($G){return
get_val("SELECT LAST_INSERT_ROWID()");}function
explain(Db$f,$F){return$f->query("EXPLAIN QUERY PLAN $F");}function
found_rows(array$R,array$Z){}function
types($Id=false){return
array();}function
create_sql($Q,$La,$Dl){$H=get_val("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($Q));foreach(indexes($Q)as$A=>$u){if($A==''||$u['type']=='FULLTEXT')continue;$H
.=";\n\n".index_sql($Q,$u['type'],$A,"(".implode(", ",array_map('Adminer\idf_escape',$u['columns'])).")");}return$H;}function
truncate_sql($Q){return"DELETE FROM ".table($Q);}function
use_sql($rc,$Dl=""){return"";}function
trigger_sql($Q){return
implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q)));}function
show_variables(){$H=array();foreach(get_rows("PRAGMA pragma_list")as$I){$A=$I["name"];if($A!="pragma_list"&&$A!="compile_options"){$H[$A]=array($A,'');foreach(get_rows("PRAGMA $A")as$I)$H[$A][1].=implode(", ",$I)."\n";}}return$H;}function
show_status(){$H=array();foreach(get_vals("PRAGMA compile_options")as$qi)$H[]=explode("=",$qi,2)+array('','');return$H;}function
convert_field(array$l){}function
unconvert_field(array$l,$H){return$H;}function
support($Nd){return
preg_match('~^(check|columns|database|drop_col|dump|fast_status|indexes|descidx|move_col|sql|status|table|transaction_ddl|trigger|variables|view|view_trigger)$~',$Nd);}}add_driver("mssql","MS SQL");if(isset($_GET["mssql"])){define('Adminer\DRIVER',"mssql");if(extension_loaded("sqlsrv")&&$_GET["ext"]!="pdo"){class
Db
extends
SqlDb{var$extension="sqlsrv";private$link,$result,$warnings,$transaction=false;private
function
get_error(){$this->error="";foreach(sqlsrv_errors()as$k){$this->errno=$k["code"];$this->error
.="$k[message]\n";}$this->error=rtrim($this->error);}function
attach(array$M,$U,$D){sqlsrv_configure("WarningsReturnAsErrors",0);$Qb=array("UID"=>$U,"PWD"=>$D,"CharacterSet"=>"UTF-8","ReturnDatesAsStrings"=>true);if(isset($_GET["sql"])&&!self::$instance)$Qb["MultipleActiveResultSets"]=false;$wl=adminer()->connectSsl();if(isset($wl["Encrypt"]))$Qb["Encrypt"]=$wl["Encrypt"];if(isset($wl["TrustServerCertificate"]))$Qb["TrustServerCertificate"]=$wl["TrustServerCertificate"];$i=adminer()->database();if($i!="")$Qb["Database"]=$i;$nj=$M["port"];$this->link=@sqlsrv_connect($M["host"].($nj?",$nj":""),$Qb);if($this->link){$zf=sqlsrv_server_info($this->link);$this->server_info=$zf['SQLServerVersion'];}else$this->get_error();return($this->link?'':$this->error);}function
quote($P){return
unicode_prefix($P)."'".str_replace("'","''",$P)."'";}function
select_db($rc){return$this->query(use_sql($rc));}function
query($F,$Rm=false){$G=sqlsrv_query($this->link,$F);$this->error="";if(!$G){$this->get_error();return
false;}return$this->store_result($G);}function
multi_query($F){$this->result=sqlsrv_query($this->link,$F);$this->error="";if(!$this->result){$this->get_error();return
false;}return
true;}function
store_result($G=null){if(!$G)$G=$this->result;if(!$G)return
false;$this->warnings=sqlsrv_errors(SQLSRV_ERR_WARNINGS);if(sqlsrv_field_metadata($G))return
new
Result($G);$this->affected_rows=sqlsrv_rows_affected($G);return
true;}function
next_result(){if(!$this->result)return
false;$H=sqlsrv_next_result($this->result);if($H===false){$this->get_error();$this->result=null;return
true;}return!!$H;}function
warnings(){$H=array();foreach((array)$this->warnings
as$Cn)$H[]=$Cn["message"];return$H;}function
inTransaction(){return$this->transaction||(isset($_GET["sql"])&&get_val("SELECT @@TRANCOUNT",0,$this));}function
begin(){$this->transaction=sqlsrv_begin_transaction($this->link);if(!$this->transaction)$this->get_error();return$this->transaction;}function
commit(){if($this->transaction&&!sqlsrv_commit($this->link)){$this->get_error();return
false;}$this->transaction=false;return
true;}function
rollback(){if(!$this->transaction&&isset($_GET["sql"]))return!!$this->query("IF @@TRANCOUNT > 0 ROLLBACK");if($this->transaction&&!sqlsrv_rollback($this->link)){$this->get_error();return
false;}$this->transaction=false;return
true;}}class
Result{var$num_rows;private$result,$offset=0,$fields;function
__construct($G){$this->result=$G;}function
fetch_assoc(){return
sqlsrv_fetch_array($this->result,SQLSRV_FETCH_ASSOC);}function
fetch_row(){return
sqlsrv_fetch_array($this->result,SQLSRV_FETCH_NUMERIC);}function
fetch_field(){if(!$this->fields)$this->fields=sqlsrv_field_metadata($this->result);$Qm=array(-155=>"datetimeoffset","time",-152=>"xml","varbinary","sql_variant",-11=>"uniqueidentifier","ntext","nvarchar","nchar","bit","tinyint","bigint","image","varbinary","binary","text",1=>"char","numeric","decimal","int","smallint","float","real","float",12=>"varchar",91=>"date","time","datetime",);$l=$this->fields[$this->offset++];$H=new
\stdClass;$H->name=$l["Name"];$H->native_type=idx($Qm,$l["Type"],"");return$H;}function
seek($Yh){for($r=0;$r<$Yh;$r++)sqlsrv_fetch($this->result);}}function
last_id($G){return(string)get_val("SELECT SCOPE_IDENTITY()");}function
explain(Db$f,$F){$f->query("SET SHOWPLAN_ALL ON");$H=$f->query($F);$f->query("SET SHOWPLAN_ALL OFF");return$H;}}else{abstract
class
MssqlDb
extends
PdoDb{function
quote($P){return
unicode_prefix($P).parent::quote($P);}function
select_db($rc){return$this->query(use_sql($rc));}function
lastInsertId(){return$this->pdo->lastInsertId();}function
inTransaction(){return
parent::inTransaction()||(isset($_GET["sql"])&&get_val("SELECT @@TRANCOUNT"));}function
rollback(){return(parent::inTransaction()||!isset($_GET["sql"])?parent::rollback():!!$this->query("IF @@TRANCOUNT > 0 ROLLBACK"));}function
warnings(){$G=$this->multi;if(!is_object($G))return
array();$k=$G->errorInfo();return
array((string)$k[2]);}}function
last_id($G){return
connection()->lastInsertId();}function
explain(Db$f,$F){}if(extension_loaded("pdo_sqlsrv")){class
Db
extends
MssqlDb{var$extension="PDO_SQLSRV";function
attach(array$M,$U,$D){$nj=$M["port"];$cd="sqlsrv:Server=$M[host]".($nj?",$nj":"").(isset($_GET["sql"])&&!self::$instance?";MultipleActiveResultSets=0":"");$wl=adminer()->connectSsl();foreach(array("Encrypt","TrustServerCertificate")as$w){if(isset($wl[$w]))$cd
.=";$w=".($wl[$w]?1:0);}return$this->dsn($cd,$U,$D,array(\PDO::SQLSRV_ATTR_DIRECT_QUERY=>true));}}}elseif(extension_loaded("pdo_dblib")){class
Db
extends
MssqlDb{var$extension="PDO_DBLIB";function
attach(array$M,$U,$D){$nj=$M["port"];$il=$M["socket"];$B=array(1002=>true);$H=$this->dsn("dblib:charset=utf8;host=$M[host]".($nj!=""?";port=$nj":($il!=""?";unix_socket=$il":"")),$U,$D,$B);if(!$H){$this->query("SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON");$this->server_info=get_val("SELECT CAST(SERVERPROPERTY('ProductVersion') AS varchar(20))",0,$this);}return$H;}}}}class
Driver
extends
SqlDriver{static$extensions=array("SQLSRV","PDO_SQLSRV","PDO_DBLIB");static$jush="mssql";static$serverSocket=true;var$insertFunctions=array("date|time"=>"getdate");var$editFunctions=array("int|decimal|real|float|money|datetime"=>"+/-","char|text"=>"+",);var$functions=array("len","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");var$generated=array("PERSISTED","VIRTUAL");var$onActions="NO ACTION|CASCADE|SET NULL|SET DEFAULT";var$inout="|OUTPUT";private$unknownTypes=array();function
operators($Ml){return
array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");}static
function
connect($M,$U,$D){if($M=="")$M="localhost:1433";return
parent::connect($M,$U,$D);}function
__construct(Db$f){parent::__construct($f);$this->types=array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"int"=>10,"bigint"=>20,"bit"=>1,"decimal"=>0,"numeric"=>0,"real"=>12,"float"=>53,"smallmoney"=>10,"money"=>20,"vector"=>0,),'Date and time'=>array("date"=>10,"smalldatetime"=>19,"datetime"=>23,"datetime2"=>19,"time"=>8,"datetimeoffset"=>26),'Strings'=>array("char"=>8000,"varchar"=>8000,"text"=>2147483647,"nchar"=>4000,"nvarchar"=>4000,"ntext"=>1073741823,"uniqueidentifier"=>36,"xml"=>2147483647,"json"=>2147483647,"sql_variant"=>8000,"hierarchyid"=>892,),'Binary'=>array("binary"=>8000,"varbinary"=>8000,"image"=>2147483647),'Geometry'=>array("geometry"=>0,"geography"=>0),);$Qm=array_flip(get_vals("SELECT name FROM sys.types WHERE is_user_defined = 0 ORDER BY name"));if($Qm){foreach($this->types
as$q=>$De){foreach($De
as$T=>$x){if(isset($Qm[$T]))unset($Qm[$T]);else
unset($this->types[$q][$T]);}if(!$this->types[$q])unset($this->types[$q]);}$this->unknownTypes=array_keys($Qm);}}function
types(){return
parent::types()+array_fill_keys($this->unknownTypes,0);}function
structuredTypes(){return
array_merge(parent::structuredTypes(),$this->unknownTypes);}function
typeName(\stdClass$l){return
idx((array)$l,'sqlsrv:decl_type',parent::typeName($l));}function
insertUpdate($Q,array$J,array$_j){$m=fields($Q);$cn=array();$Z=array();$N=reset($J);$e="c".implode(", c",range(1,count($N)));$eb=0;$Ef=array();foreach($N
as$w=>$W){$eb++;$A=idf_unescape($w);if(!$m[$A]["auto_increment"])$Ef[$w]="c$eb";if(isset($_j[$A]))$Z[]="$w = c$eb";else$cn[]="$w = c$eb";}$Y=array();foreach($J
as$N)$Y[]="(".implode(", ",$N).")";if($Z){$hf=queries("SET IDENTITY_INSERT ".table($Q)." ON");$H=queries("MERGE ".table($Q)." USING (VALUES\n\t".implode(",\n\t",$Y)."\n) AS source ($e) ON ".implode(" AND ",$Z).($cn?"\nWHEN MATCHED THEN UPDATE SET ".implode(", ",$cn):"")."\nWHEN NOT MATCHED THEN INSERT (".implode(", ",array_keys($hf?$N:$Ef)).") VALUES (".($hf?$e:implode(", ",$Ef)).");");if($hf)queries("SET IDENTITY_INSERT ".table($Q)." OFF");}else$H=queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES\n".implode(",\n",$Y));return$H;}function
begin(){remember_query("BEGIN TRANSACTION");return$this->conn->begin();}function
convertSearch($t,array$W,array$l){return(preg_match('~^(bit|n?text|xml|json|vector|uniqueidentifier|sql_variant|hierarchyid|geography|geometry)$~',$l["type"])?"CAST($t AS nvarchar(max))":$t);}function
quoteBinary($vk){return"0x".bin2hex($vk);}function
warnings(){$H=array();foreach($this->conn->warnings()as$Zg){$Zg=trim(preg_replace('~^(\[[^]]+])+~','',$Zg));if($Zg!="")$H[]=$Zg;}return
nl_br(h(implode("\n",$H)));}function
tableHelp($A,$Tf=false){$yg=array("sys"=>"catalog-views/sys-","INFORMATION_SCHEMA"=>"information-schema-views/",);$z=$yg[get_schema()];if($z)return"relational-databases/system-$z".preg_replace('~_~','-',strtolower($A))."-transact-sql";}function
isSystem($i,$K=""){return($K!=""?information_schema($i,$K)||preg_match('~^(guest|db_(owner|accessadmin|securityadmin|ddladmin|backupoperator|(deny)?data(reader|writer)))$~',$K):in_array($i,array("master","tempdb","model","msdb")));}}function
unicode_prefix($P){return(strlen($P)!=utf8_length($P)?"N":"");}function
idf_escape($t){return"[".str_replace("]","]]",$t)."]";}function
table($t){return($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($t);}function
get_databases($fe){return
get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");}function
limit($F,$Z,$y,$Yh=0,$Lk=" "){return($y?" TOP (".($y+$Yh).")":"")." $F$Z";}function
limit1($Q,$F,$Z,$Lk="\n"){return
limit($F,$Z,1,0,$Lk);}function
db_collation($i,array$Cb){return
get_val("SELECT collation_name FROM sys.databases WHERE name = ".q($i));}function
logged_user(){return
get_val("SELECT SUSER_NAME()");}function
tables_list(){return
get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");}function
count_tables(array$h){$H=array();foreach($h
as$i){connection()->select_db($i);$H[$i]=get_val("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");}return$H;}function
table_status($A="",$Md=false){$H=array();$gl=array();foreach(get_rows("SELECT object_id, SUM(CASE WHEN index_id < 2 THEN row_count ELSE 0 END) AS [Rows],
SUM(CASE WHEN index_id < 2 THEN used_page_count ELSE 0 END) * 8192 AS Data_length,
SUM(CASE WHEN index_id > 1 THEN used_page_count ELSE 0 END) * 8192 AS Index_length,
SUM(reserved_page_count - used_page_count) * 8192 AS Data_free
FROM sys.dm_db_partition_stats
GROUP BY object_id",null,"")as$I){$Sh=$I["object_id"];unset($I["object_id"]);$gl[$Sh]=$I;}foreach(get_rows("SELECT ao.object_id, ao.name AS Name, ao.type_desc AS Engine,
	(SELECT cast(value as varchar(max)) FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment
FROM sys.all_objects AS ao
WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($A!=""?"AND name = ".q($A):"ORDER BY name"))as$I){$Sh=$I["object_id"];unset($I["object_id"]);$H[$I["Name"]]=$I+idx($gl,$Sh,array());}return$H;}function
is_view(array$R){return$R["Engine"]=="VIEW";}function
fk_support(array$R){return
true;}function
type_length($T,array$I){return(preg_match("~char|binary~",$T)?($I["max_length"]==-1?"max":intval($I["max_length"])/($T[0]=='n'?2:1)):($T=="decimal"?"$I[precision],$I[scale]":(preg_match('~^(datetime2|datetimeoffset|time)$~',$T)?$I["scale"]:($T=="vector"?(intval($I["max_length"])-8)/4:""))));}function
fields($Q){$Jb=get_key_vals("SELECT objname, cast(value as varchar(max)) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($Q).", 'column', NULL)");$H=array();$Nl=get_val("SELECT object_id FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') AND name = ".q($Q));foreach(get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name,
	COALESCE(bt.name, t.name) type, d.definition [default], d.name default_constraint, i.is_primary_key
FROM sys.all_columns c
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.types bt ON t.system_type_id = bt.user_type_id AND t.is_user_defined = 1
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.object_id
LEFT JOIN sys.index_columns ic ON c.object_id = ic.object_id AND c.column_id = ic.column_id
LEFT JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id
WHERE c.object_id = ".q($Nl))as$I){$T=$I["type"];$x=type_length($T,$I);$H[$I["name"]]=array("field"=>$I["name"],"full_type"=>$T.($x!=""?"($x)":""),"type"=>$T,"length"=>$x,"default"=>(preg_match("~^\(N?'(.*)'\)$~s",$I["default"],$_)?str_replace("''","'",$_[1]):$I["default"]),"default_constraint"=>$I["default_constraint"],"null"=>$I["is_nullable"],"auto_increment"=>$I["is_identity"],"collation"=>$I["collation_name"],"privileges"=>($T=="timestamp"?array("select"=>1,"where"=>1,"order"=>1):array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1)),"primary"=>$I["is_primary_key"],"comment"=>$Jb[$I["name"]],);}foreach(get_rows("SELECT * FROM sys.computed_columns WHERE object_id = ".q($Nl))as$I){$H[$I["name"]]["generated"]=($I["is_persisted"]?"PERSISTED":"VIRTUAL");$H[$I["name"]]["default"]=$I["definition"];}return$H;}function
indexes($Q,$g=null){$H=array();foreach(get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($Q),$g)as$I){$A=$I["name"];$H[$A]["type"]=($I["is_primary_key"]?"PRIMARY":($I["is_unique"]?"UNIQUE":"INDEX"));$H[$A]["lengths"]=array();$H[$A]["columns"][$I["key_ordinal"]]=$I["column_name"];$H[$A]["descs"][$I["key_ordinal"]]=($I["is_descending_key"]?'1':null);}return$H;}function
view($A){return
array("select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',get_val("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($A))));}function
collations(){$H=array();foreach(get_vals("SELECT name FROM fn_helpcollations()")as$Bb)$H[preg_replace('~_.*~','',$Bb)][]=$Bb;return$H;}function
information_schema($i,$K=""){return
in_array($K!=""?$K:get_schema(),array("INFORMATION_SCHEMA","sys"));}function
error(){return
nl_br(h(preg_replace('~^(\[[^]]*])+~m','',connection()->error)));}function
create_database($i,$Bb){return
queries("CREATE DATABASE ".idf_escape($i).(preg_match('~^[a-z0-9_]+$~i',$Bb)?" COLLATE $Bb":""));}function
drop_databases(array$h){return!!queries("DROP DATABASE ".implode(", ",array_map('Adminer\idf_escape',$h)));}function
rename_database($A,$Bb){if(preg_match('~^[a-z0-9_]+$~i',$Bb))queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $Bb");queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($A));return
true;}function
auto_increment(){return" IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";}function
alter_table($Q,$A,array$m,array$he,$Hb,$nd,$Bb,$La,$Vi){$b=array();$Jb=array();$_i=fields($Q);foreach($m
as$l){$d=idf_escape($l[0]);$W=$l[1];if(!$W)$b["DROP"][]=" COLUMN $d";else{$W[1]=preg_replace("~( COLLATE )'(\\w+)'~",'\1\2',$W[1]);$Jb[$l[0]]=$W[5];unset($W[5]);if(preg_match('~ AS ~',$W[3]))unset($W[1],$W[2]);if($l[0]=="")$b["ADD"][]="\n  ".implode("",$W).($Q==""?substr($he[$W[0]],16+strlen($W[0])):"");else{$j=$W[3];unset($W[3]);unset($W[6]);if($d!=$W[0])queries("EXEC sp_rename ".q(table($Q).".$d").", ".q(idf_unescape($W[0])).", 'COLUMN'");$b["ALTER COLUMN ".implode("",$W)][]="";$zi=$_i[$l[0]];if(default_value($zi)!=$j){if($zi["default"]!==null)$b["DROP"][]=" ".idf_escape($zi["default_constraint"]);if($j)$b["ADD"][]="\n $j FOR $d";}}}}if($Q==""){$ka=(array)$b["ADD"];foreach($he
as$w=>$W){if(!is_string($w))$ka[]="\n$W";}return
queries("CREATE TABLE ".table($A)." (".implode(",",$ka)."\n)");}if($Q!=$A)queries("EXEC sp_rename ".q(table($Q)).", ".q($A));if($he)$b[""]=$he;foreach($b
as$w=>$W){if(!queries("ALTER TABLE ".table($A)." $w".implode(",",$W)))return
false;}foreach($Jb
as$w=>$W){$Hb=substr($W,9);queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($A).", @level2type = N'Column', @level2name = ".q($w));queries("EXEC sp_addextendedproperty
@name = N'MS_Description',
@value = $Hb,
@level0type = N'Schema',
@level0name = ".q(get_schema()).",
@level1type = N'Table',
@level1name = ".q($A).",
@level2type = N'Column',
@level2name = ".q($w));}return
true;}function
alter_indexes($Q,$b){$u=array();$Xc=array();foreach($b
as$W){if($W[2]=="DROP"){if($W[0]=="PRIMARY")$Xc[]=idf_escape($W[1]);else$u[]=idf_escape($W[1])." ON ".table($Q);}elseif(!queries(($W[0]!="PRIMARY"?"CREATE $W[0] ".($W[0]!="INDEX"?"INDEX ":"").idf_escape($W[1]!=""?$W[1]:uniqid($Q."_"))." ON ".table($Q):"ALTER TABLE ".table($Q)." ADD PRIMARY KEY")." (".implode(", ",$W[2]).")"))return
false;}return(!$u||queries("DROP INDEX ".implode(", ",$u)))&&(!$Xc||queries("ALTER TABLE ".table($Q)." DROP ".implode(", ",$Xc)));}function
found_rows(array$R,array$Z){}function
foreign_keys($Q){$H=array();$ji=array("CASCADE","NO ACTION","SET NULL","SET DEFAULT");$K=get_schema();foreach(get_rows("EXEC sp_fkeys @fktable_name = ".q($Q).", @fktable_owner = ".q($K))as$I){$o=&$H[$I["FK_NAME"]];$o["db"]=($I["PKTABLE_QUALIFIER"]==DB?"":$I["PKTABLE_QUALIFIER"]);$o["ns"]=($I["PKTABLE_OWNER"]==$K?"":$I["PKTABLE_OWNER"]);$o["table"]=$I["PKTABLE_NAME"];$o["on_update"]=$ji[$I["UPDATE_RULE"]];$o["on_delete"]=$ji[$I["DELETE_RULE"]];$o["source"][]=$I["FKCOLUMN_NAME"];$o["target"][]=$I["PKCOLUMN_NAME"];}return$H;}function
truncate_tables(array$S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views(array$_n){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$_n)));}function
drop_tables(array$S){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$S)));}function
move_tables(array$S,array$_n,$em){return
apply_queries("ALTER SCHEMA ".idf_escape($em)." TRANSFER",array_merge($S,$_n));}function
trigger($A,$Q){if($A=="")return
array();$J=get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT'
	WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE'
	WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = ".q($A));$H=reset($J);if($H)$H["Statement"]=preg_replace('~^.+\s+AS\s+~isU','',$H["text"]);return($H?:array());}function
triggers($Q){$H=array();foreach(get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT'
	WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE'
	WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = ".q($Q))as$I)$H[$I["name"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("AS"),);}function
routine($A,$T){$Ac=get_val("SELECT m.definition
FROM sys.objects o
JOIN sys.sql_modules m ON m.object_id = o.object_id
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.name = ".q($A)." AND o.type = ".q($T=="PROCEDURE"?"P":"FN"));if(!$Ac)return
array();$H=array("definition"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',$Ac),"fields"=>array());foreach(get_rows("SELECT p.name, TYPE_NAME(p.user_type_id) [type], p.max_length, p.precision, p.scale, p.is_output
FROM sys.parameters p
JOIN sys.objects o ON p.object_id = o.object_id
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.name = ".q($A)."
ORDER BY p.parameter_id")as$I){$Rd=$I["type"];$x=type_length($Rd,$I);$l=array("field"=>preg_replace('~^@~','',$I["name"]),"type"=>$Rd,"length"=>$x,"full_type"=>$Rd.($x!=""?"($x)":""),"null"=>true,"inout"=>($I["is_output"]?"OUTPUT":""),);if($l["field"]=="")$H["returns"]=$l;else$H["fields"][]=$l;}return$H;}function
routines(){return
get_rows("SELECT o.name SPECIFIC_NAME, o.name ROUTINE_NAME,
	CASE o.type WHEN 'P' THEN 'PROCEDURE' ELSE 'FUNCTION' END ROUTINE_TYPE, TYPE_NAME(p.user_type_id) DTD_IDENTIFIER
FROM sys.objects o
LEFT JOIN sys.parameters p ON o.object_id = p.object_id AND p.parameter_id = 0
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.type IN ('P', 'FN')
ORDER BY o.name");}function
routine_languages(){return
array();}function
routine_options($mk){return
array();}function
routine_id($A,array$I){return
table($A);}function
schemas(){return
get_vals("SELECT name FROM sys.schemas");}function
get_schema(){if($_GET["ns"]!="")return$_GET["ns"];return
get_val("SELECT SCHEMA_NAME()");}function
set_schema($K,$g=null){$_GET["ns"]=$K;return!!get_val("SELECT 1 FROM sys.schemas WHERE name = ".q($K),0,$g);}function
create_sql($Q,$La,$Dl){if(is_view(table_status1($Q))){$zn=view($Q);return"CREATE VIEW ".table($Q)." AS $zn[select]";}$m=array();$_j=false;foreach(fields($Q)as$A=>$l){$W=process_field($l,$l);if($W[6])$_j=true;$m[]=implode("",$W);}foreach(indexes($Q)as$A=>$u){if(!$_j||$u["type"]!="PRIMARY"){$e=array();foreach($u["columns"]as$w=>$W)$e[]=idf_escape($W).($u["descs"][$w]?" DESC":"");$A=idf_escape($A);$m[]=($u["type"]=="INDEX"?"INDEX $A":"CONSTRAINT $A ".($u["type"]=="UNIQUE"?"UNIQUE":"PRIMARY KEY"))." (".implode(", ",$e).")";}}foreach(driver()->checkConstraints($Q)as$A=>$ob)$m[]="CONSTRAINT ".idf_escape($A)." CHECK ($ob)";return"CREATE TABLE ".table($Q)." (\n\t".implode(",\n\t",$m)."\n)";}function
foreign_keys_sql($Q){$m=array();foreach(foreign_keys($Q)as$he)$m[]=ltrim(format_foreign_key($he));return($m?"ALTER TABLE ".table($Q)." ADD\n\t".implode(",\n\t",$m).";\n\n":"");}function
truncate_sql($Q){return"TRUNCATE TABLE ".table($Q);}function
use_sql($rc,$Dl=""){return"USE ".idf_escape($rc);}function
use_schema_sql($K,$Dl){$A=idf_escape($K);return($Dl=="DROP+CREATE"?"DROP SCHEMA IF EXISTS $A;\n":"")."IF SCHEMA_ID(".q($K).") IS NULL EXEC(".q("CREATE SCHEMA $A").")";}function
trigger_sql($Q){$H="";foreach(triggers($Q)as$A=>$Fm)$H
.=create_trigger(" ON ".table($Q),trigger($A,$Q)).";";return$H;}function
convert_field(array$l){}function
unconvert_field(array$l,$H){return$H;}function
support($Nd){return
preg_match('~^(check|comment|columns|database|drop_col|dump|fast_status|indexes|descidx|procedure|routine|scheme|sql|table|transaction_ddl|trigger|view|view_trigger)$~',$Nd);}}add_driver("oracle","Oracle");if(isset($_GET["oracle"])){define('Adminer\DRIVER',"oracle");function
easy_connect(array$M){return
url_host($M["host"]).($M["port"]!=""?":$M[port]":"").$M["path"];}if(extension_loaded("oci8")&&$_GET["ext"]!="pdo"){class
Db
extends
SqlDb{var$extension="oci8";private$link,$transaction=false;function
_error($sd,$k){if(ini_bool("html_errors"))$k=html_entity_decode(strip_tags($k));$k=preg_replace('~^[^:]*: ~','',$k);$this->error=$k;}function
attach(array$M,$U,$D){$this->link=@oci_new_connect($U,$D,easy_connect($M),"AL32UTF8");if($this->link){$this->server_info=oci_server_version($this->link);return'';}$k=oci_error();return($k?$k["message"]:'Unknown error.');}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($rc){return$this->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($rc));}function
query($F,$Rm=false){$G=oci_parse($this->link,$F);$this->error="";if(!$G){$k=oci_error($this->link);$this->errno=$k["code"];$this->error=$k["message"];return
false;}set_error_handler(array($this,'_error'));$H=@oci_execute($G,($this->transaction?OCI_NO_AUTO_COMMIT:OCI_COMMIT_ON_SUCCESS));restore_error_handler();if($H){if(oci_num_fields($G))return
new
Result($G);$this->affected_rows=oci_num_rows($G);oci_free_statement($G);}return$H;}function
timeout($ph){return
function_exists('oci_set_call_timeout')&&oci_set_call_timeout($this->link,$ph);}function
inTransaction(){return$this->transaction;}function
begin(){$this->transaction=true;return
true;}function
commit(){return$this->end_transaction(@oci_commit($this->link));}function
rollback(){return$this->end_transaction(@oci_rollback($this->link));}private
function
end_transaction($H){$this->transaction=false;if(!$H){$k=oci_error($this->link);$this->errno=$k["code"];$this->error=$k["message"];}return$H;}}class
Result{var$num_rows;private$result,$offset=1;function
__construct($G){$this->result=$G;}private
function
convert($I){foreach((array)$I
as$w=>$W){if(is_a($W,'OCILob')||is_a($W,'OCI-Lob'))$I[$w]=$W->load();}return$I;}function
fetch_assoc(){return$this->convert(oci_fetch_assoc($this->result));}function
fetch_row(){return$this->convert(oci_fetch_row($this->result));}function
fetch_field(){$d=$this->offset++;$H=new
\stdClass;$H->name=oci_field_name($this->result,$d);$T=oci_field_type($this->result,$d);$H->native_type=idx(array(100=>"binary_float","binary_double"),$T,$T);return$H;}}}elseif(extension_loaded("pdo_oci")){class
Db
extends
PdoDb{var$extension="PDO_OCI";function
attach(array$M,$U,$D){return$this->dsn("oci:dbname=//".easy_connect($M).";charset=AL32UTF8",$U,$D);}function
select_db($rc){return$this->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($rc));}}}class
Driver
extends
SqlDriver{static$extensions=array("OCI8","PDO_OCI");static$jush="oracle";static$serverPath=true;var$insertFunctions=array("date"=>"current_date","timestamp"=>"current_timestamp",);var$editFunctions=array("number|float|double"=>"+/-","date|timestamp"=>"+ interval/- interval","char|clob"=>"||",);var$functions=array("length","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");function
operators($Ml){return
array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");}static
function
connect($M,$U,$D){$f=parent::connect($M,$U,$D);if(is_object($f))$f->query("ALTER SESSION SET CURSOR_SHARING = FORCE"." NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'"." NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS.FF'"." NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS.FF TZH:TZM'");return$f;}function
__construct(Db$f){parent::__construct($f);$this->types=array('Numbers'=>array("number"=>38,"binary_float"=>12,"binary_double"=>21),'Date and time'=>array("date"=>19,"timestamp"=>29,"interval year"=>12,"interval day"=>28),'Strings'=>array("char"=>2000,"varchar2"=>4000,"nchar"=>2000,"nvarchar2"=>4000,"clob"=>4294967295,"nclob"=>4294967295),'Binary'=>array("raw"=>2000,"long raw"=>2147483648,"blob"=>4294967295,"bfile"=>4294967296),'Geometry'=>array("sdo_geometry"=>0),);}function
begin(){return$this->conn->begin();}function
convertSearch($t,array$W,array$l){$T=$l["type"];$ej=strpos($W["op"],"LIKE")!==false;if($T=="xmltype")return"XMLSERIALIZE(CONTENT $t AS VARCHAR2(4000))";if($T=="json")return"JSON_SERIALIZE($t)";if(preg_match('~^(date|timestamp)~',$T))return"TO_CHAR($t, 'YYYY-MM-DD HH24:MI:SS')";if(preg_match('~char~',$T)||(preg_match('~clob~',$T)&&$ej))return$t;return(!$ej&&preg_match(number_type(),$T)?$t:"TO_CHAR($t)");}function
quoteBinary($vk){return"HEXTORAW(".q(bin2hex($vk)).")";}function
typeName(\stdClass$l){return
strtolower(parent::typeName($l));}function
hasCStyleEscapes(){return
true;}function
select($Q,array$L,array$Z,array$q,array$ti=array(),$y=1,$C=0,$Bj=false){if(in_array("*",$L)){$Zb=array();$le=false;foreach(fields($Q)as$A=>$l){$Fa=convert_field($l);$le=($le||$Fa);$Zb[]=($Fa?"$Fa AS ":"").idf_escape($A);}if($le)$L=$Zb;}return
parent::select($Q,$L,$Z,$q,$ti,$y,$C,$Bj);}function
allFields(){$H=array();$J=get_rows('SELECT c.table_name "tab", c.column_name "field", c.data_type "type", c.nullable "nullable",
	c.data_precision "precision", c.data_scale "scale", c.char_col_decl_length "char_length"
FROM all_tab_columns c
WHERE '.where_owner("c.owner").'
ORDER BY c.table_name, c.column_id',$this->conn);foreach($J
as$I){$x="$I[precision],$I[scale]";$I["length"]=(strpos($I["type"],"(")?"":($x==","?$I["char_length"]:$x));$I["type"]=strtolower($I["type"]);$I["null"]=($I["nullable"]=="Y");$H[$I["tab"]][]=$I;}return$H;}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return
idf_escape($t);}function
get_databases($fe){$H=get_vals("SELECT username FROM all_users WHERE oracle_maintained = 'N' ORDER BY 1");return($H?:get_vals("SELECT username FROM all_users ORDER BY 1"));}function
limit($F,$Z,$y,$Yh=0,$Lk=" "){return($Yh?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $F$Z) t WHERE rownum <= ".($y+$Yh).") WHERE rnum > $Yh":($y?" * FROM (SELECT $F$Z) WHERE rownum <= ".($y+$Yh):" $F$Z"));}function
limit1($Q,$F,$Z,$Lk="\n"){return" $F$Z";}function
db_collation($i,array$Cb){return
get_val("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");}function
logged_user(){return
get_val("SELECT USER FROM DUAL");}function
where_owner($Hi="owner"){return"$Hi = ".q(DB);}function
views_table($e){return"(SELECT $e FROM all_views WHERE ".where_owner().")";}function
objects_table(){return"(SELECT object_name, DECODE(object_type, 'VIEW', 'view', 'table') object_type FROM all_objects WHERE ".where_owner()." AND object_type IN ('TABLE', 'VIEW'))";}function
tables_list(){return
get_key_vals("SELECT * FROM ".objects_table()." ORDER BY 1");}function
count_tables(array$h){$H=array();foreach($h
as$i)$H[$i]=get_val("SELECT COUNT(*) FROM all_objects WHERE object_type IN ('TABLE', 'VIEW') AND owner = ".q($i));return$H;}function
table_status($A="",$Md=false){$H=array();$Ak=q($A);if($Md||$A!=""){foreach(get_rows('SELECT object_name "Name", object_type "Engine" FROM '.objects_table().($A!=""?" WHERE object_name = $Ak":"").' ORDER BY 1')as$I)$H[$I["Name"]]=$I;return$H;}foreach(get_rows('SELECT t.table_name "Name", \'table\' "Engine", s.bytes "Data_length", i.bytes "Index_length", t.num_rows "Rows"
FROM all_tables t
LEFT JOIN (SELECT segment_name, SUM(bytes) bytes FROM user_segments WHERE segment_type LIKE \'TABLE%\' GROUP BY segment_name) s ON s.segment_name = t.table_name
LEFT JOIN (SELECT i.table_name, SUM(s.bytes) bytes FROM user_indexes i
	JOIN user_segments s ON s.segment_name = i.index_name AND s.segment_type LIKE \'INDEX%\' GROUP BY i.table_name) i ON i.table_name = t.table_name
WHERE '.where_owner("t.owner")."
UNION SELECT view_name, 'view', 0, 0, 0 FROM ".views_table("view_name")."
ORDER BY 1")as$I)$H[$I["Name"]]=$I;return$H;}function
is_view(array$R){return$R["Engine"]=="view";}function
fk_support(array$R){return
true;}function
fields($Q){$H=array();$hf=null;foreach(get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($Q)." AND ".where_owner()." ORDER BY column_id")as$I){$T=$I["DATA_TYPE"];$x="$I[DATA_PRECISION],$I[DATA_SCALE]";if($x==",")$x=$I["CHAR_COL_DECL_LENGTH"];elseif(strpos($T,"("))$x="";$j=$I["DATA_DEFAULT"];if($j!==null){$j=rtrim($j);if(preg_match("~^'(.*)'\$~s",$j,$_))$j=str_replace("''","'",$_[1]);}if($I["IDENTITY_COLUMN"]=="YES"){if($hf===null)$hf=get_key_vals("SELECT column_name, generation_type FROM all_tab_identity_cols WHERE table_name = ".q($Q)." AND ".where_owner());$j="GENERATED ".$hf[$I["COLUMN_NAME"]].($I["DEFAULT_ON_NULL"]=="YES"?" ON NULL":"")." AS IDENTITY";}$Ej=array("insert"=>1,"select"=>1,"update"=>1,"order"=>1);if($I["DATA_TYPE_OWNER"]==""||$T=="XMLTYPE")$Ej["where"]=1;$H[$I["COLUMN_NAME"]]=array("field"=>$I["COLUMN_NAME"],"full_type"=>$T.($x?"($x)":""),"type"=>strtolower($T),"length"=>$x,"default"=>$j,"null"=>($I["NULLABLE"]=="Y"),"auto_increment"=>($I["IDENTITY_COLUMN"]=="YES"),"privileges"=>$Ej,);}return$H;}function
table_constraints($Q,$g=null){$H=array();foreach(get_rows('SELECT c.constraint_name "name", c.constraint_type "type", c.r_owner "r_owner", c.r_constraint_name "r_constraint", c.delete_rule "delete_rule", cc.column_name "column"
FROM all_constraints c
JOIN all_cons_columns cc ON cc.owner = c.owner AND cc.constraint_name = c.constraint_name
WHERE c.constraint_type IN (\'P\', \'U\', \'R\') AND '.where_owner("c.owner")." AND c.table_name = ".q($Q).'
ORDER BY cc.position',$g)as$I){$A=$I["name"];$H[$A]["type"]=$I["type"];$H[$A]["r_owner"]=$I["r_owner"];$H[$A]["r_constraint"]=$I["r_constraint"];$H[$A]["delete_rule"]=$I["delete_rule"];$H[$A]["columns"][]=$I["column"];}return$H;}function
indexes($Q,$g=null){$H=array();$Tb=array();foreach(table_constraints($Q,$g)as$A=>$Sb)$Tb[$A]=$Sb["type"];foreach(get_rows("SELECT aic.*, atc.data_default
FROM all_ind_columns aic
LEFT JOIN all_tab_cols atc ON aic.column_name = atc.column_name AND aic.table_name = atc.table_name AND aic.index_owner = atc.owner
WHERE aic.table_name = ".q($Q)." AND ".where_owner("aic.table_owner")."
ORDER BY aic.column_position",$g)as$I){$uf=$I["INDEX_NAME"];$Fb=$I["DATA_DEFAULT"];$Fb=($Fb?trim($Fb,'"'):$I["COLUMN_NAME"]);$T=idx($Tb,$uf);$H[$uf]["type"]=($T=="P"?"PRIMARY":($T=="U"?"UNIQUE":"INDEX"));$H[$uf]["columns"][]=$Fb;$H[$uf]["lengths"][]=($I["CHAR_LENGTH"]&&$I["CHAR_LENGTH"]!=$I["COLUMN_LENGTH"]?$I["CHAR_LENGTH"]:null);$H[$uf]["descs"][]=($I["DESCEND"]&&$I["DESCEND"]=="DESC"?'1':null);}uasort($H,function($ha,$Pa){$ti=array("PRIMARY"=>0,"UNIQUE"=>1,"INDEX"=>2);return$ti[$ha["type"]]-$ti[$Pa["type"]];});return$H;}function
view($A){$J=get_rows('SELECT text "select" FROM '.views_table("view_name, text").' WHERE view_name = '.q($A));return($J?$J[0]:array());}function
collations(){return
array();}function
information_schema($i,$K=""){return
in_array($K!=""?$K:$i,array("INFORMATION_SCHEMA","SYS","SYSTEM"));}function
error(){return
h(connection()->error);}function
explain(Db$f,$F){$f->query("EXPLAIN PLAN FOR $F");return$f->query("SELECT * FROM plan_table");}function
found_rows(array$R,array$Z){}function
auto_increment(){return"";}function
alter_table($Q,$A,array$m,array$he,$Hb,$nd,$Bb,$La,$Vi){$b=$Xc=array();$_i=($Q?fields($Q):array());foreach($m
as$l){$W=$l[1];if($W&&$l[0]!=""&&idf_escape($l[0])!=$W[0])queries("ALTER TABLE ".table($Q)." RENAME COLUMN ".idf_escape($l[0])." TO $W[0]");$zi=$_i[$l[0]];if($W&&$zi){$ai=process_field($zi,$zi);if($W[2]==$ai[2])$W[2]="";}if($W){list($W[2],$W[3])=array($W[3],$W[2]);$b[]=($Q!=""?($l[0]!=""?"MODIFY (":"ADD ("):"  ").implode($W).($Q!=""?")":"");}else$Xc[]=idf_escape($l[0]);}if($Q=="")return
queries("CREATE TABLE ".table($A)." (\n".implode(",\n",array_merge($b,$he))."\n)");return(!$b||queries("ALTER TABLE ".table($Q)."\n".implode("\n",$b)))&&(!$Xc||queries("ALTER TABLE ".table($Q)." DROP (".implode(", ",$Xc).")"))&&($Q==$A||queries("ALTER TABLE ".table($Q)." RENAME TO ".table($A)));}function
alter_indexes($Q,$b){$Xc=array();$Jj=array();foreach($b
as$W){if($W[0]!="INDEX"){$W[2]=preg_replace('~ DESC$~','',$W[2]);$ec=($W[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($W[1]):"\nADD".($W[1]!=""?" CONSTRAINT ".idf_escape($W[1]):"")." $W[0] ".($W[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$W[2]).")");array_unshift($Jj,"ALTER TABLE ".table($Q).$ec);}elseif($W[2]=="DROP")$Xc[]=idf_escape($W[1]);else$Jj[]="CREATE INDEX ".idf_escape($W[1]!=""?$W[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ",$W[2]).")";}if($Xc)array_unshift($Jj,"DROP INDEX ".implode(", ",$Xc));foreach($Jj
as$F){if(!queries($F))return
false;}return
true;}function
foreign_keys($Q){$H=array();$hm=array();foreach(table_constraints($Q)as$A=>$Sb){if($Sb["type"]=="R"){$H[$A]=array("source"=>$Sb["columns"],"target"=>array(),"on_delete"=>$Sb["delete_rule"],"on_update"=>null,);$hm[$A]=array($Sb["r_owner"],$Sb["r_constraint"]);}}if($hm){$Z=array();foreach($hm
as$em)$Z[]="(owner = ".q($em[0])." AND constraint_name = ".q($em[1]).")";foreach(get_rows("SELECT owner, constraint_name, table_name, column_name FROM all_cons_columns WHERE ".implode(" OR ",array_unique($Z))." ORDER BY position")as$I){foreach($hm
as$A=>$em){if($em==array($I["OWNER"],$I["CONSTRAINT_NAME"])){$H[$A]["db"]=$I["OWNER"];$H[$A]["table"]=$I["TABLE_NAME"];$H[$A]["target"][]=$I["COLUMN_NAME"];}}}}return$H;}function
trigger($A,$Q){if($A=="")return
array();$J=get_rows('SELECT trigger_name "Trigger", trigger_type "Type", triggering_event "Event", trigger_body "Statement"
FROM all_triggers
WHERE trigger_name = '.q($A)." AND ".where_owner());$H=reset($J);if($H){$T=$H["Type"];$H["Timing"]=(preg_match('~^(BEFORE|AFTER|INSTEAD OF)~',$T,$_)?$_[1]:$T);$H["Type"]=(preg_match('~EACH ROW~',$T)||$T=="INSTEAD OF"?"FOR EACH ROW":"");}return($H?:array());}function
triggers($Q){$H=array();foreach(get_rows("SELECT trigger_name, trigger_type, triggering_event FROM all_triggers WHERE table_name = ".q($Q)." AND ".where_owner())as$I)$H[$I["TRIGGER_NAME"]]=array(preg_replace('~ (STATEMENT|EACH ROW)$~','',$I["TRIGGER_TYPE"]),$I["TRIGGERING_EVENT"]);return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE","INSERT OR UPDATE","INSERT OR DELETE","UPDATE OR DELETE","INSERT OR UPDATE OR DELETE"),"Type"=>array("FOR EACH ROW",""),);}function
truncate_tables(array$S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views(array$_n){return
apply_queries("DROP VIEW",$_n);}function
drop_tables(array$S){return
apply_queries("DROP TABLE",$S);}function
last_id($G){return"0";}function
create_database($i,$Bb){$H=queries("CREATE USER ".idf_escape($i)." NO AUTHENTICATION");return($H?queries("GRANT UNLIMITED TABLESPACE TO ".idf_escape($i)):$H);}function
drop_databases(array$h){$H=true;foreach($h
as$i)$H=!!queries("DROP USER ".idf_escape($i)." CASCADE")&&$H;return$H;}function
rename_database($A,$Bb){return!!queries("ALTER USER ".idf_escape(DB)." RENAME TO ".idf_escape($A));}function
show_variables(){return
get_rows('SELECT name, display_value FROM v$parameter');}function
show_status(){$H=array();$J=get_rows('SELECT * FROM v$instance');foreach(reset($J)as$w=>$W)$H[]=array($w,$W);return$H;}function
process_list(){return
get_rows('SELECT
	sess.process AS "process",
	sess.username AS "user",
	sess.schemaname AS "schema",
	sess.status AS "status",
	sess.wait_class AS "wait_class",
	sess.seconds_in_wait AS "seconds_in_wait",
	sql.sql_text AS "sql_text",
	sess.machine AS "machine",
	sess.port AS "port"
FROM v$session sess
LEFT JOIN v$sql sql ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');}function
convert_field(array$l){if($l["type"]=="sdo_geometry")return"SDO_UTIL.TO_WKTGEOMETRY(".idf_escape($l["field"]).")";}function
unconvert_field(array$l,$H){return($l["type"]=="sdo_geometry"?"SDO_UTIL.FROM_WKTGEOMETRY($H)":$H);}function
support($Nd){return
preg_match('~^(columns|database|drop_col|fast_status|indexes|descidx|processlist|sql|status|table|trigger|variables|view|view_trigger)$~',$Nd);}}class
Adminer{static$instance;var$error='';function
name(){return"<a href='https://www.adminer.org/'".target_blank()." id='h1'><img src='".h(preg_replace("~\\?.*~","",ME)."?file=logo.svg&version=6.1.1+43f4678f")."' width='24' height='24' alt='' id='logo'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($ec=false){return
password_file($ec);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
verifyLoginToken(){return
true;}function
serverName($M){return
h($M);}function
database(){return
DB;}function
databases($fe=true){return
get_databases($fe);}function
pluginsLinks(){}function
operators($Ml=null){return
driver()->operators($Ml);}function
schemas(){$H=schemas();if($_GET["ns"]!=""&&!in_array($_GET["ns"],$H))array_unshift($H,$_GET["ns"]);return$H;}function
queryTimeout(){return
2;}function
afterConnect(){}function
headers(){}function
csp(array$ic){return$ic;}function
verifyVersion(){return
true;}function
serviceWorker(){service_worker();}function
manifest(){$cf=$_SERVER["HTTP_HOST"]?:$_SERVER["SERVER_NAME"];$Jk=preg_replace('~\?.*~','',ME)?:'.';return
array('name'=>"Adminer".($cf!=""?" - $cf":""),'short_name'=>'Adminer','description'=>'Database management in a single PHP file','start_url'=>$Jk,'scope'=>$Jk,'display'=>'minimal-ui','icons'=>array(array('src'=>preg_replace("~\\?.*~","",ME)."?file=logo.svg&version=6.1.1+43f4678f",'sizes'=>'any','type'=>'image/svg+xml')),);}function
head($nc=null){return
true;}function
bodyClass(){echo" adminer";}function
css(){$H=array();foreach(array("","-dark")as$lh){$n="adminer$lh.css";if(file_exists($n)){$Td=file_get_contents($n);$H["$n?v=".crc32($Td)]=($lh?"dark":(preg_match('~prefers-color-scheme:\s*dark~',$Td)?'':'light'));}}return$H;}function
loginForm(){echo"<table class='layout'>\n",adminer()->loginFormField('driver','<tr><th>'.'System'.'<td>',html_select("auth[driver]",SqlDriver::$drivers,DRIVER,on('change','loginDriver'))),adminer()->loginFormField('server','<tr><th>'.'Server'.'<td>',"<input name='auth[server]' value='".h(SERVER)."' title='".'hostname[:port] or :socket'."' placeholder='localhost' autocapitalize='off'>"),adminer()->loginFormField('username','<tr><th>'.'Username'.'<td>','<input name="auth[username]" id="username" autofocus value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("fire(qs('#username').form['auth[driver]'], 'change');")),adminer()->loginFormField('password','<tr><th>'.'Password'.'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'),adminer()->loginFormField('db','<tr><th>'.'Database'.'<td>','<input name="auth[db]" value="'.h($_GET["db"]).'" autocapitalize="off">'),"</table>\n","<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
loginFormField($A,$Te,$X){return$Te.$X."\n";}function
login($Cg,$D){if($D=="")return'Adminer does not support accessing a database without a password.'.require_password_link(null);if(!Driver::$passwords)return'The database does not support passwords.'.require_password_link($D);if(!password_required())return'The server accepts any password, so filling it in protects nothing.'.require_password_link($D);return
true;}function
tableName(array$Ml){return
h($Ml["Name"]);}function
fieldName(array$l,$ti=0){$T=$l["full_type"].($l["null"]?" NULL":"");$Hb=$l["comment"];return'<span title="'.h($T.($Hb!=""?($T?": ":"").$Hb:'')).'">'.h($l["field"]).'</span>';}function
commentValue($T,$Hb){if($Hb==""||$T=='TABLE'||$T=='COLUMN')return
h($Hb);$tj=function($vk,$ib='td'){return
preg_replace('~^~m','<tr>',preg_replace('~\|~',"<$ib>",preg_replace('~\|$~m',"",rtrim($vk))));};$Q='(\+--[-+]+\+\n)';$I='(\| .* \|\n)';return"<pre>\n".preg_replace_callback("~^$Q?$I$Q?($I*)$Q?~m",function($_)use($tj){return"<table>\n".($_[1]?"<thead>".$tj($_[2],'th')."<tbody>\n":$tj($_[2])).$tj($_[4])."\n</table>";},preg_replace('~(\n(    -|mysql)&gt; )(.+)~',"\\1<code class='jush-sql'>\\3</code>",preg_replace('~(.+)\n---+\n~',"<b>\\1</b>\n",h($Hb))))."</pre>\n";}function
commentInput($T,$c,$Hb){$X=h($Hb);return(preg_match('~\n~',$X)?"<textarea$c rows='2' cols='".($T=='TABLE'?20:30)."' style='vertical-align: bottom;'>\n$X</textarea>":"<input$c value='$X'>");}function
selectLinks(array$Ml,$N=""){$A=$Ml["Name"];echo'<p class="links">';$yg=array();if($A!="")$yg["select"]='Select data';if(support("table")||support("indexes"))$yg["table"]='Show structure';if(support("table")){if(is_view($Ml)){if(support("view"))$yg["view"]='Alter view';}elseif(function_exists('Adminer\alter_table')&&$A!="")$yg["create"]='Alter table';}if($N!==null)$yg["edit"]='New item';foreach($yg
as$w=>$W)echo" <a href='".h(ME)."$w=".url_escape($A).($w=="edit"?$N:"")."'".bold(isset($_GET[$w])).">$W</a>";echo"\n";}function
foreignKeys($Q){return
foreign_keys($Q);}function
backwardKeys($Q,$Ll){return
array();}function
backwardKeysPrint(array$Ra,array$I){}function
selectQuery($F,$xl,$Ld=false){$H="\n";if(!$Ld&&($Dn=driver()->warnings())){$s="warnings";$H=", <a href='#$s' class='toggle'>".'Warnings'."</a>"."$H<div id='$s' class='hidden'>\n$Dn</div>\n";}return"<p><code class='jush-".JUSH."'>".h(str_replace("\n"," ",$F))."</code> <span class='time'>(".format_time($xl).")</span>".(support("sql")?" <a href='".h(ME)."sql=".url_escape($F)."' class='hover'>".'Edit'."</a>":"").$H;}function
sqlCommandQuery($F){return
shorten_utf8(trim($F),1000);}function
sqlPrintAfter(){}function
explain(Db$f,$F,array$xi){$G=explain($f,$F);if(!$G)return"";ob_start();print_select_result($G,$f,$xi);return
ob_get_clean();}function
rowDescription($Q){return"";}function
rowDescriptions(array$J,array$ie){return$J;}function
selectLink($W,array$l){}function
selectVal($W,$z,array$l,$Ci){$H=($W===null?"<i>NULL</i>":(preg_match("~char|binary|boolean~",$l["type"])&&!preg_match("~var~",$l["type"])?"<code>$W</code>":(preg_match('~^jsonb?$~',$l["full_type"])?"<code class='jush-json'>$W</code>":$W)));if(is_blob($l)&&!is_utf8($W))$H="<i>".lang_format(array('%d byte','%d bytes'),strlen($Ci))."</i>";return($z?"<a href='".h($z)."'".(is_url($z)?target_blank():"").">$H</a>":$H);}function
editVal($W,array$l){return$W;}function
config(){return
array();}function
tableStructurePrint(array$m,$Ml=null){echo"<div class='scrollable'>\n","<table class='nowrap odds'>\n","<thead><tr><th>".'Column'."<th>".'Type'.(support("comment")?"<th>".'Comment':"")."<tbody>\n";$pn=(support("type")?types():array());foreach($m
as$l){echo"<tr><th>".h($l["field"]);$T=h($l["full_type"]);$Bb=h($l["collation"]);echo"<td><span title='$Bb'>".(in_array($T,$pn)?"<a href='".h(ME.'type='.url_escape($T))."'>$T</a>":$T.($Bb&&isset($Ml["Collation"])&&$Bb!=$Ml["Collation"]?" $Bb":""))."</span>",($l["null"]?" <i>NULL</i>":""),($l["auto_increment"]?" <i>".'Auto Increment'."</i>":""),(isset($l["default"])?" <span title='".'Default value'."'>[<b>".($l["generated"]?"<code class='jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim($l["default"])),80,"</code>"):h($l["default"]))."</b>]</span>":""),(support("comment")?"<td>".adminer()->commentValue('COLUMN',$l["comment"]):""),"\n";}echo"</table>\n","</div>\n";}function
tableIndexesPrint(array$v,array$Ml){$Qi=false;foreach($v
as$A=>$u)$Qi|=!!$u["partial"];echo"<table>\n";$xc=first(driver()->indexAlgorithms($Ml));foreach($v
as$A=>$u){ksort($u["columns"]);$Bj=array();foreach($u["columns"]as$w=>$W)$Bj[]="<i>".h($W)."</i>".($u["lengths"][$w]?"(".h($u["lengths"][$w]).")":"").($u["descs"][$w]?" DESC":"");echo"<tr title='".h($A)."'>","<th>".h($u["type"]).($xc&&$u['algorithm']!=$xc?" (".h($u['algorithm']).")":""),"<td>".implode(", ",$Bj);if($Qi)echo"<td>".($u['partial']?"<code class='jush-".JUSH."'>WHERE ".h($u['partial']):"");echo"\n";}echo"</table>\n";}function
namePattern($T){if($T=="FOREIGN"||$T=="CHECK")return"";if($T=="TRIGGER")return"{table}_{timing}{event}";return(JUSH=="sql"?"":"{table}_")."{columns}";}function
selectColumnsPrint(array$L,array$e){print_fieldset("select",'Select',$L);$r=0;$L[""]=array();foreach($L
as$w=>$W){$W=idx($_GET["columns"],$w,array());$d=select_input(" name='columns[$r][col]' data-default=''".on('change',($w!==""?'selectFieldChange':'selectAddRow')),$e,$W["col"]);echo"<div>".(driver()->functions||driver()->grouping?html_select("columns[$r][fun]",array(-1=>"")+array_filter(array('Functions'=>driver()->functions,'Aggregation'=>driver()->grouping)),$W["fun"]," data-default=''".on('change',($w!==""?'helpClose':'selectFunAddRow')).on_help_value(' (.*)|$','($1)'))."($d)":$d)."</div>\n";$r++;}echo"</div></fieldset>\n";}function
selectSearchPrint(array$Z,array$e,array$v,$Ml=null){print_fieldset("search",'Search',$Z);foreach($v
as$r=>$u){if($u["type"]=="FULLTEXT")echo"<div>(<i>".implode("</i>, <i>",array_map('Adminer\h',$u["columns"]))."</i>) ".h(driver()->fulltextOperator)," <input type='search' name='fulltext[$r]' value='".h(idx($_GET["fulltext"],$r))."' data-default=''".on('input','selectFieldChange').">",(JUSH=='sql'?checkbox("boolean[$r]",1,isset($_GET["boolean"][$r]),"BOOL"):''),"</div>\n";}$oi=adminer()->operators($Ml);foreach(array_merge((array)$_GET["where"],array(array()))as$r=>$W){if(!$W||(("$W[col]$W[val]"!=""||preg_match('~NULL$~',$W["op"]))&&in_array($W["op"],$oi)))echo"<div>".select_input(" name='where[$r][col]' data-default=''".on('change',($W?'selectFieldChange':'selectAddRow')),$e,$W["col"],"(".'anywhere'.")"),html_select("where[$r][op]",$oi,$W["op"]," data-default='".h(first($oi))."'".on('change','selectFirstChange')),"<input type='search' name='where[$r][val]' value='".h($W["val"])."' data-default=''".on('input','selectFirstChange').on('keydown','selectSearchKeydown').on('search','selectSearchSearch').">","</div>\n";}echo"</div></fieldset>\n";}function
selectOrderPrint(array$ti,array$e,array$v){print_fieldset("sort",'Sort',$ti);$r=0;foreach((array)$_GET["order"]as$w=>$W){if($W!=""){echo"<div>".select_input(" name='order[$r]' data-default=''".on('change','selectFieldChange'),$e,$W),checkbox("desc[$r]",1,isset($_GET["desc"][$w]),'descending')."</div>\n";$r++;}}echo"<div>".select_input(" name='order[$r]' data-default=''".on('change','selectAddRow'),$e),checkbox("desc[$r]",1,false,'descending')."</div>\n","</div></fieldset>\n";}function
selectLimitPrint($y){echo"<fieldset><legend>".'Limit'."</legend><div>","<input type='number' name='limit' class='size' value='".h($y?:"")."' data-default='50'".on('input','selectFieldChange').">","</div></fieldset>\n";}function
selectLengthPrint($lm){echo"<fieldset><legend>".'Text length'."</legend><div>","<input type='number' name='text_length' class='size' value='".h($lm)."' data-default='100'>","</div></fieldset>\n";}function
selectActionPrint(array$v){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>"," <span id='noindex' title='".'Full table scan'."'></span>","<script".nonce().">\n","const indexColumns = ";$e=array();foreach($v
as$u){$mc=reset($u["columns"]);if($u["type"]!="FULLTEXT"&&$mc)$e[$mc]=1;}$e[""]=1;foreach($e
as$w=>$W)json_row($w);echo";\n","selectFieldChange.call(qs('#form')['select']);\n","</script>\n","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return!information_schema(DB);}function
selectEmailPrint(array$jd,array$e){}function
selectColumnsProcess(array$e,array$v){$L=array();$q=array();foreach((array)$_GET["columns"]as$w=>$W){if($W["fun"]=="count"||($W["col"]!=""&&(!$W["fun"]||in_array($W["fun"],driver()->functions)||in_array($W["fun"],driver()->grouping)))){$L[$w]=apply_sql_function($W["fun"],($W["col"]!=""?idf_escape($W["col"]):"*"));if(!in_array($W["fun"],driver()->grouping))$q[]=$L[$w];}}return
array($L,$q);}function
selectSearchProcess(array$m,array$v,$Ml=null){$H=array();foreach($v
as$r=>$u){if($u["type"]=="FULLTEXT"&&idx($_GET["fulltext"],$r)!="")$H[]=driver()->fulltextSql($r,$u,$_GET["fulltext"][$r],isset($_GET["boolean"][$r]));}$oi=adminer()->operators($Ml);foreach((array)$_GET["where"]as$w=>$W){$W+=array("col"=>"","op"=>first($oi),"val"=>"");$_GET["where"][$w]=$W;$_b=$W["col"];if(("$_b$W[val]"!=""||preg_match('~NULL$~',$W["op"]))&&in_array($W["op"],$oi)){if($W["op"]=="SQL"&&(!$_POST||!verify_token()))SqlDb::$untrusted=true;$Mb=array();foreach(($_b!=""?array($_b=>$m[$_b]):$m)as$A=>$l){$vj="";$Lb=" $W[op]";if(preg_match('~IN$~',$W["op"]))$Lb
.=" ".($W["val"]!=""?process_in($W["val"]):"(NULL)");elseif($W["op"]=="SQL")$Lb=" $W[val]";elseif(preg_match('~^(I?LIKE) %%$~',$W["op"],$_))$Lb=" $_[1] ".q("%$W[val]%");elseif($W["op"]=="FIND_IN_SET"){$vj="$W[op](".q($W["val"]).", ";$Lb=")";}elseif(!preg_match('~NULL$~',$W["op"]))$Lb
.=" ".q($W["val"]);if($_b!=""||is_searchable($l,$W))$Mb[]=$vj.driver()->convertSearch(idf_escape($A),$W,$l).$Lb;}$H[]=(count($Mb)==1?$Mb[0]:($Mb?"(".implode(" OR ",$Mb).")":"1 = 0"));}}return$H;}function
selectOrderProcess(array$m,array$v){$H=array();foreach((array)$_GET["order"]as$w=>$W){if($W!="")$H[]=(preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~',$W)?$W:idf_escape($W)).(isset($_GET["desc"][$w])?" DESC".(JUSH=='pgsql'&&idx($m[$W],"null")?" NULLS LAST":""):"");}return$H;}function
selectLimitProcess(){return(isset($_GET["limit"])?intval($_GET["limit"]):50);}function
selectLengthProcess(){return(isset($_GET["text_length"])?"$_GET[text_length]":"100");}function
selectEmailProcess(array$Z,array$ie){return
false;}function
selectQueryBuild(array$L,array$Z,array$q,array$ti,$y,$C){return"";}function
messageQuery($F,$nm,$Ld=false){restart_session();$Ze=&get_session("queries");if(!idx($Ze,$_GET["db"]))$Ze[$_GET["db"]]=array();if(strlen($F)>1e6)$F=preg_replace('~[\x80-\xFF]+$~','',substr($F,0,1e6))."\n…";$Ze[$_GET["db"]][]=array($F,time(),$nm);$sl="sql-".count($Ze[$_GET["db"]]);$H="<a href='#$sl' class='toggle'>".'SQL command'."</a> ".copy_icon()."\n";if(!$Ld&&($Dn=driver()->warnings())){$s="warnings-".count($Ze[$_GET["db"]]);$H="<a href='#$s' class='toggle'>".'Warnings'."</a>, $H<div id='$s' class='hidden'>\n$Dn</div>\n";}return" <span class='time'>".@date("H:i:s")."</span>"." $H<div id='$sl' class='hidden'><pre><code class='jush-".JUSH."'>".shorten_utf8($F,1e4)."</code></pre>".($nm?" <span class='time'>($nm)</span>":'').(support("sql")?'<p><a href="'.h(str_replace("db=".url_escape(DB),"db=".url_escape($_GET["db"]),ME).'sql=&history='.(count($Ze[$_GET["db"]])-1)).'">'.'Edit'.'</a>':'').'</div>';}function
error(){return
error();}function
editRowPrint($Q,array$m,$I,$cn,$F='',$nm=''){echo($F!=""?"<p><code class='jush-".JUSH."'>".h(str_replace("\n"," ",$F))."</code> <span class='time'>($nm)</span>\n":"");}function
editFunctions(array$l){$H=($l["null"]?"NULL/":"");$Pe=isset($_GET["select"])||where($_GET);foreach(array(driver()->insertFunctions,driver()->editFunctions)as$w=>$we){if(!$w||(!isset($_GET["call"])&&$Pe)){foreach($we
as$ej=>$W){if(!$ej||preg_match("~$ej~",$l["type"]))$H
.="/$W";}}if($w&&$we&&!preg_match('~set|bool~',$l["type"])&&!is_blob($l))$H
.="/SQL";}if($l["auto_increment"]&&!$Pe)$H='Auto Increment';return
explode("/",$H);}function
editInput($Q,array$l,$c,$X){if($l["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$c value='orig' checked><i>".'original'."</i></label> ":"").enum_input("radio",$c,$l,$X,"NULL");return"";}function
editHint($Q,array$l,$X){return"";}function
processInput(array$l,$X,$p=""){if($p=="SQL")return$X;$A=$l["field"];$H=q($X);if(preg_match('~^(now|getdate|uuid)$~',$p))$H="$p()";elseif(preg_match('~^current_(date|timestamp)$~',$p))$H=$p;elseif(preg_match('~^([+-]|\|\|)$~',$p))$H=idf_escape($A)." $p $H";elseif(preg_match('~^[+-] interval$~',$p))$H=idf_escape($A)." $p ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i",$X)&&JUSH!="pgsql"?$X:$H);elseif(preg_match('~^(addtime|subtime|concat)$~',$p))$H="$p(".idf_escape($A).", $H)";elseif(preg_match('~^(md5|sha1|password|encrypt)$~',$p))$H="$p($H)";return
unconvert_field($l,$H);}function
dumpOutput(){$H=array('text'=>'open','file'=>'save');if(function_exists('gzencode'))$H['gz']='gzip';return$H;}function
dumpFormat(){return(support("dump")?array('sql'=>'SQL'):array())+array('csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpPrint(){}function
dumpDatabase($i){}function
dumpTable($Q,$Dl,$Tf=0){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($Dl)dump_csv(array_keys(fields($Q)));}else{if($Tf==2){$m=array();foreach(fields($Q)as$A=>$l)$m[]=idf_escape($A)." ".full_type_sql($l);$ec="CREATE TABLE ".table($Q)." (".implode(", ",$m).")";}else$ec=create_sql($Q,$_POST["auto_increment"],$Dl);set_utf8mb4($ec);if($Dl&&$ec){if(($Dl=="DROP+CREATE"&&!function_exists('Adminer\drop_sql'))||$Tf==1)echo"DROP ".($Tf==2?"VIEW":"TABLE")." IF EXISTS ".table($Q).";\n";if($Tf==1)$ec=remove_definer($ec);echo"$ec;\n\n";}}}function
dumpData($Q,$Dl,$F,array$L=array(),array$Z=array(),array$q=array(),array$ti=array()){if($Dl){$Ng=(JUSH=="sqlite"?0:1048576);$m=array();$if=false;if($_POST["format"]=="sql"){if($Dl=="TRUNCATE+INSERT"&&!function_exists('Adminer\truncate_all_sql'))echo
truncate_sql($Q).";\n";$m=fields($Q);if(JUSH=="mssql"){foreach($m
as$l){if($l["auto_increment"]){echo"SET IDENTITY_INSERT ".table($Q)." ON;\n";$if=true;break;}}}}$G=($F!=""?connection()->query($F,1):driver()->select($Q,($L?:array("*")),$Z,$q,$ti,0));if($G){$Ef="";$cb="";$bg=array();$xe=array();$Fl="";$Od=($Q!=''?'fetch_assoc':'fetch_row');$dc=0;while($I=$G->$Od()){if(!$bg){$Y=array();foreach($I
as$W){$l=$G->fetch_field();if(idx($m[$l->name],'generated')){$xe[$l->name]=true;continue;}$bg[]=$l->name;$w=idf_escape($l->name);$Y[]="$w = VALUES($w)";}$Fl=($Dl=="INSERT+UPDATE"?"\nON DUPLICATE KEY UPDATE ".implode(", ",$Y):"").";\n";}if($_POST["format"]!="sql"){if($Dl=="table"){dump_csv($bg);$Dl="INSERT";}dump_csv($I);}else{if(!$Ef)$Ef="INSERT INTO ".table($Q)." (".implode(", ",array_map('Adminer\idf_escape',$bg)).") VALUES";foreach($I
as$w=>$W){if($xe[$w]){unset($I[$w]);continue;}$l=$m[$w];$I[$w]=($W===null?"NULL":($W===false?0:unconvert_field($l,preg_match(number_type(),$l["type"])&&!preg_match('~\[~',$l["full_type"])&&is_numeric($W)?$W:(!is_blob($l)||is_utf8($W)?q($W):driver()->quoteBinary($W)))));}$vk=($Ng?"\n":" ")."(".implode(",\t",$I).")";if(!$cb)$cb=$Ef.$vk;elseif(JUSH=='mssql'?$dc%1000!=0:strlen($cb)+4+strlen($vk)+strlen($Fl)<$Ng)$cb
.=",$vk";else{echo$cb.$Fl;$cb=$Ef.$vk;}}$dc++;}if($cb)echo$cb.$Fl;}elseif($_POST["format"]=="sql")echo"-- ".str_replace("\n"," ",connection()->error)."\n";if($if)echo"SET IDENTITY_INSERT ".table($Q)." OFF;\n";}}function
dumpFilename($gf){return
friendly_url($gf!=""?$gf:(SERVER?:"localhost"));}function
dumpHeaders($gf,$rh=false){$Gi=$_POST["output"];$Gd=(preg_match('~sql~',$_POST["format"])?"sql":($rh?"tar":"csv"));header("Content-Type: ".($Gi=="gz"?"application/x-gzip":($Gd=="tar"?"application/x-tar":($Gd=="sql"||$Gi!="file"?"text/plain":"text/csv")."; charset=utf-8")));if($Gi=="gz"){ob_start(function($P){return
gzencode($P);},1e6);}return$Gd;}function
dumpFooter(){if($_POST["format"]=="sql")echo"-- ".gmdate("Y-m-d H:i:s e")."\n";}function
importServerPath(){return"adminer.sql";}function
importPrint(){}function
importProcess(){return
false;}function
homepage(){echo'<p class="links">'.($_GET["ns"]==""&&support("database")?'<a href="'.h(ME).'database=">'.'Alter database'."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?'Alter schema':'Create schema')."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.'Database schema'."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".'Privileges'."</a>\n":"");if($_GET["ns"]!=="")echo(support("routine")?"<a href='#routines'>".'Routines'."</a>\n":""),(support("sequence")?"<a href='#sequences'>".'Sequences'."</a>\n":""),(support("type")?"<a href='#user-types'>".'User types'."</a>\n":""),(support("event")?"<a href='#events'>".'Events'."</a>\n":"");return
true;}function
navigation($kh){echo"<h1>".adminer()->name()." <span class='version'>".VERSION;$Jh=$_COOKIE["adminer_version"];echo" <a href='https://www.adminer.org/#download'".target_blank()." id='version'>".(version_compare(VERSION,$Jh)<0?h($Jh):"").version_iframe()."</a>","</span></h1>\n";if($kh=="auth"){$Gi="";foreach((array)$_SESSION["pwds"]as$xn=>$Yk){foreach($Yk
as$M=>$qn){$A=h(get_setting("vendor-$xn-$M")?:get_driver($xn));foreach($qn
as$U=>$D){if($A&&$D!==null){$vc=$_SESSION["db"][$xn][$M][$U];foreach(($vc?array_keys($vc):array(""))as$i)$Gi
.="<li><a href='".h(auth_url($xn,$M,$U,$i))."'>($A) ".h("$U@").($M!=""?adminer()->serverName($M):"").h($i!=""?" - $i":"")."</a>\n";}}}}if($Gi)echo"<ul id='logins'".on('mouseover','menuOver').on('mouseout','menuOut').">\n$Gi</ul>\n";}else{$S=array();if($_GET["ns"]!==""&&!$kh&&DB!=""){connection()->select_db(DB);$S=table_status('',true);}adminer()->syntaxHighlighting($S);adminer()->databasesPrint($kh);$ja=array();if(DB==""||!$kh){if(support("sql")){$ja['sql']="<a href='".h(ME)."sql='".bold(isset($_GET["sql"])&&!isset($_GET["import"])).">".'SQL command'."</a>";$ja['import']="<a href='".h(ME)."import='".bold(isset($_GET["import"])).">".'Import'."</a>";}$ja['dump']="<a href='".h(ME)."dump=".url_escape(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".'Export'."</a>";}$of=$_GET["ns"]!==""&&!$kh&&DB!="";if($of&&function_exists('Adminer\alter_table'))$ja['create']='<a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".'Create table'."</a>";$ja=adminer()->menuActions($ja,$kh);echo($ja?"<p class='links'>\n".implode("\n",$ja)."\n":"");if($of){if($S)adminer()->tablesPrint($S);else
echo"<p class='message'>".'No tables.'."</p>\n";}}}function
syntaxHighlighting(array$S){echo
script_src(preg_replace("~\\?.*~","",ME)."?file=jush.js&version=6.1.1+43f4678f",true);$nh=preg_replace('~<(?=/script)~i','<\\',Driver::jushModule());echo($nh?script("addEventListener('DOMContentLoaded', () => {\n$nh\n});"):"");if(support("sql")){echo"<script".nonce().">\n";if($S){$yg=array();foreach($S
as$Q=>$T)$yg[]=js_escape_re($Q);echo"var jushLinks = { ".JUSH.":";json_row(js_escape(ME).(support("table")?"table":"select").'=$&','/\b(?<!\$)('.implode('|',$yg).')(?!\$)\b/g',false);$ul=array("sql","check","event","procedure","trigger","view","type","table","processlist");if(support("routine")&&array_intersect_key($_GET,array_flip($ul))){foreach(routines()as$I)json_row(js_escape(ME).'function='.url_escape($I["SPECIFIC_NAME"]).'&name=$&','/\b'.js_escape_re($I["ROUTINE_NAME"]).'(?=["`\]]?\()/g',false);}json_row('');echo"};\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$W)echo"jushLinks.$W = jushLinks.".JUSH.";\n";if(array_intersect_key($_GET,array_flip(array("sql","check","event","procedure","trigger","view")))){$zl=(isset($_GET["trigger"])?array('INSERT INTO','UPDATE','DELETE FROM'):(isset($_GET["check"])?array():(isset($_GET["view"])?array('SELECT'):null)));$Na=Driver::jushAutocomplete($S,$zl);echo($Na?"addEventListener('DOMContentLoaded', () => { autocompleter = $Na; });\n":"");}}echo"</script>\n";}echo
script("syntaxHighlighting('".doc_version()."', '".connection()->flavor."');");}function
databasesPrint($kh){if(support("single_db"))return;$h=adminer()->databases();if(DB&&$h&&!in_array(DB,$h))array_unshift($h,DB);echo"<form action=''>\n<p id='dbs'>\n";hidden_fields_get();$sc=on('mousedown','dbMouseDown').on('change','dbChange');echo"<label title='".'Database'."'>".'DB'.": ".($h?html_select("db",array(""=>"")+group_system($h),DB,$sc):"<input name='db' value='".h(DB)."' autocapitalize='off' size='19'>\n")."</label>","<input type='submit' value='".'Use'."'".($h?" class='hidden'":"").">\n";if(support("scheme")){if($kh!="db"&&DB!=""&&connection()->select_db(DB)){echo"<br><label>".'Schema'.": ".html_select("ns",array(""=>"")+group_system(adminer()->schemas(),true),$_GET["ns"],$sc)."</label>";if($_GET["ns"]!="")set_schema($_GET["ns"]);}}foreach(array("import","sql","schema","dump","privileges")as$W){if(isset($_GET[$W])){echo
input_hidden($W);break;}}echo"</p></form>\n";}function
menuActions(array$ja,$kh){return$ja;}function
tablesPrint(array$S){echo"<ul id='tables'".on('mouseover','menuOver').on('mouseout','menuOut').">";foreach($S
as$Q=>$O){$Q="$Q";$A=adminer()->tableName($O);if($A!=""&&!$O["dependent"])echo'<li><a href="'.h(ME).'select='.url_escape($Q).'"'.bold($_GET["select"]==$Q||$_GET["edit"]==$Q,"select hover")." title='".'Select data'."'>".'select'."</a> ",(support("table")||support("indexes")?'<a href="'.h(ME).'table='.url_escape($Q).'"'.bold(in_array($Q,array($_GET["table"],$_GET["create"],$_GET["indexes"],$_GET["foreign"],$_GET["trigger"],$_GET["check"],$_GET["view"])),(is_view($O)?"view":"structure"))." title='".'Show structure'."'>$A</a>":"<span>$A</span>")."\n";}echo"</ul>\n";}function
showVariables(){return
show_variables();}function
showStatus(){return
show_status();}function
processList(){return
process_list();}function
killProcess($s){return
kill_process($s);}}class
Plugins{private
static$append=array('dumpFormat'=>true,'dumpOutput'=>true,'editRowPrint'=>true,'editFunctions'=>true,'config'=>true);var$plugins;var$drivers=array();var$driverFiles=array();var$error='';private$hooks=array();function
__construct($mj){$Wc=SqlDriver::$drivers;$Ve=" href='https://www.adminer.org/plugins/#use'".target_blank();if($mj===null){$mj=array();$Va="adminer-plugins";if(is_dir($Va)){foreach(glob("$Va/*.php")as$n){$Ud=SqlDriver::$drivers;$this->includeOnce($n);foreach(array_diff_key(SqlDriver::$drivers,$Ud)as$s=>$A)$this->driverFiles[$s]=$n;}}if(file_exists("$Va.php")){$qf=$this->includeOnce("$Va.php");if(is_array($qf)){foreach($qf
as$w=>$jj)$mj[is_object($jj)?get_class($jj):$w]=$jj;}else$this->error
.=sprintf('%s must <a%s>return an array</a>.',"<b>$Va.php</b>",$Ve)."<br>";}foreach(get_declared_classes()as$xb){if(!$mj[$xb]&&(preg_match('~^Adminer\w~i',$xb)||is_subclass_of($xb,'Adminer\Plugin'))){$Uj=new
\ReflectionClass($xb);$Ub=$Uj->getConstructor();if($Ub&&$Ub->getNumberOfRequiredParameters())$this->error
.=sprintf('<a%s>Configure</a> %s in %s.',$Ve,"<b>$xb</b>","<b>$Va.php</b>")."<br>";else$mj[$xb]=new$xb;}}}$Jf=array_filter($mj,function($jj){return!is_object($jj);});if($Jf){$this->error
.=sprintf('Every plugin must <a%s>be an object</a>.',$Ve)."<br>";$mj=array_diff_key($mj,$Jf);}$this->drivers=array_diff_key(SqlDriver::$drivers,$Wc);$this->plugins=$mj;$oa=new
Adminer;$mj[]=$oa;$Uj=new
\ReflectionObject($oa);foreach($Uj->getMethods()as$hh){foreach($mj
as$jj){$A=$hh->getName();if(method_exists($jj,$A))$this->hooks[$A][]=$jj;}}}function
includeOnce($n){return
include_once"./$n";}static
function
checksum($n){$Td=str_replace("\r","",file_get_contents($n));$Td=preg_replace('~\n\tprotected \$translations = array\(.*?\n\t\);~s','',$Td);return
dechex(crc32($Td));}function
checksums(){$Vd=array_values($this->driverFiles);foreach($this->plugins
as$jj){$Uj=new
\ReflectionObject($jj);$Vd[]=$Uj->getFileName();}$H=array();foreach($Vd
as$n)$H[basename($n,'.php')]=self::checksum($n);return$H;}static
function
officialChecksums(){return
array('adminer.js'=>'a0599090','backward-keys'=>'e65981f5','before-unload'=>'2a613523','config'=>'722eb4af','dark-switcher'=>'3d490dea','database-hide'=>'e304a899','designs'=>'ed7e44e3','dump-alter'=>'896b579e','dump-bz2'=>'f0d0e336','dump-date'=>'adc7f1c7','dump-json'=>'767dd321','dump-xml'=>'4fc3cd60','dump-zip'=>'93817d96','edit-foreign'=>'72ad1562','edit-textarea'=>'a24c3cc','editor-setup'=>'a7dc3a37','editor-views'=>'5c12b185','enum-option'=>'1e24970e','file-upload'=>'10add0e8','foreign-system'=>'ebb4c654','frames'=>'b0e1d11a','highlight-codemirror'=>'c5716555','highlight-monaco'=>'edd1b0af','highlight-prism'=>'267948e5','import-csv'=>'d429c77','login-ip'=>'4d174fea','login-otp'=>'5b5a68af','login-passkey'=>'f69f2f06','login-password-less'=>'e150daac','login-reverse-proxy'=>'24558ea2','login-servers'=>'19c42e45','login-ssl'=>'6ed147bc','login-table'=>'811f8cef','menu-links'=>'c78461b3','name-patterns'=>'84c10d09','remote-color'=>'ddeecc48','row-numbers'=>'eec8698c','select-email'=>'f84fbd2c','select-foreign'=>'fe3e58c8','select-image'=>'f55c0231','slugify'=>'dec64713','sql-gemini'=>'c60ab309','sql-log'=>'8e435000','table-indexes-structure'=>'a90cc0c9','table-structure'=>'a8458e02','tables-filter'=>'ec2bcd6e','timeout'=>'97321caf','version-github'=>'627cadf9','version-noverify'=>'966937e9','clickhouse'=>'92ca960d','elastic'=>'1582a04d','firebird'=>'1cccfc19','igdb'=>'4063cc0b','imap'=>'3da1022b','mongo'=>'63486492','redis'=>'79824392','simpledb'=>'b8e2cc7d',);}function
__call($A,array$Ni){$Da=array();foreach($Ni
as$w=>$W)$Da[]=&$Ni[$w];$H=null;foreach($this->hooks[$A]as$jj){$X=call_user_func_array(array($jj,$A),$Da);if($X!==null){if(!self::$append[$A])return$X;$H=$X+(array)$H;}}return$H;}}abstract
class
Plugin{protected$translations=array();function
description(){return$this->lang('');}function
screenshot(){return"";}protected
function
lang($t,$Qh=null){$Da=func_get_args();$Da[0]=idx($this->translations[LANG],$t)?:$t;return
call_user_func_array('Adminer\lang_format',$Da);}}class
Password{private$password_hash;private$password_matches=null;function
__construct($aj){$this->password_hash=$aj;}function
description(){return'Require a password verified by Adminer';}function
credentials(){$D=get_password();return
array(SERVER,$_GET["username"],($this->passwordMatches($D)&&!password_required()?"":$D));}function
login($Cg,$D){if($this->passwordMatches($D))return
true;}protected
function
passwordMatches($D){if($this->password_matches===null)$this->password_matches=(function_exists('password_verify')&&password_verify(strval($D),$this->password_hash));return$this->password_matches;}}Adminer::$instance=(function_exists('adminer_object')?adminer_object():(is_dir("adminer-plugins")||file_exists("adminer-plugins.php")?new
Plugins(null):new
Adminer));SqlDriver::$drivers=array("server"=>"MySQL / MariaDB")+SqlDriver::$drivers;if(!defined('Adminer\DRIVER')){define('Adminer\DRIVER',"server");if(extension_loaded("mysqli")&&$_GET["ext"]!="pdo"){class
Db
extends
\mysqli{static$instance;var$extension="MySQLi",$flavor='';function
__construct(){parent::init();}function
attach(array$M,$U,$D){mysqli_report(MYSQLI_REPORT_OFF);$nj=$M["port"];$ld=("$M[host]$nj$M[socket]"=="");$wl=adminer()->connectSsl();$mn=($wl&&($wl['key']||$wl['cert']||$wl['ca']||isset($wl['verify'])));if($mn)$this->ssl_set($wl['key'],$wl['cert'],$wl['ca'],'','');$H=@$this->real_connect((!$ld?$M["host"]:ini_get("mysqli.default_host")),(!$ld||$U!=""?$U:ini_get("mysqli.default_user")),(!$ld||$U.$D!=""?$D:ini_get("mysqli.default_pw")),null,($nj!=""?intval($nj):ini_get("mysqli.default_port")),($nj!=""?null:$M["socket"]),($mn?($wl['verify']!==false?MYSQLI_CLIENT_SSL:64):0));$this->options(MYSQLI_OPT_LOCAL_INFILE,0);return($H?'':$this->error);}function
set_charset($mb){if(parent::set_charset($mb))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $mb");}function
next_result(){return
self::more_results()&&parent::next_result();}function
quote($P){return"'".$this->escape_string($P)."'";}function
inTransaction(){return
false;}function
begin(){return$this->begin_transaction();}}}elseif(extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))){class
Db
extends
SqlDb{private$link;function
attach(array$M,$U,$D){if(ini_bool("mysql.allow_local_infile"))return
sprintf('Disable %s or enable the %s or %s extension.',"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");$nj="$M[port]$M[socket]";$A=$M["host"].($nj!=""?":$nj":"");$this->link=@mysql_connect(($A!=""?$A:ini_get("mysql.default_host")),($A.$U!=""?$U:ini_get("mysql.default_user")),($A.$U.$D!=""?$D:ini_get("mysql.default_password")),true,131072);if(!$this->link)return
mysql_error();$this->server_info=mysql_get_server_info($this->link);return'';}function
set_charset($mb){return
mysql_set_charset($mb,$this->link)||mysql_set_charset('utf8',$this->link);}function
quote($P){return"'".mysql_real_escape_string($P,$this->link)."'";}function
select_db($rc){return
mysql_select_db($rc,$this->link);}function
query($F,$Rm=false){$G=@($Rm?mysql_unbuffered_query($F,$this->link):mysql_query($F,$this->link));$this->error="";if(!$G){$this->errno=mysql_errno($this->link);$this->error=mysql_error($this->link);return
false;}if($G===true){$this->affected_rows=mysql_affected_rows($this->link);$this->info=mysql_info($this->link);return
true;}return
new
Result($G);}}class
Result{var$num_rows;private$result;private$offset=0;function
__construct($G){$this->result=$G;$this->num_rows=mysql_num_rows($G);}function
fetch_assoc(){return
mysql_fetch_assoc($this->result);}function
fetch_row(){return
mysql_fetch_row($this->result);}function
fetch_field(){$H=mysql_fetch_field($this->result,$this->offset++);$H->orgtable=$H->table;$H->native_type=idx(array("string"=>"varchar","real"=>"double"),$H->type,$H->type);return$H;}}}elseif(extension_loaded("pdo_mysql")){class
Db
extends
PdoDb{var$extension="PDO_MySQL";function
attach(array$M,$U,$D){$B=array(\PDO::MYSQL_ATTR_LOCAL_INFILE=>false);if(isset($_GET["select"]))$B[\PDO::MYSQL_ATTR_MULTI_STATEMENTS]=false;$wl=adminer()->connectSsl();if($wl){if($wl['key'])$B[\PDO::MYSQL_ATTR_SSL_KEY]=$wl['key'];if($wl['cert'])$B[\PDO::MYSQL_ATTR_SSL_CERT]=$wl['cert'];if($wl['ca'])$B[\PDO::MYSQL_ATTR_SSL_CA]=$wl['ca'];if(isset($wl['verify']))$B[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT]=$wl['verify'];}$cf=$M["host"];$nj=$M["port"];$il=$M["socket"];return$this->dsn("mysql:charset=utf8".($cf!=""?";host=$cf":'').($nj!=""?";port=$nj":($il!=""?";unix_socket=$il":"")),$U,$D,$B);}function
set_charset($mb){return$this->query("SET NAMES $mb");}function
select_db($rc){return$this->query("USE ".idf_escape($rc));}function
query($F,$Rm=false){$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,!$Rm);return
parent::query($F,$Rm);}}}class
Driver
extends
SqlDriver{static$extensions=array("MySQLi","MySQL","PDO_MySQL");static$jush="sql";static$serverSocket=true;var$unsigned=array("unsigned","zerofill","unsigned zerofill");var$functions=array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");var$partitionBy=array("HASH","LINEAR HASH","KEY","LINEAR KEY","RANGE","LIST");function
operators($Ml){return
array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");}static
function
connect($M,$U,$D){$f=parent::connect($M,$U,$D);if(is_string($f)){if(function_exists('iconv')&&!is_utf8($f)&&strlen($vk=iconv("windows-1252","utf-8//IGNORE",$f))>strlen($f))$f=$vk;return$f;}$f->set_charset(charset($f));$f->query("SET sql_quote_show_create = 1, autocommit = 1");$f->flavor=(preg_match('~MariaDB~',$f->server_info)?'maria':'mysql');add_driver(DRIVER,($f->flavor=='maria'?"MariaDB":"MySQL"));return$f;}function
__construct(Db$f){parent::__construct($f);$this->types=array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Geometry'=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),);$this->insertFunctions=array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",);$this->editFunctions=array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",);if(min_version('5.7.8',10.2,$f))$this->types['Strings']["json"]=4294967295;if(min_version('',10.7,$f)){$this->types['Strings']["uuid"]=128;$this->insertFunctions['uuid']='uuid';}if(min_version('',10.5,$f)){$this->types['Network']["inet6"]=39;if(min_version('','10.10',$f))$this->types['Network']["inet4"]=15;}if(min_version(9,11.7,$f))$this->types['Numbers']["vector"]=16383;if(min_version(5.7,10.2,$f))$this->generated=array("STORED","VIRTUAL");}function
unconvertFunction(array$l){return(preg_match("~binary~",$l["type"])?"<code class='jush-sql'>UNHEX</code>":($l["type"]=="bit"?doc_link(array('sql'=>'bit-value-literals.html'),"<code>b''</code>"):($l["type"]=="vector"?"<code class='jush-sql'>".($this->conn->flavor=='maria'?"VEC_FromText":"STRING_TO_VECTOR")."</code>":(preg_match("~geom|point|linestring|polygon~",$l["type"])?"<code class='jush-sql'>GeomFromText</code>":""))));}function
insert($Q,array$N){return($N?parent::insert($Q,$N):queries("INSERT INTO ".table($Q)." ()\nVALUES ()"));}function
insertUpdate($Q,array$J,array$_j){$e=array_keys(reset($J));$vj="INSERT INTO ".table($Q)." (".implode(", ",$e).") VALUES\n";$Y=array();foreach($e
as$w)$Y[$w]="$w = VALUES($w)";$Fl="\nON DUPLICATE KEY UPDATE ".implode(", ",$Y);$Y=array();$x=0;foreach($J
as$N){$X="(".implode(", ",$N).")";if($Y&&(strlen($vj)+$x+strlen($X)+strlen($Fl)>1e6)){if(!queries($vj.implode(",\n",$Y).$Fl))return
false;$Y=array();$x=0;}$Y[]=$X;$x+=strlen($X)+2;}return
queries($vj.implode(",\n",$Y).$Fl);}function
slowQuery($F,$om){if(min_version('5.7.8','10.1.2')){if($this->conn->flavor=='maria')return"SET STATEMENT max_statement_time=$om FOR $F";elseif(preg_match('~^(SELECT\b)(.+)~is',$F,$_))return"$_[1] /*+ MAX_EXECUTION_TIME(".($om*1000).") */ $_[2]";}}function
convertColumn($t,array$l){if(preg_match("~binary~",$l["type"]))return"HEX($t)";if($l["type"]=="bit")return"BIN($t + 0)";if($l["type"]=="vector")return($this->conn->flavor=='maria'?"VEC_ToText":"VECTOR_TO_STRING")."($t)";if(preg_match("~geom|point|linestring|polygon~",$l["type"]))return(min_version(8)?"ST_":"")."AsWKT($t)";return"";}function
convertSearch($t,array$W,array$l){return($this->convertColumn($t,$l)?:(preg_match('~'.text_type().'~',$l["type"])&&!preg_match("~^utf8~",$l["collation"])&&preg_match('~[\x80-\xFF]~',$W['val'])?"CONVERT($t USING ".charset($this->conn).")":$t));}function
typeName(\stdClass$l){$A=parent::typeName($l);if($A!=""){$Qm=array("TINY"=>"tinyint","SHORT"=>"smallint","LONG"=>"int","INT24"=>"mediumint","LONGLONG"=>"bigint","NEWDECIMAL"=>"decimal","VAR_STRING"=>"varchar","STRING"=>"char",);return
idx($Qm,$A,strtolower($A));}$Qm=array("decimal","tinyint","smallint","int","float","double",7=>"timestamp","bigint","mediumint","date","time","datetime","year",15=>"varchar","bit",242=>"vector",245=>"json","decimal","enum","set","tinytext","mediumtext","longtext","text","varchar","char","geometry",);$H=idx($Qm,$l->type,"");return($l->charsetnr==63?str_replace(array("text","varchar","char"),array("blob","varbinary","binary"),$H):$H);}function
quoteBinary($vk){return"X".q(bin2hex($vk));}function
md5($d,array$l){if(is_blob($l)||preg_match('~'.text_type().'~',$l["type"]))return"MD5(".(is_blob($l)||preg_match("~^utf8~",$l["collation"])?$d:"CONVERT($d USING ".charset($this->conn).")").")";}function
warnings(){$G=$this->conn->query("SHOW WARNINGS");if($G&&$G->num_rows){ob_start();print_select_result($G);return
ob_get_clean();}}function
tableHelp($A,$Tf=false){$Eg=($this->conn->flavor=='maria');if(information_schema(DB))return
strtolower(str_replace("_","-",DB)."-".($Eg?"$A-table/":str_replace("_","-",$A)."-table.html"));if(DB=="sys")return($Eg?"sys-schema/":strtolower("sys-".str_replace("_","-",preg_replace('~^x\$~','',$A)).".html"));if(DB=="mysql")return($Eg?"mysql$A-table/":"system-schema.html");}function
partitionsInfo($Q){$qe="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($Q);$G=$this->conn->query("SELECT PARTITION_METHOD, PARTITION_EXPRESSION, PARTITION_ORDINAL_POSITION $qe ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");$I=($G?$G->fetch_row():null);if(!$I)return
array();$H=array();list($H["partition_by"],$H["partition"],$H["partitions"])=$I;$Wi=get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $qe AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");$H["partition_names"]=array_keys($Wi);$H["partition_values"]=array_values($Wi);return$H;}function
checkConstraints($Q){$H=parent::checkConstraints($Q);return($this->conn->flavor=='maria'?$H:array_map('stripslashes',$H));}function
hasCStyleEscapes(){static$fb;if($fb===null){$tl=get_val("SHOW VARIABLES LIKE 'sql_mode'",1,$this->conn);$fb=(strpos($tl,'NO_BACKSLASH_ESCAPES')===false);}return$fb;}function
hasEstimatedRows(){return
true;}function
isSystem($i,$K=""){return
information_schema($i,$K)||in_array($i,array("mysql","sys"));}function
lineComment(){return"#|-- ";}function
engines(){$H=array();foreach(get_rows("SHOW ENGINES")as$I){if(preg_match("~YES|DEFAULT~",$I["Support"]))$H[]=$I["Engine"];}return$H;}function
indexAlgorithms(array$Ml){return(preg_match('~^(MEMORY|NDB)$~',$Ml["Engine"])?array("HASH","BTREE"):array());}}function
idf_escape($t){return"`".str_replace("`","``",$t)."`";}function
table($t){return
idf_escape($t);}function
get_databases($fe){$H=get_session("dbs");if($H===null){$F="SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME";$xl=microtime(true);$H=($fe?slow_query($F):get_vals($F));if(microtime(true)-$xl>0.1){restart_session();set_session("dbs",$H);stop_session();}}return$H;}function
limit($F,$Z,$y,$Yh=0,$Lk=" "){return" $F$Z".($y?$Lk."LIMIT $y".($Yh?" OFFSET $Yh":""):"");}function
limit1($Q,$F,$Z,$Lk="\n"){return
limit($F,$Z,1,0,$Lk);}function
db_collation($i,array$Cb){$H=null;$ec=get_val("SHOW CREATE DATABASE ".idf_escape($i),1);if(preg_match('~ COLLATE ([^ ]+)~',$ec,$_))$H=$_[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$ec,$_))$H=$Cb[$_[1]][-1];return$H;}function
logged_user(){return
get_val("SELECT CURRENT_USER()");}function
tables_list(){return
get_key_vals("SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME");}function
count_tables(array$h){$H=array();foreach($h
as$i)$H[$i]=count(get_vals("SHOW TABLES IN ".idf_escape($i)));return$H;}function
table_status($A="",$Md=false){$H=array();$F="SELECT ENGINE AS Engine, TABLE_NAME AS Name, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($A!=""?"AND TABLE_NAME = ".q($A):"ORDER BY Name");$K=array();foreach(($Md?array():get_rows($F))as$I)$K[$I["Name"]]=$I;$zj=null;foreach(get_rows($Md?$F:"SHOW TABLE STATUS".($A!=""?" LIKE ".q(addcslashes($A,"%_\\")):""))as$I){$Ci=idx($K,$I["Name"]);if($Ci){if($I["Comment"]!==$Ci["Comment"]&&$I["Comment"]!==$zj)$I["Error"]=$I["Comment"];$zj=$I["Comment"];$I["Comment"]=$Ci["Comment"];$I["Engine"]=$Ci["Engine"];}if($I["Engine"]=="InnoDB")$I["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$I["Comment"]);if(!isset($I["Engine"]))$I["Comment"]="";if($A!="")$I["Name"]=$A;$H[$I["Name"]]=$I;}return$H;}function
is_view(array$R){return$R["Engine"]===null;}function
fk_support(array$R){return
preg_match('~InnoDB|IBMDB2I'.(min_version(5.6)?'|NDB':'').'~i',$R["Engine"]);}function
parse_type($te){preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$te,$_);return
array($_[1],$_[2],ltrim($_[3].$_[4]));}function
fields($Q){$Eg=(connection()->flavor=='maria');$H=array();foreach(get_rows("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ".q($Q)." ORDER BY ORDINAL_POSITION")as$I){$l=$I["COLUMN_NAME"];$T=$I["COLUMN_TYPE"];$ye=$I["GENERATION_EXPRESSION"];$Jd=$I["EXTRA"];preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$Jd,$xe);list($Pm,$x,$an)=parse_type($T);$j=$I["COLUMN_DEFAULT"];if($j!=""){$Sf=preg_match('~text|json~',$Pm);if(!$Eg&&$Sf)$j=preg_replace("~^(_\w+)?('.*')$~",'\2',stripslashes($j));if($Eg||$Sf){$j=($j=="NULL"?null:preg_replace_callback("~^'(.*)'$~",function($_){return
stripslashes(str_replace("''","'",$_[1]));},$j));}if(!$Eg&&preg_match('~binary~',$Pm)&&preg_match('~^0x(\w*)$~',$j,$_))$j=pack("H*",$_[1]);}$H[$l]=array("field"=>$l,"full_type"=>$T,"type"=>$Pm,"length"=>$x,"unsigned"=>$an,"default"=>($xe?($Eg?$ye:stripslashes($ye)):$j),"null"=>($I["IS_NULLABLE"]=="YES"),"auto_increment"=>($Jd=="auto_increment"),"on_update"=>(preg_match('~\bon update (\w+)~i',$Jd,$_)?$_[1]:""),"collation"=>$I["COLLATION_NAME"],"privileges"=>array_flip(explode(",","$I[PRIVILEGES],where,order")),"comment"=>$I["COLUMN_COMMENT"],"primary"=>($I["COLUMN_KEY"]=="PRI"),"generated"=>($xe[1]=="PERSISTENT"?"STORED":$xe[1]),);}return$H;}function
indexes($Q,$g=null){$H=array();foreach(get_rows("SHOW INDEX FROM ".table($Q),$g)as$I){$A=$I["Key_name"];$H[$A]["type"]=($A=="PRIMARY"?"PRIMARY":($I["Index_type"]=="FULLTEXT"?"FULLTEXT":($I["Non_unique"]?(preg_match('~^(SPATIAL|VECTOR)$~',$I["Index_type"])?$I["Index_type"]:"INDEX"):"UNIQUE")));$H[$A]["columns"][]=$I["Column_name"];$H[$A]["lengths"][]=($I["Index_type"]=="SPATIAL"?null:$I["Sub_part"]);$H[$A]["descs"][]=null;$H[$A]["algorithm"]=$I["Index_type"];}return$H;}function
foreign_keys($Q){static$ej='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$H=array();$fc=get_val("SHOW CREATE TABLE ".table($Q),1);if($fc){preg_match_all("~CONSTRAINT ($ej) FOREIGN KEY ?\\(((?:$ej,? ?)+)\\) REFERENCES ($ej)(?:\\.($ej))? \\(((?:$ej,? ?)+)\\)(?: ON DELETE (".driver()->onActions."))?(?: ON UPDATE (".driver()->onActions."))?~",$fc,$Hg,PREG_SET_ORDER);foreach($Hg
as$_){preg_match_all("~$ej~",$_[2],$ml);preg_match_all("~$ej~",$_[5],$em);$H[idf_unescape($_[1])]=array("db"=>idf_unescape($_[4]!=""?$_[3]:$_[4]),"table"=>idf_unescape($_[4]!=""?$_[4]:$_[3]),"source"=>array_map('Adminer\idf_unescape',$ml[0]),"target"=>array_map('Adminer\idf_unescape',$em[0]),"on_delete"=>($_[6]?:"RESTRICT"),"on_update"=>($_[7]?:"RESTRICT"),);}}return$H;}function
view($A){return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',get_val("SHOW CREATE VIEW ".table($A),1)));}function
collations(){$H=array();foreach(get_rows("SHOW COLLATION")as$I){if($I["Default"])$H[$I["Charset"]][-1]=$I["Collation"];else$H[$I["Charset"]][]=$I["Collation"];}ksort($H);foreach($H
as$w=>$W)sort($H[$w]);return$H;}function
information_schema($i,$K=""){return($i=="information_schema")||(min_version(5.5)&&$i=="performance_schema");}function
error(){return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",connection()->error));}function
create_database($i,$Bb){return
queries("CREATE DATABASE ".idf_escape($i).($Bb?" COLLATE ".q($Bb):""));}function
drop_databases(array$h){$H=apply_queries("DROP DATABASE",$h,'Adminer\idf_escape');restart_session();set_session("dbs",null);return$H;}function
rename_database($A,$Bb){$H=false;if(create_database($A,$Bb)){$S=array();$_n=array();foreach(tables_list()as$Q=>$T){if($T=='VIEW')$_n[]=$Q;else$S[]=$Q;}$H=(!$S&&!$_n)||move_tables($S,$_n,$A);drop_databases($H?array(DB):array());}return$H;}function
auto_increment(){$Ma=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$u){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$u["columns"],true)){$Ma="";break;}if($u["type"]=="PRIMARY")$Ma=" UNIQUE";}}return" AUTO_INCREMENT$Ma";}function
alter_table($Q,$A,array$m,array$he,$Hb,$nd,$Bb,$La,$Vi){$b=array();foreach($m
as$l){if($l[1]){$j=$l[1][3];if(preg_match('~ GENERATED~',$j)){$l[1][3]=(connection()->flavor=='maria'?"":$l[1][2]);$l[1][2]=$j;}$b[]=($Q!=""?($l[0]!=""?"CHANGE ".idf_escape($l[0]):"ADD"):" ")." ".implode($l[1]).($Q!=""?$l[2]:"");}else$b[]="DROP ".idf_escape($l[0]);}$b=array_merge($b,$he);$O=($Hb!==null?" COMMENT=".q($Hb):"").($nd?" ENGINE=".q($nd):"").($Bb?" COLLATE ".q($Bb):"").($La!=""?" AUTO_INCREMENT=$La":"");if($Vi){$Wi=array();if($Vi["partition_by"]=='RANGE'||$Vi["partition_by"]=='LIST'){foreach($Vi["partition_names"]as$w=>$W){$X=$Vi["partition_values"][$w];$Wi[]="\n  PARTITION ".idf_escape($W)." VALUES ".($Vi["partition_by"]=='RANGE'?"LESS THAN":"IN").($X!=""?" ($X)":" MAXVALUE");}}$O
.="\nPARTITION BY $Vi[partition_by]($Vi[partition])";if($Wi)$O
.=" (".implode(",",$Wi)."\n)";elseif($Vi["partitions"])$O
.=" PARTITIONS ".(+$Vi["partitions"]);}elseif($Vi===null)$O
.="\nREMOVE PARTITIONING";if($Q=="")return
queries("CREATE TABLE ".table($A)." (\n".implode(",\n",$b)."\n)$O");if($Q!=$A)$b[]="RENAME TO ".table($A);if($O)$b[]=ltrim($O);return($b?queries("ALTER TABLE ".table($Q)."\n".implode(",\n",$b)):true);}function
alter_indexes($Q,$b){$kb=array();foreach($b
as$W)$kb[]=($W[2]=="DROP"?"\nDROP INDEX ".idf_escape($W[1]):"\nADD $W[0] ".($W[0]=="PRIMARY"?"KEY ":"").($W[1]!=""?idf_escape($W[1])." ":"")."(".implode(", ",$W[2]).")");return
queries("ALTER TABLE ".table($Q).implode(",",$kb));}function
truncate_tables(array$S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views(array$_n){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$_n)));}function
drop_tables(array$S){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$S)));}function
move_tables(array$S,array$_n,$em){$ak=array();foreach($S
as$Q)$ak[]=table($Q)." TO ".idf_escape($em).".".table($Q);if(!$ak||queries("RENAME TABLE ".implode(", ",$ak))){$Bc=array();foreach($_n
as$Q)$Bc[table($Q)]=view($Q);connection()->select_db($em);$i=idf_escape(DB);foreach($Bc
as$A=>$zn){if(!queries("CREATE VIEW $A AS ".str_replace(" $i."," ",$zn["select"]))||!queries("DROP VIEW $i.$A"))return
false;}return
true;}return
false;}function
copy_tables(array$S,array$_n,$em){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($S
as$Q){$A=($em==DB?table("copy_$Q"):idf_escape($em).".".table($Q));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $A"))||!queries("CREATE TABLE $A LIKE ".table($Q))||!queries("INSERT INTO $A SELECT * FROM ".table($Q)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I){$Fm=$I["Trigger"];list($wd,$Th)=trigger_event($I);if(!queries("CREATE TRIGGER ".($em==DB?idf_escape("copy_$Fm"):idf_escape($em).".".idf_escape($Fm))." $I[Timing] $wd".($Th!=""?" $Th":"")." ON $A FOR EACH ROW\n$I[Statement];"))return
false;}}foreach($_n
as$Q){$A=($em==DB?table("copy_$Q"):idf_escape($em).".".table($Q));$zn=view($Q);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $A"))||!queries("CREATE VIEW $A AS $zn[select]"))return
false;}return
true;}function
trigger_event(array$I){$yd=explode(",",$I["Event"]);$H=array();foreach(array("DELETE","INSERT","UPDATE")as$wd){if(in_array($wd,$yd))$H[]=$wd;}$H=implode(" OR ",$H);if(in_array("UPDATE",$yd)&&min_version('','12.0.1')&&preg_match('~\s(?:BEFORE|AFTER)\s+(.+?)\s+ON\s~is',get_val("SHOW CREATE TRIGGER ".idf_escape($I["Trigger"]),2),$_)&&preg_match('~\bOF\s+(.+)~is',$_[1],$Th))return
array("$H OF",$Th[1]);return
array($H,"");}function
trigger($A,$Q){if($A=="")return
array();$J=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($A));$H=reset($J);if($H)list($H["Event"],$H["Of"])=trigger_event($H);return($H?:array());}function
triggers($Q){$H=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I){list($wd)=trigger_event($I);$H[$I["Trigger"]]=array($I["Timing"],$wd);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>(min_version('','12.0.1')?array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF",):array("INSERT","UPDATE","DELETE")),"Type"=>array("FOR EACH ROW"),);}function
routine($A,$T){$J=get_rows("SELECT PARAMETER_NAME, DTD_IDENTIFIER, PARAMETER_MODE, COLLATION_NAME
FROM information_schema.PARAMETERS
WHERE SPECIFIC_SCHEMA = DATABASE() AND ROUTINE_TYPE = '$T' AND SPECIFIC_NAME = ".q($A)."
ORDER BY ORDINAL_POSITION");$m=array();foreach($J
as$I){$te=$I["DTD_IDENTIFIER"];list($Pm,$x,$an)=parse_type($te);$m[]=array("field"=>$I["PARAMETER_NAME"],"type"=>$Pm,"length"=>$x,"unsigned"=>$an,"null"=>true,"full_type"=>$te,"inout"=>($T=="FUNCTION"?"":$I["PARAMETER_MODE"]),"collation"=>$I["COLLATION_NAME"],);}$H=connection()->query("SELECT
	ROUTINE_COMMENT comment,
	ROUTINE_DEFINITION definition,
	LOWER(EXTERNAL_LANGUAGE) language,
	IF(DEFINER = CURRENT_USER(), '', DEFINER) definer,
	IF(IS_DETERMINISTIC = 'YES', 'DETERMINISTIC', 'NOT DETERMINISTIC') is_deterministic,
	SQL_DATA_ACCESS data_access,
	CONCAT('SQL SECURITY ', SECURITY_TYPE) security
FROM information_schema.ROUTINES
WHERE ROUTINE_SCHEMA = DATABASE() AND ROUTINE_TYPE = '$T' AND ROUTINE_NAME = ".q($A))->fetch_assoc();if(!$H)return
array();$H['options']=array("DEFINER"=>$H['definer'],"DETERMINISTIC"=>$H['is_deterministic'],"SQL_DATA_ACCESS"=>$H['data_access'],"SQL_SECURITY"=>$H['security'],"COMMENT"=>$H['comment'],);if($m&&$m[0]['field']=='')$H['returns']=array_shift($m);$H['fields']=$m;return$H;}function
routines(){return
get_rows("SELECT SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE()");}function
routine_languages(){return(min_version(9,99)?array("sql"=>"sql","javascript"=>"js"):array());}function
routine_options($mk){return
array("DEFINER"=>array(),"DETERMINISTIC"=>array("NOT DETERMINISTIC","DETERMINISTIC"),"SQL_DATA_ACCESS"=>array("CONTAINS SQL","NO SQL","READS SQL DATA","MODIFIES SQL DATA"),"SQL_SECURITY"=>array("SQL SECURITY DEFINER","SQL SECURITY INVOKER"),"COMMENT"=>array(),);}function
routine_id($A,array$I){return
idf_escape($A);}function
last_id($G){return
get_val("SELECT LAST_INSERT_ID()");}function
explain(Db$f,$F){return$f->query("EXPLAIN ".(min_version(5.7)?"":"PARTITIONS ").$F);}function
found_rows(array$R,array$Z){return($Z||$R["Engine"]!="InnoDB"?null:$R["Rows"]);}function
create_sql($Q,$La,$Dl){$H=get_val("SHOW CREATE TABLE ".table($Q),1);if(!$La)$H=preg_replace('~(\n\)[^\n]*?) AUTO_INCREMENT=\d+~','\1',$H);return$H;}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
use_sql($rc,$Dl=""){$A=idf_escape($rc);$H="";if(preg_match('~CREATE~',$Dl)&&($ec=get_val("SHOW CREATE DATABASE $A",1))){set_utf8mb4($ec);if($Dl=="DROP+CREATE")$H="DROP DATABASE IF EXISTS $A;\n";$H
.="$ec;\n";}return$H."USE $A";}function
trigger_sql($Q){$H="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")),null,"-- ")as$I){list($I["Event"],$I["Of"])=trigger_event($I);$H
.="\n".create_trigger(" ON ".table($I["Table"]),$I+array("Type"=>"FOR EACH ROW")).";\n";}return$H;}function
show_variables(){return
get_rows("SHOW VARIABLES");}function
show_status(){return
get_rows("SHOW STATUS");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
convert_field(array$l){return
driver()->convertColumn(idf_escape($l["field"]),$l);}function
unconvert_field(array$l,$H){if(preg_match("~binary~",$l["type"]))$H="UNHEX($H)";if($l["type"]=="bit")$H="CONVERT(b$H, UNSIGNED)";if($l["type"]=="vector")$H=(connection()->flavor=='maria'?"VEC_FromText":"STRING_TO_VECTOR")."($H)";if(preg_match("~geom|point|linestring|polygon~",$l["type"])){$vj=(min_version(8)?"ST_":"");$H=$vj."GeomFromText($H, $vj"."SRID($l[field]))";}return$H;}function
support($Nd){return
preg_match('~^(comment|columns|copy|database|drop_col|dump|event|indexes|kill|privileges|move_col|procedure|processlist|routine|sql|status|table|trigger|variables|view'.(min_version(8)?'|descidx':'').(min_version('8.0.16','10.2.1')?'|check':'').(min_version(8,99)?'|fast_status':'').')$~',$Nd);}function
kill_process($s){return
queries("KILL ".number($s));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){return
get_val("SELECT @@max_connections");}function
types($Id=false){return
array();}function
type_values($s){return"";}function
type_definition($s){return
array("kind"=>"","definition"=>"");}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($K,$g=null){return
true;}}define('Adminer\JUSH',Driver::$jush);define('Adminer\SERVER',"".$_GET[DRIVER]);define('Adminer\DB',"$_GET[db]");define('Adminer\ME',preg_replace('~\?.*~','',relative_uri()).'?'.(sid()?SID.'&':'').($_GET["ext"]?"ext=".url_escape($_GET["ext"]).'&':'').(isset($_GET[DRIVER])?DRIVER."=".url_escape(SERVER).'&':'').(isset($_GET["username"])?"username=".url_escape($_GET["username"]).'&':'').(isset($_GET["db"])?'db='.url_escape(DB).'&'.(isset($_GET["ns"])?"ns=".url_escape($_GET["ns"])."&":""):''));if(isset($_GET["manifest"])){header("Content-Type: application/manifest+json; charset=utf-8");header("Cache-Control: no-cache");echo
json_encode(adminer()->manifest(),64|256);exit;}function
page_header($qm,$k="",$bb=array(),$rm="",$Mh=false,$Sc=""){if($Mh){header("HTTP/1.1 404 Not Found");$k=($k?:'Not found.');}page_headers();if(is_ajax()&&$k){page_messages($k);exit;}if(!ob_get_level())ob_start('ob_gzhandler',4096);$sm=$qm.($rm!=""?": $rm":"");$tm=strip_tags($sm.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".adminer()->name());echo'<!DOCTYPE html>
<html lang=\'en\' dir=\'ltr\' class=\'ltr nojs\'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>',$tm,'</title>
<link rel="stylesheet" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=6.1.1+43f4678f"),'">
';$jc=adminer()->css();if(is_int(key($jc)))$jc=array_fill_keys($jc,'light');$Me=in_array('light',$jc)||in_array('',$jc);$Ke=in_array('dark',$jc)||in_array('',$jc);$nc=($Me?($Ke?null:false):($Ke?:null));$Wg=" media='(prefers-color-scheme: dark)'";if($nc!==false)echo"<link rel='stylesheet'".($nc?"":$Wg)." href='".h(preg_replace("~\\?.*~","",ME)."?file=dark.css&version=6.1.1+43f4678f")."'>\n";echo"<meta name='color-scheme' content='".($nc===null?"light dark":($nc?"dark":"light"))."'>\n",script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=6.1.1+43f4678f");if(adminer()->head($nc))echo"<link rel='icon' href='data:image/gif;base64,"."R0lGODlhEAAQAJEAAAQCBPz+/PwCBAROZCH5BAEAAAAALAAAAAAQABAAAAI2hI+pGO1rmghihiUdvUBnZ3XBQA7f05mOak1RWXrNq5nQWHMKvuoJ37BhVEEfYxQzHjWQ5qIAADs='>\n","<link rel='apple-touch-icon' href='".h(preg_replace("~\\?.*~","",ME)."?file=logo.svg&version=6.1.1+43f4678f")."'>\n";if(adminer()->manifest())echo"<link rel='manifest' href='".h(preg_replace('~\?.*~','',ME)."?manifest=")."' crossorigin='use-credentials'>\n";foreach($jc
as$hn=>$lh){$c=($lh=='dark'&&!$nc?$Wg:($lh=='light'&&$Ke?" media='(prefers-color-scheme: light)'":""));echo"<link rel='stylesheet'$c href='".h($hn)."'>\n";}echo"\n<body class='";adminer()->bodyClass();echo"'>\n",script((isset($_COOKIE["adminer_version"])||!adminer()->verifyVersion()?"":"onload = partial(verifyVersion, '".VERSION."');\n")."
const offlineMessage = '".js_escape('You are offline.')."';
const numberFormat = '".js_escape('#,##0')."';
const numberDigits = '".js_escape('0123456789')."';
const urlSeparators = '".js_escape(ini_get("arg_separator.input"))."';"),"<div id='help' class='jush-".JUSH." jsonly hidden'".on('mouseover','helpKeep').on('mouseout','helpMouseout')."></div>\n","<div id='content'>\n","<span id='menuopen' class='jsonly'".on('click','menuToggle')."><button title='".'Menu'."' class='icon icon-move' aria-expanded='false'></button></span>\n";if($bb!==null){$z=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($z?:".").'">'.get_driver(DRIVER).'</a> » ';$z=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$M=adminer()->serverName(SERVER);$M=($M!=""?$M:'Server');if($bb===false)echo"$M\n";else{echo"<a href='".h($z.(DB!=""&&support("single_db")?"&db=":""))."' accesskey='1' title='Alt+Shift+1'>$M</a> » ";$Ck="";if(is_string($bb)){$Ck=$bb;$bb=array();}if($_GET["ns"]!=""||(DB!=""&&is_array($bb))){$tc="$z&db=".url_escape(DB).(support("scheme")?"&ns=":"").(support("single_table")?"&select=":"");echo'<a href="'.h($tc.($_GET["ns"]==""?$Ck:"")).'">'.h(DB).'</a> » ';}if(is_array($bb)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1).$Ck).'">'.h($_GET["ns"]).'</a> » ';foreach($bb
as$w=>$W){$Dc=(is_array($W)?$W[1]:h($W));if($Dc!="")echo"<a href='".h(ME."$w=").url_escape(is_array($W)?$W[0]:$W)."'>$Dc</a> » ";}}echo"$qm\n";}}echo"<h2>$sm$Sc</h2>\n","<div id='ajaxstatus' role='status' class='jsonly'></div>\n";restart_session();page_messages($k);adminer()->serviceWorker();$h=&get_session("dbs");if(DB!=""&&$h&&!in_array(DB,$h,true))$h=null;stop_session();define('Adminer\PAGE_HEADER',1);ob_flush();flush();if($Mh){page_footer($Mh===true?"":$Mh);exit;}}function
service_worker(){$Xj=has_passwords();$zb=($Xj?"navigator.serviceWorker.register('".js_escape(preg_replace('~\?.*~','',ME)."?file=worker.js&version=6.1.1+43f4678f")."', {scope: location.pathname}).catch(() => {});":"navigator.serviceWorker.getRegistration().then(registration => registration && registration.unregister());
	caches.keys().then(keys => keys.forEach(key => key.startsWith('adminer-') && caches.delete(key)));");echo
script("if (navigator.serviceWorker) {\n\t$zb\n}");}function
has_passwords(){foreach((array)$_SESSION["pwds"]as$Yk){foreach($Yk
as$qn){foreach($qn
as$D){if($D!==null)return
true;}}}return
false;}function
page_headers(){header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach(adminer()->csp(csp())as$ic){$Re=array();foreach($ic
as$w=>$W)$Re[]="$w $W";header("Content-Security-Policy: ".implode("; ",$Re));}adminer()->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self' https://www.adminer.org","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
design_checksums(){$nn=array();foreach(array_keys(adminer()->css())as$hn)$nn[preg_replace('~\?.*~','',$hn)]=true;$H=array();foreach(array("adminer.css","adminer-dark.css")as$n){if($nn[$n]&&file_exists($n)){preg_match('~^/\* Adminer design ([-\w]+) \*/~',file_get_contents($n),$_);$H[$n]=array((string)$_[1],Plugins::checksum($n));}}return$H;}function
official_design_checksums(){return
array('adminer-border/adminer.css'=>'ec757f3e','adminer-dark/adminer-dark.css'=>'a26bcd7b','brade/adminer.css'=>'be4161f0','bueltge/adminer.css'=>'1a8f00b4','cpanel/adminer.css'=>'59ce604e','dracula/adminer-dark.css'=>'cfaf61dd','esterka/adminer.css'=>'1f805f36','flat/adminer.css'=>'49a61af9','galkaev/adminer-dark.css'=>'16c46f94','haeckel/adminer.css'=>'147a3565','hever/adminer.css'=>'ef0e1948','konya/adminer.css'=>'2b409696','lavender-light/adminer.css'=>'bf03f5d7','lucas-sandery/adminer.css'=>'6596353','mancave/adminer-dark.css'=>'e1ac813d','mvt/adminer.css'=>'ebd3afdc','nette/adminer.css'=>'5ab360e7','ng9/adminer.css'=>'488583cf','nicu/adminer.css'=>'216f097b','pappu687/adminer.css'=>'b58d128c','paranoiq/adminer.css'=>'64d27e5','pepa-linha/adminer.css'=>'baf25f0','pokorny/adminer.css'=>'ee9eea6d','price/adminer.css'=>'81be9a85','rmsoft/adminer.css'=>'6cd4a237','rmsoft_blue-dark/adminer.css'=>'32102a8','rmsoft_blue/adminer.css'=>'7d8d5b18','win98/adminer.css'=>'e82d63c3',);}function
version_iframe(){return(isset($_COOKIE["adminer_version"])||!adminer()->verifyVersion()?"":"<noscript><iframe sandbox src='https://www.adminer.org/version/?current=".VERSION."&amp;noscript=1'></iframe></noscript>");}function
get_nonce(){static$Lh;if(!$Lh)$Lh=base64_encode(rand_string());return$Lh;}function
page_messages($k){$gn=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$dh=idx($_SESSION["messages"],$gn);if($dh){echo"<div class='message'>".implode("</div>\n<div class='message'>",$dh)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$gn]);}if($k)echo"<div class='error'>$k</div>\n";if(adminer()->error)echo"<div class='error'>".adminer()->error."</div>\n";}function
page_footer($kh=""){echo"</div>\n\n<div id='foot' class='foot'>\n<div id='menu'>\n";adminer()->navigation($kh);echo"</div>\n";if($kh!="auth")echo'<form action="" method="post">
<p class="logout">
<span title="Username">',h($_GET["username"])."\n",'</span>
<input type=\'submit\' name=\'logout\' value=\'Logout\' id=\'logout\'>
',input_token(),'</form>
';echo"</div>\n\n",script("setupSubmitHighlight(document);");}function
int32($th){while($th>=2147483648)$th-=4294967296;while($th<=-2147483649)$th+=4294967296;return(int)$th;}function
long2str(array$V,$Bn){$vk='';foreach($V
as$W)$vk
.=pack('V',$W);if($Bn)return
substr($vk,0,end($V));return$vk;}function
str2long($vk,$Bn){$V=array_values(unpack('V*',str_pad($vk,4*ceil(strlen($vk)/4),"\0")));if($Bn)$V[]=strlen($vk);return$V;}function
xxtea_mx($Mn,$Ln,$Gl,$Yf){return
int32((($Mn>>5&0x7FFFFFF)^$Ln<<2)+(($Ln>>3&0x1FFFFFFF)^$Mn<<4))^int32(($Gl^$Ln)+($Yf^$Mn));}function
encrypt_string($Al,$w){if($Al=="")return"";$w=array_values(unpack("V*",pack("H*",md5($w))));$V=str2long($Al,true);$th=count($V)-1;$Mn=$V[$th];$Ln=$V[0];$Ij=floor(6+52/($th+1));$Gl=0;while($Ij-->0){$Gl=int32($Gl+0x9E3779B9);$dd=$Gl>>2&3;for($Ii=0;$Ii<$th;$Ii++){$Ln=$V[$Ii+1];$sh=xxtea_mx($Mn,$Ln,$Gl,$w[$Ii&3^$dd]);$Mn=int32($V[$Ii]+$sh);$V[$Ii]=$Mn;}$Ln=$V[0];$sh=xxtea_mx($Mn,$Ln,$Gl,$w[$Ii&3^$dd]);$Mn=int32($V[$th]+$sh);$V[$th]=$Mn;}return
long2str($V,false);}function
decrypt_string($Al,$w){if($Al=="")return"";if(!$w)return
false;$w=array_values(unpack("V*",pack("H*",md5($w))));$V=str2long($Al,false);$th=count($V)-1;$Mn=$V[$th];$Ln=$V[0];$Ij=floor(6+52/($th+1));$Gl=int32($Ij*0x9E3779B9);while($Gl){$dd=$Gl>>2&3;for($Ii=$th;$Ii>0;$Ii--){$Mn=$V[$Ii-1];$sh=xxtea_mx($Mn,$Ln,$Gl,$w[$Ii&3^$dd]);$Ln=int32($V[$Ii]-$sh);$V[$Ii]=$Ln;}$Mn=$V[$th];$sh=xxtea_mx($Mn,$Ln,$Gl,$w[$Ii&3^$dd]);$Ln=int32($V[0]-$sh);$V[0]=$Ln;$Gl=int32($Gl-0x9E3779B9);}return
long2str($V,true);}$hj=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$W){list($w)=explode(":",$W);$hj[$w]=$W;}}function
add_invalid_login(){$Ta=get_temp_dir()."/adminer-invalid";foreach(glob("$Ta*")?:array($Ta)as$n){$ne=file_open_lock($n);if($ne)break;}if(!$ne)$ne=file_open_lock("$Ta-".rand_string());if(!$ne)return;$Lf=json_decode(stream_get_contents($ne),true);$nm=time();if($Lf){foreach($Lf
as$Mf=>$W){if($W[0]<$nm)unset($Lf[$Mf]);}}$Jf=&$Lf[adminer()->bruteForceKey()];if(!$Jf)$Jf=array($nm+30*60,0);$Jf[1]++;file_write_unlock($ne,json_encode($Lf));}function
check_invalid_login(array&$hj){$Lf=array();foreach(glob(get_temp_dir()."/adminer-invalid*")as$n){$ne=file_open_lock($n);if($ne){$Lf=json_decode(stream_get_contents($ne),true);file_unlock($ne);break;}}$w=adminer()->bruteForceKey();$Jf=idx($Lf,$w,array());$Kh=($Jf[1]>29?$Jf[0]-time():0);if($Kh>0){$k=lang_format(array('Too many unsuccessful logins, try again in %d minute.','Too many unsuccessful logins, try again in %d minutes.'),ceil($Kh/60));if($_SERVER["HTTP_X_FORWARDED_FOR"]!=""&&$w==$_SERVER["REMOTE_ADDR"])$k
.='<br>'.sprintf('Use the %s <a%s>plugin</a> if Adminer runs behind a reverse proxy.','<b>login-reverse-proxy</b>'," href='https://www.adminer.org/plugins/?version=".VERSION."'".target_blank());auth_error($k,$hj,false);}}function
password_required(){static$H;if($H===null){$H=(bool)get_session("password_required");if(!$H){$hc=adminer()->credentials();$H=!is_object(Driver::connect($hc[0],$hc[1],""));if($H)set_session("password_required",true);}}return$H;}function
require_password_link($D){$oh="<a href='https://www.adminer.org/password/'".target_blank().">".'More options'."</a>";if(!function_exists('password_hash'))return" $oh";$kj=($D!==null?$D:base64_encode(substr(pack("H*",rand_string()),0,12)));$Qe=password_hash($kj,PASSWORD_DEFAULT);$n="adminer-plugins.php";$Cd=file_exists("adminer-plugins.php");if($Cd)$Hf=($D!==null?sprintf('Add this line to %s to require the entered password:',"<b>$n</b>"):sprintf('Add this line to %s to require the password %s:',"<b>$n</b>","<b>$kj</b>"));else{$n="<button name='password_less' value='".h($Qe)."' class='link'>$n</button>";$Hf=($D!==null?sprintf('Save %s next to Adminer to require the entered password:',$n):sprintf('Save %s next to Adminer to require the password %s:',$n,"<b>$kj</b>"));}$wg="\t<a>new</a> Adminer\\Password(<span class='jush-apo'>'".h($Qe)."'</span>),";$H="<p>$Hf
<pre><code class='jush'>".($Cd?$wg:"&lt;?php\n<a>return</a> <a>array</a>(\n$wg\n);")."</code></pre>
<p>$oh
";return" <a href='#password-less' class='toggle'>".'Require a password.'."</a>
<div id='password-less' class='hidden'>".($Cd?$H:"<form action='' method='post'>\n".$H.input_token()."</form>")."</div>";}if(preg_match('~^[-\w$./]+$~',$_POST["password_less"])&&verify_token()){header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=adminer-plugins.php");echo"<?php\nreturn array(\n\tnew Adminer\\Password('$_POST[password_less]'),\n);\n";exit;}$Ka=$_POST["auth"];if($Ka&&(!adminer()->verifyLoginToken()||verify_token())){session_regenerate_id();$xn=$Ka["driver"];$M=$Ka["server"];$U=$Ka["username"];$D=(string)$Ka["password"];$i=$Ka["db"];set_password($xn,$M,$U,$D);$_SESSION["db"][$xn][$M][$U][$i]=true;if($Ka["permanent"]){$w=implode("-",array_map('base64_encode',array($xn,$M,$U,$i)));$Cj=adminer()->permanentLogin(true);$hj[$w]="$w:".base64_encode($Cj?encrypt_string($D,$Cj):"");cookie("adminer_permanent",implode(" ",$hj));}if(!array_diff(array_keys($_POST),array("auth","token"))||$xn!=DRIVER||$M!=SERVER||$U!==$_GET["username"]||$i!=DB)redirect(auth_url($xn,$M,$U,$i));}elseif($_POST["logout"]&&(!$_SESSION["token"]||verify_token())){Driver::disconnect();foreach(array("pwds","db","dbs","queries")as$w)set_session($w,null);unset_permanent($hj);redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.'.' '.'Thanks for using Adminer. Consider <a href="https://www.adminer.org/en/donation/">donating</a>.');}elseif($hj&&!$_SESSION["pwds"]){session_regenerate_id();$Cj=adminer()->permanentLogin();foreach($hj
as$w=>$W){list(,$wb)=explode(":",$W);list($xn,$M,$U,$i)=array_map('base64_decode',explode("-",$w));set_password($xn,$M,$U,decrypt_string(base64_decode($wb),$Cj));$_SESSION["db"][$xn][$M][$U][$i]=true;}}function
unset_permanent(array&$hj){foreach($hj
as$w=>$W){list($xn,$M,$U,$i)=array_map('base64_decode',explode("-",$w));if($xn==DRIVER&&$M==SERVER&&$U==$_GET["username"]&&$i==DB)unset($hj[$w]);}cookie("adminer_permanent",implode(" ",$hj));}function
auth_error($k,array&$hj,$Kf=true){$Zk=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$Zk]||$_GET[$Zk])&&!$_SESSION["token"])$k='Session expired. Please log in again.';elseif($Kf&&($D=get_password())!==null){restart_session();add_invalid_login();if($D===false)$k
.=($k?'<br>':'').sprintf('Master password expired. <a href="https://www.adminer.org/en/extension/"%s>Implement</a> the %s method to make it permanent.',target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);unset_permanent($hj);}}if(!$_COOKIE[$Zk]&&$_GET[$Zk]&&ini_bool("session.use_only_cookies"))$k='Session support must be enabled.';$Ni=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?:rand_string()),$Ni["lifetime"]);if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);page_header('Login',$k,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth","token")))echo"<p class='message'>".'The action will be performed after successful login with the same credentials.'."\n";echo
input_token(),"</div>\n";adminer()->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists('Adminer\Db')){unset($_SESSION["pwds"][DRIVER]);unset_permanent($hj);page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",Driver::$extensions)),false);page_footer("auth");exit;}$f='';if(isset($_GET["username"])&&is_string(get_password())){check_invalid_login($hj);$hc=adminer()->credentials();$f=Driver::connect($hc[0],$hc[1],$hc[2]);if(is_object($f)){Db::$instance=$f;Driver::$instance=new
Driver($f);if($f->flavor)save_settings(array("vendor-".DRIVER."-".SERVER=>get_driver(DRIVER)));}}$Cg=null;if(!is_object($f)||($Cg=adminer()->login($_GET["username"],get_password()))!==true){$k=(is_string($f)?nl_br(h($f)):(is_string($Cg)?$Cg:'Invalid credentials.')).(preg_match('~^ | $~',get_password())?'<br>'.'There is a space in the entered password, which might be the cause.':'');auth_error($k,$hj);}if($_POST["logout"]&&$_SESSION["token"]&&!verify_token()){page_header('Logout','Invalid CSRF token. Submit the form again.');page_footer("db");exit;}if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);stop_session(true);if($Ka&&$_POST["token"])$_POST["token"]=get_token();$k='';if($_POST){if(!verify_token()){header("HTTP/1.1 403 Forbidden");$k='Invalid CSRF token. Submit the form again.'.' '.'If you did not send this request from Adminer, close this page.';}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){header("HTTP/1.1 413 Content Too Large");$k=sprintf('The POST data is too large. Reduce the data or increase the %s configuration directive.',"<b>post_max_size</b>");if(isset($_GET["sql"]))$k
.=' '.'You can upload a large SQL file via FTP and import it from the server.';}function
print_select_result($G,$g=null,array$xi=array(),&$y=0,&$ed=false){$yg=array();$v=array();$e=array();$S=array();$_j=array();$gd=array();$Qm=array();$H=array();$mh=$ed;$ed=false;for($r=0;(!$y||$r<$y)&&($I=$G->fetch_row());$r++){if(!$r){echo"<div class='scrollable'>\n","<table class='nowrap odds'".($mh?on('click','tableClick').on('dblclick','tableClick').on('keydown','editingKeydown'):"").">\n","<thead><tr>";for($Vf=0;$Vf<count($I);$Vf++){$l=$G->fetch_field();$A=$l->name;$Q=(isset($l->table)?$l->table:"");$wi=(isset($l->orgtable)?$l->orgtable:"");$vi=(isset($l->orgname)?$l->orgname:$A);$Pm=driver()->typeName($l);if($xi&&JUSH=="sql")$yg[$Vf]=($A=="table"?"table=":($A=="possible_keys"?"indexes=":null));elseif($wi!=""){$ua=($Q!=""?$Q:$wi);if($Q!="")$H[$Q]=$wi;if(!isset($v[$ua])){if(!isset($_j[$wi])){$_j[$wi]=array();foreach(indexes($wi,$g)as$u){if($u["type"]=="PRIMARY"){$_j[$wi]=array_flip($u["columns"]);break;}}}$S[$ua]=$wi;$v[$ua]=$_j[$wi];$e[$ua]=$_j[$wi];}if(isset($e[$ua][$vi])){unset($e[$ua][$vi]);$v[$ua][$vi]=$Vf;$yg[$Vf]=$ua;}elseif($mh&&isset($l->orgname)&&$l->db==DB&&!is_blob(array("type"=>$Pm)))$gd[$Vf]=array($ua,$vi,preg_match('~text|json|lob~',$Pm));}$Qm[$Vf]=$Pm;echo"<th title='".h(trim(($wi!=""?"$wi.$vi":($l->name!=$vi?$vi:""))." ".$Pm))."'>".h($A).($xi?doc_link(array('sql'=>"explain-output.html#explain_".strtolower($A),'mariadb'=>"explain/#the-columns-in-explain-select",)):"");}foreach($gd
as$Vf=>$ib){if($e[$ib[0]])unset($gd[$Vf]);}echo"<tbody>\n";}$jf=array();foreach($v
as$ua=>$u){if($u&&!$e[$ua]){$t="";foreach($u
as$_b=>$Vf){if($I[$Vf]===null){$t=null;break;}$t
.="&where[".url_escape(bracket_escape($_b))."]=".url_escape($I[$Vf]);}$jf[$ua]=$t;}}echo"<tr>";foreach($I
as$w=>$W){$z="";if(isset($yg[$w])){if($xi&&JUSH=="sql"){$Q=$I[array_search("table=",$yg)];$z=ME.$yg[$w].url_escape($xi[$Q]!=""?$xi[$Q]:$Q);}elseif(idx($jf,$yg[$w])!==null)$z=ME."edit=".url_escape($S[$yg[$w]]).$jf[$yg[$w]];}$c="";$ib=idx($gd,$w);if($ib&&idx($jf,$ib[0])!==null&&is_utf8($W)){$ed=true;$c=" data-name='".h("val[".bracket_escape($S[$ib[0]])."][".bracket_escape(substr($jf[$ib[0]],1))."][".bracket_escape($ib[1])."]")."' data-text='".($ib[2]?1:0)."'";}$W=select_value($W,$z,array('type'=>(preg_match('~binary~',$Qm[$w])?'blob':$Qm[$w])),null);echo"<td".(preg_match(number_type(),$Qm[$w])?" class='number'":"")."$c>$W";}}$y=$r;echo($r?"</table>\n</div>":"<p class='message'>".'No rows.')."\n";return$H;}function
textarea($A,$X,$J=10,$Db=80,$Xf=JUSH){echo"<textarea name='".h($A)."' rows='$J' cols='$Db' class='sqlarea jush-".h($Xf)."' spellcheck='false' wrap='off'>";if(is_array($X)){foreach($X
as$W)echo
h($W[0])."\n\n\n";}else
echo
h($X);echo"</textarea>";}function
select_input($c,array$B,$X="",$ij=""){if($B&&$X!=""&&!isset($B[$X]))$B=array($X=>$X)+$B;$dm=($B?"select":"input");return"<$dm$c".($B?"><option value=''>$ij".optionlist($B,$X,true)."</select>":" size='10' value='".h($X)."' placeholder='$ij'>");}function
json_row($w,$W=null,$vd=true){static$Zd=true;if($Zd)echo"{";if($w!=""){echo($Zd?"":",")."\n\t\"".addcslashes($w,"\r\n\t\"\\/").'": '.($W!==null?($vd?'"'.addcslashes($W,"\r\n\"\\/").'"':$W):'null');$Zd=false;}else{echo"\n}\n";$Zd=true;}}function
flat_collations(){$Cb=collations();return(is_array(reset($Cb))?call_user_func_array('array_merge',array_values($Cb)):$Cb);}function
edit_type($w,array$l,array$Cb,array$je=array(),array$Kd=array()){$T=(string)$l["type"];echo"<td><select name='".h($w)."[type]' class='type' aria-labelledby='label-type'".on_help_value().">";if($T&&!array_key_exists($T,driver()->types())&&!isset($je[$T])&&!in_array($T,$Kd))$Kd[]=$T;$Bl=driver()->structuredTypes();if($je)$Bl['Foreign keys']=$je;echo
optionlist(array_merge($Kd,$Bl),$T),"</select><td>","<input name='".h($w)."[length]' value='".h($l["length"])."' size='3'".(!$l["length"]&&preg_match('~var(char|binary)$~',$T)?" class='required'":"")." aria-labelledby='label-length'>","<td class='options'>",($Cb?"<input list='collations' name='".h($w)."[collation]'".option_types($T,'('.text_type().')$')." value='".h($l["collation"])."' placeholder='(".'collation'.")'>":''),(driver()->unsigned?"<select name='".h($w)."[unsigned]'".option_types($T,'^$|'.number_type()).'><option>'.optionlist(driver()->unsigned,$l["unsigned"]).'</select>':''),(isset($l['on_update'])?"<select name='".h($w)."[on_update]'".option_types($T,'timestamp|datetime').'>'.optionlist(array(""=>"(".'ON UPDATE'.")","CURRENT_TIMESTAMP"),(preg_match('~^CURRENT_TIMESTAMP~i',$l["on_update"])?"CURRENT_TIMESTAMP":$l["on_update"])).'</select>':''),($je?"<select name='".h($w)."[on_delete]'".option_types($T,'`')."><option value=''>(".'ON DELETE'.")".optionlist(explode("|",driver()->onActions),$l["on_delete"])."</select> ":" ");}function
option_types($T,$Qm){return" data-types='".h($Qm)."'".(preg_match("~$Qm~",$T)?"":" class='hidden'");}function
process_length($x){if(JUSH=="mssql"&&preg_match('~^\s*\(?\s*max\s*\)?\s*$~i',$x))return"(max)";$qd=driver()->enumLength;return(preg_match("~^\\s*\\(?\\s*$qd(?:\\s*,\\s*$qd)*+\\s*\\)?\\s*\$~",$x)&&preg_match_all("~$qd~",$x,$Hg)?"(".implode(",",$Hg[0]).")":preg_replace('~^[0-9].*~','(\0)',preg_replace('~[^-0-9,+()[\]]~','',$x)));}function
process_in($W){$qd=driver()->enumLength;if(preg_match("~^\\s*\\(?\\s*$qd(?:\\s*,\\s*$qd)*+\\s*\\)?\\s*\$~",$W)&&preg_match_all("~$qd~",$W,$Hg))return"(".implode(", ",$Hg[0]).")";$H=array();foreach(explode(",",$W)as$Uf)$H[]=q(trim($Uf));return"(".implode(", ",$H).")";}function
process_type(array$l,$Ab="COLLATE"){return" ".(is_user_type($l["type"])?idf_escape($l["type"]):$l["type"]).process_length($l["length"]).(preg_match(number_type(),$l["type"])&&in_array($l["unsigned"],driver()->unsigned)?" $l[unsigned]":"").(preg_match('~'.text_type().'~',$l["type"])&&$l["collation"]?" $Ab ".(JUSH=="mssql"?$l["collation"]:q($l["collation"])):"");}function
process_field(array$l,array$Mm){if($l["on_update"])$l["on_update"]=str_ireplace("current_timestamp()","CURRENT_TIMESTAMP",$l["on_update"]);return
array(idf_escape(trim($l["field"])),process_type($Mm),($l["null"]?" NULL":" NOT NULL"),default_value($l),(preg_match('~timestamp|datetime~',$l["type"])&&$l["on_update"]?" ON UPDATE $l[on_update]":""),(support("comment")&&$l["comment"]!=""?" COMMENT ".q($l["comment"]):""),($l["auto_increment"]?auto_increment():null),);}function
default_value(array$l){if($l["default"]===null)return"";$j=str_replace("\r","",$l["default"]);$xe=$l["generated"];$P=!preg_match('~]$~',$l["length"])&&(preg_match('~char|binary|text|json|enum|set|String~',$l["type"])||driver()->enumLength($l));return(in_array($xe,driver()->generated)?(JUSH=="mssql"?" AS ($j)".($xe=="VIRTUAL"?"":" $xe"):" GENERATED ALWAYS AS ($j) $xe"):(preg_match('~^GENERATED ~i',$j)?" $j":" DEFAULT ".($P||preg_match('~^(?![a-z])~i',$j)?(JUSH=="sql"&&preg_match('~text|json~',$l["type"])?"(".q($j).")":q($j)):str_ireplace("current_timestamp()","CURRENT_TIMESTAMP",(JUSH=="sqlite"?"($j)":$j)))));}function
edit_fields(array$m,array$Cb,$T="TABLE",array$je=array()){$m=array_values($m);$yc=(($_POST?$_POST["defaults"]:get_setting("defaults"))?"":" class='hidden'");$Ib=(($_POST?$_POST["comments"]:get_setting("comments"))?"":" class='hidden'");echo"<thead><tr>\n",($T=="PROCEDURE"?"<td>":""),"<th id='label-name'>".($T=="TABLE"?'Column name':'Parameter name'),"<th id='label-type'>".'Type'."<textarea id='enum-edit' rows='4' cols='12' wrap='off' hidden></textarea>".script("qs('#enum-edit').onblur = editingLengthBlur;"),"<th id='label-length'>".'Length',"<th>".'Options';if($T=="TABLE")echo"<th id='label-null'>NULL\n","<th><input type='radio' name='auto_increment_col' value=''><abbr id='label-ai' title='".'Auto Increment'."'>AI</abbr>",doc_link(array('sql'=>"example-auto-increment.html",'mariadb'=>"auto_increment/",'sqlite'=>"autoinc.html",'pgsql'=>"datatype-numeric.html#DATATYPE-SERIAL",'cockroach'=>"serial",'mssql'=>"t-sql/statements/create-table-transact-sql-identity-property",)),"<th id='label-default'$yc>".'Default value',(support("comment")?"<th id='label-comment'$Ib>".'Comment':"");$lg=!support("move_col");echo"<td>".icon("plus","add[".($lg?count($m):0)."]","+",'Add next',($lg?on('click','editingAddLastRow'):"")),"<tbody".on('click','editingClick').on('input','editingInput').on('keydown','editingKeydown').">\n";foreach($m
as$r=>$l){$r++;$yi=$l[($_POST?"orig":"field")];$Kc=(isset($_POST["add"][$r-1])||(isset($l["field"])&&!idx($_POST["drop_col"],$r)))&&(support("drop_col")||$yi=="");echo"<tr".($Kc?"":" hidden").">\n",($T=="PROCEDURE"?"<td>".html_select("fields[$r][inout]",explode("|",driver()->inout),$l["inout"]):"")."<th>",(support("move_col")?icon("move","","↕",'Move')." ":"");if($Kc)echo"<input name='fields[$r][field]' value='".h($l["field"])."' data-maxlength='64' autocapitalize='off' aria-labelledby='label-name'".(isset($_POST["add"][$r-1])?" autofocus":"").">";echo
input_hidden("fields[$r][orig]",$yi);edit_type("fields[$r]",$l,$Cb,$je);if($T=="TABLE"){echo"<td><label class='block'>".checkbox("fields[$r][null]",1,$l["null"],"","","","label-null")."</label>","<td><label class='block'><input type='radio' name='auto_increment_col' value='$r'".($l["auto_increment"]?" checked":"")." aria-labelledby='label-ai'></label>","<td$yc>".(driver()->generated?html_select("fields[$r][generated]",array_merge(array("","DEFAULT"),driver()->generated),$l["generated"])." ":checkbox("fields[$r][generated]",1,$l["generated"],"","","","label-default"));$c=" name='fields[$r][default]' aria-labelledby='label-default'";$X=h($l["default"]);echo(preg_match('~\n~',$l["default"])?"<textarea$c rows='2' cols='30' style='vertical-align: bottom;'>\n$X</textarea>":"<input$c value='$X'>");if(support("comment")){$c=" name='fields[$r][comment]' data-maxlength='".(min_version(5.5)?1024:255)."' aria-labelledby='label-comment'";echo"<td$Ib>".adminer()->commentInput('COLUMN',$c,$l["comment"]);}}echo"<td>",(support("move_col")?icon("plus","add[$r]","+",'Add next')." ":""),($yi==""||support("drop_col")?icon("cross","drop_col[$r]","x",'Remove'):"");}}function
process_fields(array&$m){if($_POST["add"]){$m=array_values($m);array_splice($m,key($_POST["add"]),0,array(array()));}return$_POST["add"]||$_POST["drop_col"];}function
drop_create($Xc,$ec,$Zc,$jm,$bd,$Bg,$ch,$ah,$bh,$di,$Eh){if($_POST["drop"])query_redirect($Xc,$Bg,$ch);elseif($di=="")query_redirect($ec,$Bg,$bh);elseif(support("transaction_ddl")){driver()->begin();queries_redirect($Bg,$ah,queries($Xc)&&queries($ec)&&driver()->commit());driver()->rollback();}elseif($di!=$Eh){$gc=queries($ec);queries_redirect($Bg,$ah,$gc&&queries($Xc));if($gc&&$Zc)queries($Zc);}else
queries_redirect($Bg,$ah,queries($jm)&&queries($bd)&&queries($Xc)&&queries($ec));}function
create_trigger($hi,array$I){$pm=" $I[Timing] $I[Event]".(preg_match('~ OF~',$I["Event"])?" $I[Of]":"");return"CREATE TRIGGER ".idf_escape($I["Trigger"]).(JUSH=="mssql"?$hi.$pm:$pm.$hi).preg_replace('~[\s;]+$~',''," $I[Type]\n$I[Statement]").";";}function
q_dollar($P){$Cc='$$';while(strpos($P.$Cc,$Cc)!=strlen($P))$Cc='$_'.substr($Cc,1);return$Cc.$P.$Cc;}function
routine_collate($Bb){static$nb=array();if($Bb&&!$nb){foreach(collations()as$mb=>$vn){foreach((array)$vn
as$W)$nb[$W]=$mb;}}return($nb[$Bb]?"CHARACTER SET ".q($nb[$Bb])." ":"")."COLLATE";}function
create_routine($mk,array$I){$N=array();$m=$I["fields"];ksort($m);foreach($m
as$l){if($l["field"]!=""){$Cf=(preg_match("~^(".driver()->inout.")\$~",$l["inout"])?$l["inout"]:"");$N[]="\n  ".(JUSH=="mssql"?"@$l[field]".process_type($l).($Cf?" $Cf":""):($Cf?"$Cf ":"").idf_escape($l["field"]).process_type($l,routine_collate($l["collation"])));}}$_c="";$B=array();foreach(routine_options($mk)as$w=>$Y){$X=idx($I["options"],$w,"");if($w=="DEFINER")$_c=($X?" $w=".implode("@",array_map('Adminer\q',explode("@",$X,2))):"");elseif(!$Y){if($X!="")$B[]="$w ".q($X);}elseif($X!=reset($Y)&&in_array($X,$Y))$B[]=$X;}$jg=$I["language"];$Ac=preg_replace('~[\s;]+$~','',$I["definition"]);$Tc=(JUSH=="pgsql"||($jg&&$jg!="sql"));$Mi=($N?implode(",",$N)."\n":"");return"CREATE$_c $mk ".table(trim($I["name"])).(JUSH=="mssql"&&$mk=="PROCEDURE"?rtrim($Mi):" ($Mi)").($mk=="FUNCTION"?"\nRETURNS".process_type($I["returns"],routine_collate($I["returns"]["collation"])):"").($jg?" LANGUAGE $jg":"").($B?"\n".implode(" ",$B):"").($Tc?" AS ".q_dollar("\n".trim($Ac)."\n"):(JUSH=="mssql"?"\nAS":"")."\n$Ac;");}function
remove_definer($F){$_c=implode("@",array_map('Adminer\idf_escape',explode("@",logged_user(),2)));return
preg_replace('(^([A-Z =]+) DEFINER='.preg_quote($_c).')','\1',$F);}function
object_name($T,$Q,array$e){return
str_replace(array("{table}","{columns}"),array($Q,implode("_",$e)),adminer()->namePattern($T));}function
format_foreign_key(array$o,$A=""){$i=$o["db"];$Nh=$o["ns"];return($A!=""?" CONSTRAINT ".idf_escape($A):"")." FOREIGN KEY (".implode(", ",array_map('Adminer\idf_escape',$o["source"])).") REFERENCES ".($i!=""&&$i!=$_GET["db"]?idf_escape($i).".":"").($Nh!=""&&$Nh!=$_GET["ns"]?idf_escape($Nh).".":"").idf_escape($o["table"])." (".implode(", ",array_map('Adminer\idf_escape',$o["target"])).")".(preg_match("~^(".driver()->onActions.")\$~",$o["on_delete"])?" ON DELETE $o[on_delete]":"").(preg_match("~^(".driver()->onActions.")\$~",$o["on_update"])?" ON UPDATE $o[on_update]":"").($o["deferrable"]?" $o[deferrable]":"");}function
tar_file($n,$um){$H=pack("a100a8a8a8a12a12",$n,644,0,0,decoct($um->size),decoct(time()));$tb=8*32;for($r=0;$r<strlen($H);$r++)$tb+=ord($H[$r]);$H
.=sprintf("%06o",$tb)."\0 ";echo$H,str_repeat("\0",512-strlen($H));$um->send();echo
str_repeat("\0",511-($um->size+511)%512);}function
doc_version(){$Xk=connection()->server_info;if(JUSH=='oracle'){preg_match('~(?:.* |^)(\d+)\.\d+\.\d+\.\d+\.\d+~s',$Xk,$_);return($_[1]>=18?$_[1]:"19");}$Wj=(JUSH=='sql'||connection()->flavor=='cockroach'?'~^\d+\.\d+~':'~^\d\.?\d~');$yn=(preg_match($Wj,$Xk,$_)?$_[0]:"");if(JUSH=='mssql')return($yn>=15?"sql-server-ver$yn":($yn==12?"azuresqldb-current":"sql-server-2017"));return$yn;}function
doc_link(array$dj,$km="📖"){$yn=doc_version();$in=array('sql'=>"https://dev.mysql.com/doc/refman/$yn/en/",'sqlite'=>"https://www.sqlite.org/",'pgsql'=>"https://www.postgresql.org/docs/".(connection()->flavor=='cockroach'?"current":$yn)."/",'mssql'=>"https://learn.microsoft.com/en-us/sql/",'oracle'=>"https://docs.oracle.com/en/database/oracle/oracle-database/$yn/",);if(connection()->flavor=='maria'){$in['sql']="https://mariadb.com/kb/en/";$dj['sql']=($dj['mariadb']?:str_replace(".html","/",$dj['sql']));}if(connection()->flavor=='cockroach'&&$dj['cockroach']){$in['pgsql']="https://docs.cockroachlabs.com/docs/v$yn/";$dj['pgsql']=$dj['cockroach'];}return($dj[JUSH]?" <a href='".h($in[JUSH].$dj[JUSH].(JUSH=='mssql'?"?view=$yn":""))."'".target_blank()." class='doc' title='".'Documentation'."'>$km</a>":"");}function
db_size($i){if(!connection()->select_db($i))return"?";$H=0;foreach(table_status()as$R)$H+=$R["Data_length"]+$R["Index_length"];return
format_number($H);}function
set_utf8mb4($ec){static$N=false;if(!$N&&preg_match('~\butf8mb4~i',$ec)){$N=true;echo"SET NAMES ".charset(connection()).";\n\n";}}if(DB==""&&isset($_GET["ns"]))redirect(remove_from_uri('ns'));if(!(DB!=""?connection()->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect"||$_GET["script"]=="kill")){if(DB!=""||$_GET["refresh"]){restart_session();set_session("dbs",null);}if(DB!="")page_header('Database'.": ".h(DB),adminer()->error(),true,"","db");else{if(!isset($_GET["db"])&&support("single_db")){$h=adminer()->databases();if($h)redirect(ME."db=".url_escape($h[0]));}if($_POST["db"]&&!$k)queries_redirect(substr(ME,0,-1),'Databases have been dropped.',drop_databases($_POST["db"]));page_header('Select database',$k,false);echo"<p class='links'>\n";foreach(array('database'=>'Create database','privileges'=>'Privileges','processlist'=>'Process list','variables'=>'Variables','status'=>'Status',)as$w=>$W){if(support($w))echo"<a href='".h(ME)."$w='>$W</a>\n";}echo"<p>".sprintf('%s version: %s through PHP extension %s',get_driver(DRIVER),"<b>".h(connection()->server_info)."</b>","<b>".connection()->extension."</b>")."\n","<p>".sprintf('Logged in as: %s',"<b>".h(logged_user())."</b>")."\n";$h=adminer()->databases();if($h){$zk=support("scheme");$Cb=collations();echo"<form action='' method='post'>\n","<table class='checkable odds'".on('click','tableClick').on('dblclick','tableClick').">\n","<thead><tr>".(support("database")?"<td class='hover'>":"")."<th".(JUSH!='mssql'?" aria-sort='ascending'":"").">".'Database'.(get_session("dbs")!==null?" - <a href='".h(ME)."refresh=1'>".'Refresh'."</a>":"")."<th>".'Collation'."<th>".'Tables'."<th>".'Size'." - <a href='".h(ME)."dbsize=1'".on('click','ajaxSetHtml',ME."script=connect").">".'Compute'."</a>"."<tbody>\n";$h=($_GET["dbsize"]?count_tables($h):array_flip($h));foreach($h
as$i=>$S){$lk=h(preg_replace('~&db=[^&]*~','',ME))."db=".url_escape($i);$s=h("Db-".$i);echo"<tr>".(support("database")?"<td class='hover'>".checkbox("db[]",$i,in_array($i,(array)$_POST["db"]),"","","",$s):""),"<th><a href='$lk' id='$s'>".h($i)."</a>";$Bb=h(db_collation($i,$Cb));echo"<td>".(support("database")?"<a href='$lk".($zk?"&amp;ns=":"")."&amp;database=' title='".'Alter database'."'>$Bb</a>":$Bb),"<td align='right'><a href='$lk&amp;schema=' id='tables-".h($i)."' title='".'Database schema'."'>".($_GET["dbsize"]?format_number($S):"?")."</a>","<td align='right' id='size-".h($i)."'>".($_GET["dbsize"]?db_size($i):"?"),"\n";}echo"</table>\n",(support("database")?"<div class='footer'><div>\n"."<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>\n"."<input type='hidden' name='all' value=''".on('click','countDbs').">\n"."<input type='submit' name='drop' value='".'Drop'."'".confirm().">\n"."</div></fieldset>\n"."</div></div>\n":""),input_token(),"</form>\n",script("tableCheck();");}$oa=adminer();$mj=($oa
instanceof
Plugins?$oa->plugins:array());$Wc=($oa
instanceof
Plugins?$oa->drivers:array());$Hc=design_checksums();if($mj||$Wc||$Hc){$ub=($oa
instanceof
Plugins?$oa->checksums():array());$Vh=Plugins::officialChecksums();$dn=function($hn){return" (<a href='$hn'".target_blank()." class='update'>".VERSION."</a>)";};$lj=function($Td)use($ub,$Vh,$dn){return($ub[$Td]&&$Vh[$Td]&&$ub[$Td]!==$Vh[$Td]?$dn("https://www.adminer.org/plugins/?version=".VERSION):"");};echo"<div class='plugins'>\n","<h3>".'Loaded plugins'."</h3>\n<ul>\n";foreach($mj
as$jj){$Uj=new
\ReflectionObject($jj);$Ec=(method_exists($jj,'description')?$jj->description():"");if(!$Ec){if(preg_match('~^/[\s*]+(.+)~',$Uj->getDocComment(),$_))$Ec=$_[1];}$_k=(method_exists($jj,'screenshot')?$jj->screenshot():"");echo"<li><b>".get_class($jj)."</b>".h($Ec?": $Ec":"").($_k?" (<a href='".h($_k)."'".target_blank().">".'screenshot'."</a>)":"").$lj(basename((string)$Uj->getFileName(),'.php'))."\n";}foreach($Wc
as$s=>$A)echo"<li><b>".h($s)."</b>: ".h($A).$lj(basename((string)$oa->driverFiles[$s],'.php'))."\n";if($Hc){$Xh=official_design_checksums();foreach($Hc
as$n=>$Gc){list($A,$tb)=$Gc;$Wh=$Xh["$A/$n"];echo"<li><b>".h($n)."</b>".h($A?": $A":"").($Wh&&$Wh!==$tb?$dn("https://www.adminer.org/?version=".VERSION."#extras"):"")."\n";}}echo"</ul>\n";adminer()->pluginsLinks();echo"</div>\n";}}page_footer("db");exit;}if(support("scheme")){if(DB!=""&&$_GET["ns"]!==""){if(!isset($_GET["ns"]))redirect(preg_replace('~&db=[^&]+~','\0&ns='.url_escape(get_schema()),relative_uri()));if(!set_schema($_GET["ns"]))page_header('Schema'.h(": $_GET[ns]"),adminer()->error(),true,"","ns");}}adminer()->afterConnect();class
TmpFile{private$handler;var$size=0;function
__construct(){$this->handler=tmpfile();}function
write($Wb){$this->size+=strlen($Wb);fwrite($this->handler,$Wb);}function
send(){fseek($this->handler,0);fpassthru($this->handler);fclose($this->handler);}}if($_GET["select"]!=""&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["callf"]))$_GET["call"]=$_GET["callf"];if(isset($_GET["function"]))$_GET["procedure"]=$_GET["function"];if(isset($_GET["download"])){$a=$_GET["download"];$m=fields($a);header("Content-Type: application/octet-stream");$Y=array_merge((array)$_GET["where"],(array)$_GET["val"]);header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$Y)).".".friendly_url($_GET["field"]));$L=array(idf_escape($_GET["field"]));$G=driver()->select($a,$L,array(where($_GET,$m)),$L);$I=($G?$G->fetch_row():array());echo
driver()->value($I[0],$m[$_GET["field"]]);exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$m=fields($a);if(!$m)$k=adminer()->error();$R=table_status1($a);$A=adminer()->tableName($R);$k=$k?:h($R["Error"]);page_header(($m&&is_view($R)?$R['Engine']=='materialized view'?'Materialized view':'View':'Table').": ".($A!=""?$A:h($a)),$k,array(),"",!$m,($m?doc_link(array(JUSH=>driver()->tableHelp($a,is_view($R)))):""));$kk=array();foreach($m
as$w=>$l)$kk+=$l["privileges"];adminer()->selectLinks($R,(isset($kk["insert"])||!support("table")?"":null));$Hb=$R["Comment"];if($Hb!="")echo"<p class='nowrap'>".'Comment'.": ".adminer()->commentValue('TABLE',$Hb)."\n";if($m)adminer()->tableStructurePrint($m,$R);function
tables_links(array$S){echo"<ul>\n";foreach($S
as$I){$z=preg_replace('~ns=[^&]*~',"ns=".url_escape($I["ns"]),ME);echo"<li><a href='".h($z."table=".url_escape($I["table"]))."'>".($I["ns"]!=$_GET["ns"]?"<b>".h($I["ns"])."</b>.":"").h($I["table"])."</a>";}echo"</ul>\n";}$Af=driver()->inheritsFrom($a);if($Af){echo"<h3>".'Inherits from'."</h3>\n";tables_links($Af);}if(support("indexes")&&driver()->supportsIndex($R)){echo"<div>\n","<h3 id='indexes'>".'Indexes'."</h3>\n";$v=indexes($a);if($v)adminer()->tableIndexesPrint($v,$R);if(driver()->supportsAlterIndex($R))echo'<p class="links hover"><a href="'.h(ME).'indexes='.url_escape($a).'">'.'Alter indexes'."</a>\n";echo"</div>\n";}if(!is_view($R)&&driver()->supportsAlterTable($R)){if(fk_support($R)){echo"<div>\n","<h3 id='foreign-keys'>".'Foreign keys'."</h3>\n";$je=foreign_keys($a);if($je){echo"<table>\n","<thead><tr><th>".'Source'."<th>".'Target'."<th>".'ON DELETE'."<th>".'ON UPDATE'."<td class='hover'><tbody>\n";foreach($je
as$A=>$o){echo"<tr title='".h($A)."'>","<th><i>".implode("</i>, <i>",array_map('Adminer\h',$o["source"]))."</i>";$z=($o["db"]!=""?preg_replace('~db=[^&]*~',"db=".url_escape($o["db"]),ME):($o["ns"]!=""?preg_replace('~ns=[^&]*~',"ns=".url_escape($o["ns"]),ME):ME));echo"<td><a href='".h($z."table=".url_escape($o["table"]))."'>".($o["db"]!=""&&$o["db"]!=DB?"<b>".h($o["db"])."</b>.":"").($o["ns"]!=""&&$o["ns"]!=$_GET["ns"]?"<b>".h($o["ns"])."</b>.":"").h($o["table"])."</a>","(<i>".implode("</i>, <i>",array_map('Adminer\h',$o["target"]))."</i>)","<td>".h($o["on_delete"]),"<td>".h($o["on_update"]),'<td class="hover"><a href="'.h(ME.'foreign='.url_escape($a).'&name='.url_escape($A)).'">'.'Alter'.'</a>',"\n";}echo"</table>\n";}echo'<p class="links hover"><a href="'.h(ME).'foreign='.url_escape($a).'">'.'Create foreign key'."</a>\n","</div>\n";}if(support("check")){echo"<div>\n","<h3 id='checks'>".'Checks'."</h3>\n";$pb=driver()->checkConstraints($a);if($pb){echo"<table>\n";foreach($pb
as$w=>$W)echo"<tr title='".h($w)."'>","<td><code class='jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim($W)),80,"</code>"),"<td class='hover'><a href='".h(ME.'check='.url_escape($a).'&name='.url_escape($w))."'>".'Alter'."</a>","\n";echo"</table>\n";}echo'<p class="links hover"><a href="'.h(ME).'check='.url_escape($a).'">'.'Create check'."</a>\n","</div>\n";}}if(support(is_view($R)?"view_trigger":"trigger")&&driver()->supportsAlterTable($R)){echo"<div>\n","<h3 id='triggers'>".'Triggers'."</h3>\n";$Jm=triggers($a);if($Jm){echo"<table>\n";foreach($Jm
as$w=>$W){echo"<tr valign='top'><td>".h($W[0])."<td>".h($W[1])."<th>".h($w)."<td class='hover'><a href='".h(ME.'trigger='.url_escape($a).'&name='.url_escape($w))."'>".'Alter'."</a>";$mk=$W[2];if($mk){$ok=preg_replace('~ns=[^&]*~',"ns=".url_escape($mk["ns"]),ME).'function='.url_escape($mk["function"]).'&name='.url_escape($mk["name"]);echo", <a href='".h($ok)."' title='".h($mk["name"])."'>".'Alter function'."</a>";}echo"\n";}echo"</table>\n";}echo'<p class="links hover"><a href="'.h(ME).'trigger='.url_escape($a).'">'.'Create trigger'."</a>\n","</div>\n";}$cl=driver()->shadowTables($a);if($cl){echo"<h3 id='shadow-tables'>".'Shadow tables'."</h3>\n";tables_links($cl);}$_f=driver()->inheritedTables($a);if($_f){echo"<h3 id='partitions'>".'Inherited by'."</h3>\n";$Ri=driver()->partitionsInfo($a);if($Ri)echo"<p><code class='jush-".JUSH."'>BY ".h("$Ri[partition_by]($Ri[partition])")."</code>\n";tables_links($_f);}}elseif(isset($_GET["schema"])){page_header('Database schema',"",array(),h(DB.($_GET["ns"]?".$_GET[ns]":"")));function
schema_column($Q,array$Tj,array&$e){if(!isset($e[$Q])){$e[$Q]=0;foreach((array)idx($Tj,$Q)as$A=>$Vj){if($A!=$Q)$e[$Q]=max($e[$Q],schema_column($A,$Tj,$e)+1);}}return$e[$Q];}function
type_class($T){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$w=>$W){if(preg_match("~$w|$W~",$T))return" class='$w'";}}$Ul=array();$Wl=array();$Vl=array();$Qd=array();$ca=($_GET["schema"]?:$_COOKIE["adminer_schema-".str_replace(".","_",DB)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$ca,$Hg,PREG_SET_ORDER);foreach($Hg
as$r=>$_){$Ul[$_[1]]=array((float)$_[2],(float)$_[3]);$Wl[]="\n\t'".js_escape($_[1])."': [ $_[2], $_[3] ]";}$K=array();$Tj=array();$je=array();$xa=driver()->allFields();$We=array();$Xl=array();foreach(table_status('',true)as$Q=>$R){if(!is_view($R)){if(adminer()->tableName($R)!=""&&!$R["dependent"])$Xl[$Q]=$R;else$We[$Q]=true;}}foreach($Xl
as$Q=>$R){$E=0;$K[$Q]["fields"]=array();foreach($xa[$Q]as$l){$E+=1.25;$Qd[$Q][$l["field"]]=$E;$K[$Q]["fields"][$l["field"]]=$l;}foreach(adminer()->foreignKeys($Q)as$W){if($W["db"]==""&&$W["ns"]==""&&!$We[$W["table"]]){$je[$Q][]=$W;$Tj[$W["table"]][$Q]=array();}}}$e=array();$Ae=array();$Kn=array();$Ge=array();foreach(array_keys($K)as$A)schema_column($A,$Tj,$e);arsort($e);foreach($e
as$A=>$d){$ih=null;foreach((array)idx($je,$A)as$W){if($W["table"]!=$A&&$K[$W["table"]])$ih=($ih===null?$e[$W["table"]]:min($ih,$e[$W["table"]]));}$e[$A]=max($d,(int)$ih-1);}foreach($K
as$A=>$Q){$d=$e[$A];$Ae[$d][]=$A;$mm=.75*strlen($A);foreach($Q["fields"]as$l)$mm=max($mm,.65*strlen($l["field"]));$Kn[$d]=max(idx($Kn,$d,0),ceil($mm)+1);}foreach($je
as$A=>$vn){foreach($vn
as$W){$Fe=$e[$A]+(idx($e,$W["table"],$e[$A])>$e[$A]?1:0);$Ge[$Fe]=idx($Ge,$Fe,0)+1;}}ksort($Ae);$Ue=0;$Jn=0;$Eb=0;$yj=null;$Ql=array();$Zl=array();foreach($Ae
as$d=>$S){if($yj!==null){$Eb=round($Eb+$Kn[$yj]+1.7+idx($Ge,$d,0)*.1,1);$ti=array();foreach($S
as$A){$Gl=0;$dc=0;$Ah=array_keys((array)idx($Tj,$A));foreach((array)idx($je,$A)as$W)$Ah[]=$W["table"];foreach($Ah
as$uh){if($K[$uh]&&$e[$uh]<$d){$Gl+=$K[$uh]["pos"][0];$dc++;}}$ti[$A]=($dc?$Gl/$dc:$Ue);}asort($ti);$S=array_keys($ti);}$xm=0;foreach($S
as$A){$E=1.25*count($K[$A]["fields"]);$K[$A]["pos"]=($Ul[$A]?:array($xm,$Eb));$Ql[$A]=$K[$A]["pos"][1];$Zl[$A]=$Kn[$d];$xm+=2.5+$E;$Ue=max($Ue,$K[$A]["pos"][0]+2.5+$E);$Jn=max($Jn,round($K[$A]["pos"][1]+$Kn[$d],1));if(!$Ul[$A])$Vl[]="\n\t'".js_escape($A)."': [ ".$K[$A]["pos"][0].", ".$K[$A]["pos"][1]." ]";}$yj=$d;}$pg=array();$Ua=array();foreach($je
as$A=>$vn){foreach($vn
as$W){$fm=idx($Ql,$W["table"],$Ql[$A]);$nl=$Ql[$A]+$Zl[$A];$jk=($fm-1>$nl);$ng=($jk?$nl+1:min($Ql[$A],$fm)-1);$Ta=idx($Ua,(string)$ng,0);$Ua[(string)$ng]=$Ta+1;$ng=round($jk?min($ng+$Ta*.1,$fm-1):$ng-$Ta*.1,1);while($pg[(string)$ng])$ng-=.0001;$K[$A]["references"][$W["table"]][(string)$ng]=array($W["source"],$W["target"]);$Tj[$W["table"]][$A][(string)$ng]=$W["target"];$pg[(string)$ng]=true;}}echo'<div id="schema" style="height: ',$Ue,'em; width: ',$Jn,'em;">
<script',nonce(),'>
const tablePos = {',implode(",",$Wl)."\n",'};
const tablePosDefault = {',implode(",",$Vl)."\n",'};
const em = qs(\'#schema\').offsetHeight / ',$Ue,';
document.onmousemove = schemaMousemove;
document.onmouseup = event => schemaMouseup(event, \'',js_escape(DB),'\');
</script>
';foreach($K
as$A=>$Q){echo"<div class='table'".on('mousedown','schemaMousedown')." style='top: ".$Q["pos"][0]."em; left: ".$Q["pos"][1]."em; width: ".$Zl[$A]."em;'>",'<a href="'.h(ME).'table='.url_escape($A).'"><b>'.h($A)."</b></a>";foreach($Q["fields"]as$l){$W='<span'.type_class($l["type"]).' title="'.h($l["type"].($l["length"]?"($l[length])":"").($l["null"]?" NULL":'')).'">'.h($l["field"]).'</span>';echo"<br>".($l["primary"]?"<i>$W</i>":$W);}foreach((array)$Q["references"]as$gm=>$Vj){foreach($Vj
as$ng=>$Qj){$og=$ng-$Q["pos"][1];$Dl=($og>0?"left: 100%; width: calc($og"."em - 100%)":"left: $og"."em");$Jn=($og>0?"100%":(-$og)."em");$r=0;foreach($Qj[0]as$ml)echo"\n<div class='references' title='".h($gm)."' id='refs$ng-".($r++)."' style='$Dl"."; top: ".$Qd[$A][$ml]."em; padding-top: .5em;'>"."<div style='border-top: 1px solid gray; width: $Jn;'></div></div>";}}foreach((array)$Tj[$A]as$gm=>$Vj){foreach($Vj
as$ng=>$hm){$og=$ng-$Q["pos"][1];$r=0;foreach($hm
as$em)echo"\n<div class='references arrow' title='".h($gm)."' id='refd$ng-".($r++)."' style='left: $og"."em; top: ".$Qd[$A][$em]."em;'>"."<div style='height: .5em; border-bottom: 1px solid gray; width: ".(-$og)."em;'></div>"."</div>";}}echo"\n</div>\n";}foreach($K
as$A=>$Q){foreach((array)$Q["references"]as$gm=>$Vj){if($K[$gm]){foreach($Vj
as$ng=>$Qj){$jh=$Ue;$Pg=-10;foreach($Qj[0]as$w=>$ml){$oj=$Q["pos"][0]+$Qd[$A][$ml];$pj=$K[$gm]["pos"][0]+$Qd[$gm][$Qj[1][$w]];$jh=min($jh,$oj,$pj);$Pg=max($Pg,$oj,$pj);}echo"<div class='references' id='refl$ng' style='left: $ng"."em; top: $jh"."em; padding: .5em 0;'><div style='border-right: 1px solid gray; margin-top: 1px; height: ".($Pg-$jh)."em;'></div></div>\n";}}}}echo'</div>
<p class="links"><a href="',h(ME."schema=".url_escape($ca)),'" id="schema-link">Permanent link</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST&&!$k){$j=array("auto_increment"=>'');foreach(array("type","routine","event","trigger")as$Il){if(support($Il))$j[$Il."s"]='';}save_settings(array_intersect_key($_POST+$j,array_flip(array("output","format","db_style","schema_style","table_style","data_style"))+$j),"adminer_export");$wa=(DB==""||$_GET["ns"]==="");$S=array_flip((array)$_POST["tables"])+array_flip((array)$_POST["data"]);$Gd=dump_headers((count($S)==1?key($S):DB),($wa||count($S)>1));$Rf=preg_match('~sql~',$_POST["format"]);if($Rf){echo"-- Adminer ".VERSION." ".get_driver(DRIVER)." ".str_replace("\n"," ",connection()->server_info)." dump\n\n";if(JUSH=="sql"){echo"SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
".($_POST["data_style"]?"SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
":"")."
";connection()->query("SET time_zone = '+00:00'");connection()->query("SET sql_mode = ''");}}$Dl=$_POST["db_style"];$h=array(DB);if(DB==""){$h=$_POST["databases"];if(is_string($h))$h=explode("\n",rtrim(str_replace("\r","",$h),"\n"));}foreach((array)$h
as$i){adminer()->dumpDatabase($i);if(connection()->select_db($i)){if($Rf&&$Dl)echo
use_sql($i,$Dl).";\n\n";foreach(($_GET["ns"]===""?(array)$_POST["schemas"]:(DB!=""||!support("scheme")?array(""):adminer()->schemas()))as$K){if($K!=""){if(DB==""&&information_schema(DB,$K))continue;set_schema($K);}if($Rf&&$_POST["schema_style"]&&function_exists('Adminer\use_schema_sql'))echo
use_schema_sql($_GET["ns"],$_POST["schema_style"]).";\n\n";$_l=($_POST["table_style"]||$_POST["data_style"]?table_status('',true):array());$Fd=array();$qc=array();foreach($_l
as$A=>$R){if($wa||in_array($A,(array)$_POST["tables"]))$Fd[$A]=$R;if($wa||in_array($A,(array)$_POST["data"]))$qc[$A]=$R;}if($Rf){if($_POST["table_style"]=="DROP+CREATE"&&function_exists('Adminer\drop_sql'))echo
drop_sql($Fd);if($_POST["data_style"]=="TRUNCATE+INSERT"&&function_exists('Adminer\truncate_all_sql')){$Km=array();foreach($qc
as$A=>$R){if(!is_view($R)&&!($_POST["table_style"]=="DROP+CREATE"&&isset($Fd[$A])))$Km[]=$A;}echo
truncate_all_sql($Km);}$Fi="";if($_POST["types"]){foreach(types()as$s=>$T){$Ac=type_definition($s);$Rh=($Ac["kind"]=='d'?"DOMAIN":"TYPE");if($Ac["definition"])$Fi
.=($Dl!='DROP+CREATE'?"DROP $Rh IF EXISTS ".table($T).";;\n":"")."CREATE $Rh ".table($T)." $Ac[definition];\n\n";else$Fi
.="-- Could not export type $T\n\n";}}if($_POST["routines"]){foreach(routines()as$I){$A=$I["ROUTINE_NAME"];$mk=$I["ROUTINE_TYPE"];$ec=create_routine($mk,array("name"=>$A)+routine($I["SPECIFIC_NAME"],$mk));set_utf8mb4($ec);$Fi
.=($Dl!='DROP+CREATE'?"DROP $mk IF EXISTS ".table($A).";;\n":"")."$ec;\n\n";}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$I){$ec=remove_definer(get_val("SHOW CREATE EVENT ".idf_escape($I["Name"]),3));set_utf8mb4($ec);$Fi
.=($Dl!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($I["Name"]).";;\n":"")."$ec;;\n\n";}}echo($Fi&&JUSH=='sql'?"DELIMITER ;;\n\n$Fi"."DELIMITER ;\n\n":$Fi);}if($_POST["table_style"]||$_POST["data_style"]){$_n=array();foreach($_l
as$A=>$R){$Q=array_key_exists($A,$Fd);$oc=array_key_exists($A,$qc);if($Q||$oc){$um=null;if($Gd=="tar"){$um=new
TmpFile;ob_start(array($um,'write'),1e5);}adminer()->dumpTable($A,($Q?$_POST["table_style"]:""),(is_view($R)?2:0));if(is_view($R))$_n[]=$A;elseif($oc){$m=fields($A);$L=array("*");$ac=convert_fields($m,$m);if($ac)$L[]=substr($ac,2);adminer()->dumpData($A,$_POST["data_style"],"",$L);}if($Rf&&$_POST["triggers"]&&$Q&&($Jm=trigger_sql($A)))echo"\nDELIMITER ;;\n$Jm\nDELIMITER ;\n";if($Gd=="tar"){ob_end_flush();tar_file((DB!=""?"":"$i/")."$A.csv",$um);}elseif($Rf)echo"\n";}}if($Rf&&$_POST["table_style"]&&function_exists('Adminer\foreign_keys_sql')){foreach($Fd
as$A=>$R){if(!is_view($R))echo
foreign_keys_sql($A);}}if($Rf){foreach($_n
as$zn)adminer()->dumpTable($zn,$_POST["table_style"],1);}if($Gd=="tar")echo
pack("x1024");}}}}adminer()->dumpFooter();exit;}page_header('Export',$k,($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),h(DB));echo'
<form action="" method="post">
<table class="layout">
';$uc=array('','USE','DROP+CREATE','CREATE');$xk=(JUSH=="mssql"?array('','DROP+CREATE','CREATE'):$uc);$Yl=array('','DROP+CREATE','CREATE');$pc=array('','TRUNCATE+INSERT','INSERT');if(JUSH=="sql")$pc[]='INSERT+UPDATE';$I=get_settings("adminer_export");if(!$I)$I=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"schema_style"=>"","table_style"=>"DROP+CREATE","data_style"=>"INSERT");echo"<tr><th>".'Output'."<td>".html_radios("output",adminer()->dumpOutput(),$I["output"])."\n","<tr><th>".'Format'."<td>".html_radios("format",adminer()->dumpFormat(),$I["format"])."\n",(JUSH=="sqlite"?"":"<tr><th>".'Database'."<td>".html_select('db_style',$uc,$I["db_style"]).(support("type")?checkbox("types",1,$I["types"],'User types'):"").(support("routine")?checkbox("routines",1,$I["routines"],'Routines'):"").(support("event")?checkbox("events",1,$I["events"],'Events'):"")),(function_exists('Adminer\use_schema_sql')?"<tr><th>".'Schema'."<td>".html_select('schema_style',$xk,$I["schema_style"]):""),"<tr><th>".'Tables'."<td>".html_select('table_style',$Yl,$I["table_style"]).checkbox("auto_increment",1,$I["auto_increment"],'Auto Increment').(support("trigger")?checkbox("triggers",1,$I["triggers"],'Triggers'):""),"<tr><th>".'Data'."<td>".html_select('data_style',$pc,$I["data_style"]),'</table>
';adminer()->dumpPrint();echo'<p><input type=\'submit\' value=\'Export\'>
',input_token(),'
<table',on('click','dumpClick'),'>
';$wj=array();if($_GET["ns"]===""&&support("scheme")){echo"<thead><tr><th style='text-align: left;'>","<label class='block'><input type='checkbox' id='check-schemas' checked class='jsonly' title='".'All'."'".on('click','formCheck','^schemas\[').">".'Schema'."</label>","<tbody>\n";foreach(adminer()->schemas()as$K){if(!information_schema(DB,$K))echo"<tr><td>".checkbox("schemas[]",$K,true,$K,"","block")."\n";}}elseif(DB!=""){$rb=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$rb class='jsonly' title='".'All'."'".on('click','formCheck','^tables\[').">".'Table'."</label>","<th style='text-align: right;'><label class='block'>".'Data'."<input type='checkbox' id='check-data'$rb class='jsonly' title='".'All'."'".on('click','formCheck','^data\[')."></label>","<tbody>\n";$_n="";$bm=tables_list();foreach($bm
as$A=>$T){$vj=preg_replace('~_.*~','',$A);$rb=($a==""||$a==(substr($a,-1)=="%"?"$vj%":$A));$Bj="<tr><td>".checkbox("tables[]",$A,$rb,$A,"","block");if($T!==null&&!preg_match('~table~i',$T))$_n
.="$Bj\n";else
echo"$Bj<td align='right'><label class='block'><span id='Rows-".h($A)."'></span>".checkbox("data[]",$A,$rb)."</label>\n";$wj[$vj]++;}echo$_n;if($bm)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}else{$h=adminer()->databases();echo"<thead><tr><th style='text-align: left;'>","<label class='block'>".($h?"<input type='checkbox' id='check-databases'".($a==""?" checked":"")." class='jsonly' title='".'All'."'".on('click','formCheck','^databases\[').">":"").'Database'."</label>","<tbody>\n";if($h){foreach($h
as$i){if(!information_schema($i)){$vj=preg_replace('~_.*~','',$i);echo"<tr><td>".checkbox("databases[]",$i,$a==""||$a=="$vj%",$i,"","block")."\n";$wj[$vj]++;}}}else
echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}echo'</table>
</form>
';$Zd=true;foreach($wj
as$w=>$W){if($w!=""&&$W>1){echo($Zd?"<p>":" ")."<a href='".h(ME)."dump=".url_escape("$w%")."'>".h($w)."</a>";$Zd=false;}}}elseif(isset($_GET["privileges"])){page_header('Privileges');echo'<p class="links"><a href="'.h(ME).'user=">'.'Create user'."</a>";$G=connection()->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$ze=$G;if(!$G)$G=connection()->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");echo"<form action=''><p>\n";hidden_fields_get();echo
input_hidden("db",DB),($ze?"":input_hidden("grant")),"<table class='odds'>\n","<thead><tr><th>".'Username'."<th>".'Server'."<td class='hover'><tbody>\n";while($I=$G->fetch_assoc())echo'<tr><td>'.h($I["User"]),"<td>".h($I["Host"]),'<td class="hover"><a href="'.h(ME.'user='.url_escape($I["User"]).'&host='.url_escape($I["Host"])).'">'.'Edit'."</a>\n";if(!$ze||DB!="")echo"<tr><td><input name='user' autocapitalize='off'>","<td><input name='host' value='localhost' autocapitalize='off'>","<td class='hover'><input type='submit' value='".'Edit'."'>\n";echo"</table>\n","</form>\n";}elseif(isset($_GET["sql"])){if(!$k&&$_POST["export"]){save_settings(array("output"=>$_POST["output"],"format"=>$_POST["format"]),"adminer_import");dump_headers("sql");if($_POST["format"]=="sql")echo"$_POST[query]\n";else{adminer()->dumpTable("","");adminer()->dumpData("","table",$_POST["query"]);adminer()->dumpFooter();}exit;}if(!$k&&$_POST["val"]){$ra=0;$El=true;$jb=array();$uk=0;foreach($_POST["val"]as$J)$uk+=count($J);$Wa=$uk>1&&driver()->begin();foreach($_POST["val"]as$Ol=>$J){$Q=bracket_escape($Ol,true);$m=fields($Q);$Pl=indexes($Q);foreach($J
as$t=>$I){parse_str(bracket_escape($t,true),$Z);$Um=array();foreach($Z["where"]as$w=>$W)$Um[bracket_escape($w,true)]=$W;if(!$m||$Z["null"]||array_diff_key($Um,$m)||!unique_array($Um,$Pl)){$El=false;break
2;}$N=array();$L=array();foreach($I
as$Zf=>$W){$w=bracket_escape($Zf,true);$l=idx($m,$w);if(!$l){$El=false;break
3;}$N[idf_escape($w)]=(preg_match('~char|text~',$l["type"])||$W!=""?adminer()->processInput($l,$W):"NULL");$L[$Zf]=$w;}$Lj=where($Z,$m);if(!driver()->update($Q,$N," WHERE $Lj",0," ")){$El=false;break
2;}$ra+=connection()->affected_rows;$e=array();foreach($L
as$w)$e[]=idf_escape($w);$en=driver()->select($Q,$e,array($Lj),$e);$Gh=($en?$en->fetch_row():array());$Vf=0;foreach($L
as$Zf=>$w){$l=$m[$w];$Cl=array('type'=>(preg_match('~binary~',$l["type"])?'blob':$l["type"]));$jb["val[$Ol][$t][$Zf]"]=select_value(idx($Gh,$Vf++),"",$Cl,null);}}}if($Wa&&$El)$El=driver()->commit();queries_redirect(null,lang_format(array('%d item has been affected.','%d items have been affected.'),$ra),$El);if($Wa&&!$El)driver()->rollback();page_headers();page_messages($k);foreach($jb
as$A=>$W)echo"<div data-name='".h($A)."' hidden>$W</div>\n";exit;}restart_session();$af=&get_session("queries");$Ze=&$af[DB];if(!$k&&$_POST["clear"]){$Ze=array();redirect(remove_from_uri("history"));}stop_session();$pa=get_settings("adminer_import");if($_POST&&$pa)save_settings($pa,"adminer_import");page_header((isset($_GET["import"])?'Import':'SQL command'),$k);$xg=driver()->lineComment();if(!$k&&$_POST&&!(isset($_GET["import"])&&adminer()->importProcess())){$Cc=driver()->delimiter;$ne=false;if(!isset($_GET["import"]))$F=$_POST["query"];elseif($_POST["webfile"]){$rl=adminer()->importServerPath();$ne=@fopen((file_exists($rl)?$rl:"compress.zlib://$rl.gz"),"rb");$F=($ne?fread($ne,1e6):false);}else$F=get_file("sql_file",true,$Cc);if(is_string($F)){if(($Xg=ini_bytes("memory_limit"))!="-1")ini_set("memory_limit",max($Xg,strval(2*strlen($F)+memory_get_usage()+8e6)));if($F!=""&&strlen($F)<1e6){$Ij=$F.(preg_match("~$Cc\\s*\$~",$F)?"":$Cc);if(!$Ze||first(end($Ze))!=$Ij){restart_session();$Ze[]=array($Ij,time());set_session("queries",$af);stop_session();}}$ol="(?:\\s|\xEF\xBB\xBF|/\\*[\s\S]*?\\*/|(?:$xg)[^\n]*\n?|--\r?\n)";$Yh=0;$ld=true;$cc=false;$g=connect();if($g&&DB!=""){$g->select_db(DB);if($_GET["ns"]!="")set_schema($_GET["ns"],$g);}$Gb=0;$td=array();$Oi='[\'"'.(JUSH=="sql"?'`':(JUSH=="sqlite"?'`[':(JUSH=="mssql"?'[':''))).']|/\*|'.$xg.'|$'.(JUSH=="pgsql"?'|\$([a-zA-Z]\w*)?\$':'');$ym=microtime(true);while($F!=""){if(!$Yh&&preg_match("~^$ol*+DELIMITER\\s+(\\S+)~i",$F,$_)){$Cc=preg_quote($_[1]);$F=substr($F,strlen($_[0]));}elseif(!$Yh&&JUSH=='pgsql'&&preg_match("~^($ol*+COPY\\s+)[^;]+\\s+FROM\\s+stdin;~i",$F,$_)){$Cc="\n\\\\\\.\r?\n";$cc=true;$Yh=strlen($_[0]);}else{preg_match("($Cc\\s*|$Oi)",$F,$_,PREG_OFFSET_CAPTURE,$Yh);list($le,$E)=$_[0];if(!$le&&$ne&&!feof($ne))$F
.=fread($ne,1e5);else{if(!$le&&rtrim($F)=="")break;$Yh=$E+strlen($le);if($le&&!preg_match("(^$Cc)",$le)){$gb=driver()->hasCStyleEscapes()||(JUSH=="pgsql"&&($E>0&&strtolower($F[$E-1])=="e"));$ej=($le=='/*'?'\*/':($le=='['?']':(preg_match("~^(?:$xg)~",$le)?"\n":preg_quote($le).($gb?'|\\\\.':''))));while(preg_match("($ej|\$)s",$F,$_,PREG_OFFSET_CAPTURE,$Yh)){$vk=$_[0][0];if(!$vk&&$ne&&!feof($ne))$F
.=fread($ne,1e5);else{$Yh=$_[0][1]+strlen($vk);if(!$vk||$vk[0]!="\\")break;}}}else{$Ij=substr($F,0,$E+($cc?3:0));$F=substr($F,$Yh);$Yh=0;if($cc){$Cc=driver()->delimiter;$cc=false;}$zb="<code class='jush-".JUSH."'>".adminer()->sqlCommandQuery($Ij)."</code>";if(preg_match("~^$ol*+\$~",$Ij)&&!preg_match('~/\*M?!~',$Ij)){echo($_POST["only_errors"]?"":"<pre>$zb</pre>\n");continue;}$ld=false;$Gb++;$Bj="<pre id='sql-$Gb'>$zb</pre>\n";if(JUSH=="sqlite"&&preg_match("~^$ol*+(ATTACH|VACUUM\\b.*\\bINTO)\\b~is",$Ij,$_)!==0){echo$Bj,"<p class='error'>".sprintf('%s queries are not supported.',preg_match('~ATTACH~i',$_[1])?'ATTACH':'VACUUM INTO')."\n";$td[]=" <a href='#sql-$Gb'>$Gb</a>";if($_POST["error_stops"])break;}else{if(!$_POST["only_errors"]){echo$Bj;ob_flush();flush();}$xl=microtime(true);if(connection()->multi_query($Ij)&&$g&&preg_match("~^$ol*+USE\\b~i",$Ij))$g->query($Ij);do{$G=connection()->store_result();if(connection()->error){echo($_POST["only_errors"]?$Bj:""),"<p class='error'>".'Error in query'.(connection()->errno?" (".connection()->errno.")":"").": ".adminer()->error()."\n";$td[]=" <a href='#sql-$Gb'>$Gb</a>";if($_POST["error_stops"])break
2;}else{$z=ME."sql=".url_escape(trim($Ij));$nm=" <span class='time'>(".format_time($xl).")</span>".(strlen($z)<1900?" <a href='".h($z)."'>".'Edit'."</a>":"");$ra=connection()->affected_rows;$Dn=($_POST["only_errors"]?"":driver()->warnings());$En="warnings-$Gb";if($Dn)$nm
.=", <a href='#$En' class='toggle'>".'Warnings'."</a>";$Dd="";$Ed="explain-$Gb";if(is_object($G)){$y=$_POST["limit"];$Ph=$y;$ed=!$_POST["only_errors"];if($ed)echo"<form action='' method='post'>\n";$xi=print_select_result($G,$g,array(),$Ph,$ed);if(!$_POST["only_errors"]){$Ph=max($G->num_rows,$Ph);echo"<p class='sql-footer'>".($Ph?($y&&$Ph>$y?sprintf('%d / ',$y):"").lang_format(array('%d row','%d rows'),$Ph):""),$nm;if($g&&preg_match("~^($ol|\\()*+SELECT\\b~i",$Ij)&&($Dd=adminer()->explain($g,$Ij,$xi))!="")echo", <a href='#$Ed' class='toggle'>Explain</a>";if($ed)echo", <input type='submit' name='save' value='".'Save'."' class='jsonly' disabled"." title='".'Ctrl+click on a value to modify it.'."'".on('click','sqlSave','Saving…').">";$s="export-$Gb";echo", <a href='#$s' class='toggle'>".'Export'."</a><span id='$s' class='hidden'>: ".html_select("output",adminer()->dumpOutput(),$pa["output"])." ".html_select("format",adminer()->dumpFormat(),$pa["format"]).input_hidden("query",$Ij)."<input type='submit' name='export' value='".'Export'."'".($y?"":on('click','sqlExport')).">".input_token()."</span>\n"."</form>\n";}}else{if(preg_match("~^$ol*+(CREATE|DROP|ALTER)$ol++(DATABASE|SCHEMA)\\b~i",$Ij)){restart_session();set_session("dbs",null);stop_session();}if(!$_POST["only_errors"])echo"<p class='message' title='".h(connection()->info)."'>".lang_format(array('Query executed OK, %d row affected.','Query executed OK, %d rows affected.'),$ra)."$nm\n";}echo($Dn?"<div id='$En' class='hidden'>\n$Dn</div>\n":""),($Dd!=""?"<div id='$Ed' class='hidden explain'>\n$Dd</div>\n":"");}$xl=microtime(true);}while(connection()->next_result());}}}}}if($ld)echo"<p class='message'>".'No commands to execute.'."\n";else{$pf=connection()->inTransaction();driver()->rollback();if($pf)echo"<pre><code class='jush-".JUSH."'>ROLLBACK".(JUSH=="mssql"?" TRANSACTION":"")." -- Adminer</code></pre>\n";if($_POST["only_errors"])echo"<p class='message'>".lang_format(array('%d query executed OK.','%d queries executed OK.'),$Gb-count($td))," <span class='time'>(".format_time($ym).")</span>\n";elseif($td&&$Gb>1)echo"<p class='error'>".'Error in query'.": ".implode("",$td)."\n";}}else
echo"<p class='error'>".upload_error($F)."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form"';$fn="";if(!isset($_GET["import"]))echo
on('submit','sqlSubmit',remove_from_uri("sql|limit|error_stops|only_errors|history"));else
echo
on_upload_progress($fn);echo'>
';$Ad="<input type='submit' value='".'Execute'."' title='Ctrl+Enter'>";if(!isset($_GET["import"])){$Ij=$_GET["sql"];if($_POST)$Ij=$_POST["query"];elseif($_GET["history"]=="all")$Ij=$Ze;elseif($_GET["history"]!="")$Ij=idx($Ze[$_GET["history"]],0);echo"<p>";textarea("query",$Ij,20);echo($_POST?"":script("qs('textarea').focus();")),"<p>";adminer()->sqlPrintAfter();echo"$Ad\n",'Limit rows'.": <input type='number' name='limit' class='size' value='".h($_POST?$_POST["limit"]:$_GET["limit"])."'>\n";}else{$He=(extension_loaded("zlib")?"[.gz]":"");echo"<fieldset><legend>".'File upload'."</legend><div>",($fn?input_hidden(ini_get("session.upload_progress.name"),$fn):""),"SQL$He: ".file_input(" name='sql_file[]' multiple","\n$Ad"),($fn?" <progress class='jsonly hidden' max='1' value='0'></progress>":""),"</div></fieldset>\n";$mf=adminer()->importServerPath();if($mf)echo"<fieldset><legend>".'From server'."</legend><div>",sprintf('Webserver file %s',"<code>".h($mf)."$He</code>")," <input type='submit' name='webfile' value='".'Run file'."'>","</div></fieldset>\n";adminer()->importPrint();echo"<p>";}echo
checkbox("error_stops",1,($_POST?$_POST["error_stops"]:isset($_GET["import"])||$_GET["error_stops"]),'Stop on error')."\n",checkbox("only_errors",1,($_POST?$_POST["only_errors"]:isset($_GET["import"])||$_GET["only_errors"]),'Show only errors')."\n",input_token();if(!isset($_GET["import"])&&$Ze){print_fieldset("history",'History',$_GET["history"]!="");for($W=end($Ze);$W;$W=prev($Ze)){$w=key($Ze);list($Ij,$nm,$hd)=$W;echo'<div><a href="'.h(ME."sql=&history=$w").'" class="hover">'.'Edit'."</a>"." <span class='time' title='".@date('Y-m-d',$nm)."'>".@date("H:i:s",$nm)."</span>"." <code class='jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim(preg_replace("~^(?:$xg).*~m",'',$Ij))),80,"</code>").($hd?" <span class='time'>($hd)</span>":"")."</div>\n";}echo"<input type='submit' name='clear' value='".'Clear'."'>\n","<a href='".h(ME."sql=&history=all")."'>".'Edit all'."</a>\n","</div></fieldset>\n";}echo'</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$m=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$m):""):where($_GET,$m));$cn=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($m
as$A=>$l){if((!$cn&&!isset($l["privileges"]["insert"]))||adminer()->fieldName($l)=="")unset($m[$A]);}if($_POST&&!$k&&!isset($_GET["select"])){$Bg=relative_uri((string)$_POST["referer"]);if($_POST["insert"])$Bg=($cn?null:relative_uri());elseif(!preg_match('~^.+&select=.+$~',$Bg))$Bg=ME."select=".url_escape($a);$v=indexes($a);$Vm=unique_array($_GET["where"],$v);$Lj="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($Bg,'Item has been deleted.',driver()->delete($a,$Lj,$Vm?0:1));else{$N=array();foreach($m
as$A=>$l){$W=process_input($l);if($W!==false&&$W!==null)$N[idf_escape($A)]=$W;}if($cn){if(!$N)redirect($Bg);queries_redirect($Bg,'Item has been updated.',driver()->update($a,$N,$Lj,$Vm?0:1));if(is_ajax()){page_headers();page_messages($k);exit;}}else{$G=driver()->insert($a,$N);$mg=($G?last_id($G):0);queries_redirect($Bg,sprintf('Item%s has been inserted.',($mg?" $mg":"")),$G);}}}$I=null;$F="";$nm="";if($Z){$L=array();$Gk=array("*");foreach($m
as$A=>$l){if(isset($l["privileges"]["select"])){$Fa=($_POST["clone"]&&$l["auto_increment"]?"''":convert_field($l));$d=($Fa?"$Fa AS ":"").idf_escape($A);$L[]=$d;if($Fa)$Gk[]=$d;}}$I=array();if(!support("table")){$L=array("*");$Gk=$L;}if($L){$xl=microtime(true);$G=driver()->select($a,$L,array($Z),$L,array(),(isset($_GET["select"])?2:1));$F=str_replace("SELECT ".implode(", ",$L),"SELECT ".implode(", ",$Gk),driver()->query);$nm=format_time($xl);if(!$G)$k=adminer()->error();else{$I=$G->fetch_assoc();if(!$I)$I=false;}if(isset($_GET["select"])&&(!$I||$G->fetch_assoc()))$I=null;}}if(!$m&&driver()->primary!=""){if(!$Z){$G=driver()->select($a,array("*"),array(),array("*"));$I=($G?$G->fetch_assoc():false);if(!$I)$I=array(driver()->primary=>"");}if($I){foreach($I
as$w=>$W){if(!$Z)$I[$w]=null;$m[$w]=array("field"=>$w,"null"=>($w!=driver()->primary),"auto_increment"=>($w==driver()->primary));}}}if($_POST["save"]){$qj=array();foreach((array)$_POST["fields"]as$w=>$W)$qj[bracket_escape($w,true)]=$W;$I=$qj+($I?$I:array());}edit_form($a,$m,$I,$cn,$k,$F,$nm);}elseif(isset($_GET["create"])){function
referencable_primary($Jk){$H=array();foreach(table_status('',true)as$Sl=>$Q){if($Sl!=$Jk&&!$Q["dependent"]&&fk_support($Q)){foreach(fields($Sl)as$l){if($l["primary"]){if($H[$Sl]){unset($H[$Sl]);break;}$H[$Sl]=$l;}}}}return$H;}$a=$_GET["create"];$Ti=driver()->partitionBy;$Xi=($Ti&&$a!=""?driver()->partitionsInfo($a):array());$Sj=referencable_primary($a);$je=array();foreach($Sj
as$Sl=>$l)$je[str_replace("`","``",$Sl)."`".str_replace("`","``",$l["field"])]=$Sl;$_i=array();$R=array();$Mh=false;if($a!=""){$_i=fields($a);$R=table_status1($a);$Mh=(count($R)<2);}$za=($a==""||driver()->supportsAlterTable($R));$I=$_POST;$I["fields"]=(array)$I["fields"];if($I["auto_increment_col"])$I["fields"][$I["auto_increment_col"]]["auto_increment"]=true;if($_POST&&!$k)save_settings(array("comments"=>$_POST["comments"],"defaults"=>$_POST["defaults"]));if($_POST&&!process_fields($I["fields"])&&!$k){if($_POST["drop"])queries_redirect(substr(ME,0,-1),'Table has been dropped.',drop_tables(array($a)));else{$m=array();$xa=array();$jn=false;$he=array();$zi=reset($_i);$ta=" FIRST";foreach($I["fields"]as$l){$o=$je[$l["type"]];$Mm=($o!==null?$Sj[$o]:$l);if($l["field"]!=""){if(!$l["generated"])$l["default"]=null;$Gj=process_field($l,$Mm);$xa[]=array($l["orig"],$Gj,$ta);if(!$zi||$Gj!==process_field($zi,$zi)){$m[]=array($l["orig"],$Gj,$ta);if($l["orig"]!=""||$ta)$jn=true;}if($o!==null)$he[idf_escape($l["field"])]=($a!=""&&JUSH!="sqlite"?"ADD":" ").format_foreign_key(array('table'=>$je[$l["type"]],'source'=>array($l["field"]),'target'=>array($Mm["field"]),'on_delete'=>$l["on_delete"],),object_name("FOREIGN",trim($I["name"]),array($l["field"])));$ta=" AFTER ".idf_escape($l["field"]);}elseif($l["orig"]!=""){$jn=true;$m[]=array($l["orig"]);}if($l["orig"]!=""){$zi=next($_i);if(!$zi)$ta="";}}$Vi=array();if(in_array($I["partition_by"],$Ti)){foreach($I
as$w=>$W){if(preg_match('~^partition~',$w))$Vi[$w]=$W;}foreach($Vi["partition_names"]as$w=>$A){if($A==""){unset($Vi["partition_names"][$w]);unset($Vi["partition_values"][$w]);}}$Vi["partition_names"]=array_values($Vi["partition_names"]);$Vi["partition_values"]=array_values($Vi["partition_values"]);if($Vi==$Xi)$Vi=array();}elseif(preg_match("~partitioned~",$R["Create_options"]))$Vi=null;$Zg='Table has been altered.';if($a==""){cookie("adminer_engine",$I["Engine"]);$Zg='Table has been created.';}$A=trim($I["name"]);$Bg=ME.(support("table")?"table=":"select=").url_escape($A);$G=alter_table($a,$A,(JUSH=="sqlite"&&($jn||$he)?$xa:$m),$he,($I["Comment"]!=$R["Comment"]?$I["Comment"]:null),($I["Engine"]&&$I["Engine"]!=$R["Engine"]?$I["Engine"]:""),($I["Collation"]&&$I["Collation"]!=$R["Collation"]?$I["Collation"]:""),($I["Auto_increment"]!=""?number($I["Auto_increment"]):""),$Vi);if($G&&!Queries::$queries&&$a!=""&&!$m&&!$he)redirect($Bg);queries_redirect($Bg,$Zg,$G);}}$yl=($a!=""?"alter":"create");page_header(($a!=""?'Alter table':'Create table'),$k,array("table"=>$a),h($a),$Mh,doc_link(array('sql'=>"$yl-table.html",'mariadb'=>($a!=""?"$yl-table":""),'pgsql'=>"sql-$yl"."table.html",'cockroach'=>"$yl-table",'mssql'=>"t-sql/statements/$yl-table-transact-sql",'sqlite'=>"lang_{$yl}table.html",'oracle'=>"sqlrf/".strtoupper($yl)."-TABLE.html",)));if(!$_POST){$Qm=driver()->types();$I=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($Qm["int"])?"int":(isset($Qm["integer"])?"integer":"")),"on_update"=>"")),"partition_names"=>array(""),);if($a!=""){$I=$R;$I["name"]=$a;$I["fields"]=array();if(!$_GET["auto_increment"])$I["Auto_increment"]="";foreach($_i
as$l){if($l["generated"])$l["default"]=ltrim($l["default"]);$l["generated"]=$l["generated"]?:(isset($l["default"])?"DEFAULT":"");$I["fields"][]=$l;}if($Ti){$I+=$Xi;$I["partition_names"][]="";$I["partition_values"][]="";}}}$Cb=flat_collations();$od=driver()->engines();foreach($od
as$nd){if(!strcasecmp($nd,$I["Engine"])){$I["Engine"]=$nd;break;}}$Kg=max_input_vars(12,20);if($Kg){$We=(count($I["fields"])>$Kg?"":" hidden");echo"<p".($We?" id='max-fields' data-columns='$Kg'":"")." class='error$We'>".max_input_vars_error()."\n";}echo'
<form action="" method="post" id="form">
<p>
';if(support("columns")||$a==""){echo'Table name'.": <input name='name'".($a==""&&!$_POST?" autofocus":"")." data-maxlength='64' value='".h($I["name"])."' autocapitalize='off'>\n",(!$za?h($R["Engine"])."\n":($od?html_select("Engine",array(""=>"(".'engine'.")")+$od,$I["Engine"],on('change','helpClose').on_help_value())."\n":""));if($Cb)echo"<datalist id='collations'>".optionlist($Cb)."</datalist>\n",(preg_match("~sqlite|mssql~",JUSH)?"":"<input list='collations' name='Collation' value='".h($I["Collation"])."' placeholder='(".'collation'.")'>\n");echo"<input type='submit' value='".'Save'."'>\n";}if(support("columns")&&$za){echo"<div class='scrollable'>\n","<table id='edit-fields' class='nowrap'>\n";edit_fields($I["fields"],$Cb,"TABLE",$je);echo"</table>\n",script("editFields();"),"</div>\n<p>\n",'Auto Increment'.": <input type='number' name='Auto_increment' class='size' value='".h($I["Auto_increment"])."'>\n",checkbox("defaults",1,($_POST?$_POST["defaults"]:get_setting("defaults")),'Default values',on('click','columnShowClick',6),"jsonly");$Jb=($_POST?$_POST["comments"]:get_setting("comments"));if(support("comment")){echo
checkbox("comments",1,$Jb,'Comment',on('click','editingCommentsClick',true),"jsonly").' ';$c=" name='Comment' data-maxlength='".(min_version(5.5)?2048:60)."'".($Jb?"":" class='hidden'");echo
adminer()->commentInput('TABLE',$c,$I["Comment"]);}echo'<p>
<input type=\'submit\' value=\'Save\'>
';}echo'
';if($a!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$a)),'>
';if($Ti&&(JUSH=='sql'||$a=="")){$Ui=preg_match('~RANGE|LIST~',$I["partition_by"]);print_fieldset("partition",'Partition by',$I["partition_by"]);echo"<p>".html_select("partition_by",array_merge(array(""),$Ti),$I["partition_by"],on('change','partitionByChange').on_help_value('.','PARTITION BY $&'))."\n","(<input name='partition' value='".h($I["partition"])."'>)\n",'Partitions'.": <input type='number' name='partitions' class='size".($Ui||!$I["partition_by"]?" hidden":"")."' value='".h($I["partitions"])."'>\n","<table id='partition-table'".($Ui?"":" class='hidden'").">\n","<thead><tr><th>".'Partition name'."<th>".'Values'."<tbody>\n";foreach($I["partition_names"]as$w=>$W)echo'<tr>','<td><input name="partition_names[]" value="'.h($W).'" autocapitalize="off"'.($w==count($I["partition_names"])-1?on('input','partitionNameChange'):'').'>','<td><input name="partition_values[]" value="'.h(idx($I["partition_values"],$w)).'">';echo"</table>\n</div></fieldset>\n";}echo
input_token(),'</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$vf=array("PRIMARY","UNIQUE","INDEX");$R=table_status1($a,true);$sf=driver()->indexAlgorithms($R);if(preg_match('~MyISAM|M?aria'.(min_version(5.6,'10.0.5')?'|InnoDB':'').'~i',$R["Engine"]))$vf[]="FULLTEXT";if(preg_match('~MyISAM|M?aria'.(min_version(5.7,'10.2.2')?'|InnoDB':'').'~i',$R["Engine"]))$vf[]="SPATIAL";if(min_version('',11.7)&&preg_match('~MyISAM|InnoDB~i',$R["Engine"]))$vf[]="VECTOR";$v=indexes($a);$m=fields($a);$_j=array();if(JUSH=="mongo"){$_j=$v["_id_"];unset($vf[0]);unset($v["_id_"]);}$I=$_POST;if($I)save_settings(array("index_options"=>$I["options"]));if($_POST&&!$k&&!$_POST["add"]&&!$_POST["drop_col"]){$b=array();foreach($I["indexes"]as$u){$A=$u["name"];if(in_array($u["type"],$vf)){$e=array();$tg=array();$Fc=array();$mi=array();$tf=(support("partial_indexes")?$u["partial"]:"");$rf=(in_array($u["algorithm"],$sf)?$u["algorithm"]:"");$N=array();ksort($u["columns"]);foreach($u["columns"]as$w=>$d){if($d!=""){$x=idx($u["lengths"],$w);$Dc=idx($u["descs"],$w);$li=idx($u["opclasses"],$w);$N[]=($m[$d]?idf_escape($d):$d).($x?"(".(+$x).")":"").($li!=""?" ".idf_escape($li):"").($Dc?" DESC":"");$e[]=$d;$tg[]=($x?:null);$Fc[]=$Dc;$mi[]="$li";}}$Bd=$v[$A];if($Bd){ksort($Bd["columns"]);ksort($Bd["lengths"]);ksort($Bd["descs"]);if($u["type"]==$Bd["type"]&&array_values($Bd["columns"])===$e&&(!$Bd["lengths"]||array_values($Bd["lengths"])===$tg)&&array_values($Bd["descs"])===$Fc&&(!$Bd["opclasses"]||array_values($Bd["opclasses"])===$mi)&&$Bd["partial"]==$tf&&(!$sf||$Bd["algorithm"]==$rf)){unset($v[$A]);continue;}}if($e)$b[]=array($u["type"],$A,$N,$rf,$tf);}}foreach($v
as$A=>$Bd)$b[]=array($Bd["type"],$A,"DROP");if(!$b)redirect(ME."table=".url_escape($a));queries_redirect(ME."table=".url_escape($a),'Indexes have been altered.',alter_indexes($a,$b));}page_header('Indexes',$k,array("table"=>$a),h($a),false,doc_link(array('sql'=>"create-index.html",'pgsql'=>"sql-createindex.html",'cockroach'=>"create-index",'mssql'=>"t-sql/statements/create-index-transact-sql",'sqlite'=>"lang_createindex.html",'oracle'=>"sqlrf/CREATE-INDEX.html",)));$Sd=array_keys($m);if($_POST["add"]){foreach($I["indexes"]as$w=>$u){if($u["columns"][count($u["columns"])]!="")$I["indexes"][$w]["columns"][]="";}$u=end($I["indexes"]);if($u["type"]||array_filter($u["columns"],'strlen'))$I["indexes"][]=array("columns"=>array(1=>""));}if(!$I){foreach($v
as$w=>$u){$v[$w]["name"]=$w;$v[$w]["columns"][]="";}$v[]=array("columns"=>array(1=>""));$I["indexes"]=$v;}$tg=(JUSH=="sql"||JUSH=="mssql");$mi=driver()->indexOpclasses();$dl=($_POST?$_POST["options"]:get_setting("index_options"));$wh=array();foreach($vf
as$T)$wh[$T]=str_replace("{table}",$a,adminer()->namePattern($T));echo'
<form action="" method="post">
<div class="scrollable">
<table class="nowrap odds">
<thead><tr>
<th id="label-type">Index Type
';$kf=" class='idxopts".($dl?"":" hidden")."'";if($sf)echo"<th id='label-algorithm'$kf>".'Algorithm'.doc_link(array('sql'=>'create-index.html#create-index-storage-engine-index-types','mariadb'=>'storage-engine-index-types/','pgsql'=>'indexes-types.html','cockroach'=>'create-index#parameters',));echo'<th><input type="submit" hidden>','Columns'.($tg?"<span$kf> (".'length'.")</span>":"");if($tg||support("descidx"))echo
checkbox("options",1,$dl,'Options',on('click','indexOptionsShow'),"jsonly")."\n";echo'<th id="label-name">Name
';if(support("partial_indexes"))echo"<th id='label-condition'$kf>".'Condition';echo'<td><noscript>',icon("plus","add[0]","+",'Add next'),'</noscript>
<tbody>
';if($_j){echo"<tr><td>PRIMARY<td>";foreach($_j["columns"]as$w=>$d)echo
select_input(" disabled",array_combine($Sd,$Sd),$d),"<label><input disabled type='checkbox'>".'descending'."</label> ";echo"<td><td>\n";}$Vf=1;foreach($I["indexes"]as$u){if(!$_POST["drop_col"]||$Vf!=key($_POST["drop_col"])){echo"<tr><td>".html_select("indexes[$Vf][type]",array(-1=>"")+$vf,$u["type"],on('change','indexesChangeType',$wh),"label-type");if($sf)echo"<td$kf>".html_select("indexes[$Vf][algorithm]",array_merge(array(""),$sf),$u['algorithm'],"","label-algorithm");echo"<td>";ksort($u["columns"]);$r=1;foreach($u["columns"]as$w=>$d){echo"<span>".select_input(" name='indexes[$Vf][columns][$r]' title='".'Column'."'".on('change','indexesChangeColumn',$wh),($m&&($d==""||$m[$d])?array_combine($Sd,$Sd):array()),$d)," <span$kf>",($tg?"<input type='number' name='indexes[$Vf][lengths][$r]' class='size' value='".h(idx($u["lengths"],$w))."' title='".'Length'."'>":"");if($mi){$li=idx($u["opclasses"],$w);echo
html_select("indexes[$Vf][opclasses][$r]",array(""=>"(".'operator class'.")")+array_combine($mi,$mi)+($li!=""?array($li=>$li):array()),$li),doc_link(array('pgsql'=>'indexes-opclass.html'));}echo(support("descidx")?checkbox("indexes[$Vf][descs][$r]",1,idx($u["descs"],$w),'descending'):""),"<br>","</span></span>";$r++;}echo"<td><input name='indexes[$Vf][name]' value='".h($u["name"])."' autocapitalize='off' aria-labelledby='label-name'>\n";if(support("partial_indexes"))echo"<td$kf><input name='indexes[$Vf][partial]' value='".h($u["partial"])."' autocapitalize='off' aria-labelledby='label-condition'>\n";echo"<td>".icon("cross","drop_col[$Vf]","x",'Remove',on('click','editingRemoveRow','indexes$1[type]'));}$Vf++;}echo'</table>
</div>
<p>
<input type=\'submit\' value=\'Save\'>
',input_token(),'</form>
';}elseif(isset($_GET["database"])){$I=$_POST;if($_POST&&!$k&&!$_POST["add"]){$A=trim($I["name"]);if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),'Database has been dropped.',drop_databases(array(DB)));}elseif($A!==DB){if(DB!=""){$_GET["db"]=$A;queries_redirect(preg_replace('~\bdb=[^&]*&~','',ME)."db=".url_escape($A),'Database has been renamed.',rename_database($A,(string)$I["collation"]));}else{$h=explode("\n",str_replace("\r","",$A));$El=true;$kg="";foreach($h
as$i){if(count($h)==1||$i!=""){if(!create_database($i,(string)$I["collation"]))$El=false;$kg=$i;}}restart_session();set_session("dbs",null);queries_redirect(preg_replace('~&db=[^&]*~','',ME)."db=".url_escape($kg),'Database has been created.',$El);}}else{if(!$I["collation"])redirect(substr(ME,0,-1));query_redirect("ALTER DATABASE ".idf_escape($A).(preg_match('~^[a-z0-9_]+$~i',$I["collation"])?" COLLATE $I[collation]":""),substr(ME,0,-1),'Database has been altered.');}}$yl=(DB!=""?"alter":"create");page_header(DB!=""?'Alter database':'Create database',$k,array(),h(DB),false,doc_link(array('sql'=>"$yl-database.html",'mariadb'=>(DB!=""?"":"$yl-database"),'pgsql'=>"sql-$yl"."database.html",'cockroach'=>"$yl-database",'mssql'=>"t-sql/statements/$yl-database-transact-sql",'oracle'=>"sqlrf/".strtoupper($yl)."-USER.html",)));$Cb=collations();$A=DB;if($_POST)$A=$I["name"];elseif(DB!="")$I["collation"]=db_collation(DB,$Cb);elseif(JUSH=="sql"){foreach(get_vals("SHOW GRANTS")as$ze){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~',$ze,$_)&&$_[1]){$A=stripcslashes(idf_unescape("`$_[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add"]||strpos($A,"\n")?'<textarea autofocus name="name" rows="10" cols="40">'.h($A).'</textarea><br>':'<input name="name" autofocus value="'.h($A).'" data-maxlength="64" autocapitalize="off">')."\n",($Cb?html_select("collation",array(""=>"(".'collation'.")")+$Cb,$I["collation"]).doc_link(array('sql'=>"charset-charsets.html",'mariadb'=>"supported-character-sets-and-collations/",'mssql'=>"relational-databases/system-functions/sys-fn-helpcollations-transact-sql",)):"")."\n",'<input type=\'submit\' value=\'Save\'>
';if(DB!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm(sprintf('Drop %s?',DB)).">\n";elseif(!$_POST["add"]&&$_GET["db"]=="")echo
icon("plus","add[0]","+",'Add next')."\n";echo
input_token(),'</form>
';}elseif(isset($_GET["scheme"])){$I=$_POST;if($_POST&&!$k){$z=preg_replace('~ns=[^&]*&~','',ME)."ns=";if($_POST["drop"])query_redirect("DROP SCHEMA ".idf_escape($_GET["ns"]),$z,'Schema has been dropped.');else{$A=trim($I["name"]);$z
.=url_escape($A);if($_GET["ns"]=="")query_redirect("CREATE SCHEMA ".idf_escape($A),$z,'Schema has been created.');elseif($_GET["ns"]!=$A)query_redirect("ALTER SCHEMA ".idf_escape($_GET["ns"])." RENAME TO ".idf_escape($A),$z,'Schema has been altered.');else
redirect($z);}}$yl=($_GET["ns"]!=""?"alter":"create");page_header($_GET["ns"]!=""?'Alter schema':'Create schema',$k,array(),"",false,doc_link(array('pgsql'=>"sql-$yl"."schema.html",'cockroach'=>"$yl-schema",'mssql'=>"t-sql/statements/create-schema-transact-sql",)));if(!$I)$I["name"]=$_GET["ns"];echo'
<form action="" method="post">
<p><input name="name" autofocus value="',h($I["name"]),'" autocapitalize="off">
<input type=\'submit\' value=\'Save\'>
';if($_GET["ns"]!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm(sprintf('Drop %s?',$_GET["ns"])).">\n";echo
input_token(),'</form>
';}elseif(isset($_GET["call"])){$ba=($_GET["name"]?:$_GET["call"]);$rk=(isset($_GET["callf"])?"FUNCTION":"PROCEDURE");$mk=routine($_GET["call"],$rk);page_header('Call'.": ".h($ba),$k,"#routines","",!$mk,(isset($_GET["callf"])?"":doc_link(array('sql'=>"call.html",'pgsql'=>"sql-call.html",'cockroach'=>"call",'mssql'=>"t-sql/language-elements/execute-transact-sql",))));$nf=array();$Fi=array();foreach($mk["fields"]as$r=>$l){if(substr($l["inout"],-3)=="OUT"&&JUSH=='sql')$Fi[$r]="@".idf_escape($l["field"])." AS ".idf_escape($l["field"]);if(!$l["inout"]||preg_match('~^(IN|OUTPUT)~',$l["inout"]))$nf[]=$r;}if(!$k&&$_POST){$hb=array();foreach($mk["fields"]as$w=>$l){$W="";if(in_array($w,$nf)){$W=process_input($l);if($W===false)$W="''";if(isset($Fi[$w]))connection()->query("SET @".idf_escape($l["field"])." = $W");}if(isset($Fi[$w]))$hb[]="@".idf_escape($l["field"]);elseif(in_array($w,$nf))$hb[]=$W;}$Da=implode(", ",$hb);$F=(isset($_GET["callf"])||JUSH!="mssql"?(isset($_GET["callf"])?"SELECT ":"CALL ").(idx($mk["returns"],"type")=="record"?"* FROM ":"").table($ba)."($Da)":"EXEC ".table($ba).($Da!=""?" $Da":""));$xl=microtime(true);$G=connection()->multi_query($F);$ra=connection()->affected_rows;echo
adminer()->selectQuery($F,$xl,!$G);if(!$G)echo"<p class='error'>".adminer()->error()."\n";else{$g=connect();if($g)$g->select_db(DB);do{$G=connection()->store_result();if(is_object($G))print_select_result($G,$g);else
echo"<p class='message'>".lang_format(array('Routine has been called, %d row affected.','Routine has been called, %d rows affected.'),$ra)." <span class='time'>".@date("H:i:s")."</span>\n";}while(connection()->next_result());if($Fi)print_select_result(connection()->query("SELECT ".implode(", ",$Fi)));}}echo'
<form action="" method="post">
';if($nf){echo"<table class='layout'>\n";foreach($nf
as$w){$l=$mk["fields"][$w];$A=$l["field"];echo"<tr><th>".adminer()->fieldName($l);$X=idx($_POST["fields"],$A);if($X!=""){if($l["type"]=="set")$X=implode(",",$X);}input($l,$X,idx($_POST["function"],$A,""));echo"\n";}echo"</table>\n";}echo'<p>
<input type=\'submit\' value=\'Call\'>
',input_token(),'</form>

',adminer()->commentValue($rk,$mk['comment']);}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];$A=$_GET["name"];$I=$_POST;if($_POST&&!$k&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){if(!$_POST["drop"]){$I["source"]=array_filter($I["source"],'strlen');ksort($I["source"]);$em=array();foreach($I["source"]as$w=>$W)$em[$w]=$I["target"][$w];$I["target"]=$em;}$Sb=object_name("FOREIGN",$a,$I["source"]);if(JUSH=="sqlite")$G=recreate_table($a,$a,array(),array(),array(" $A"=>($I["drop"]?"":" ".format_foreign_key($I,$Sb))));else{$b="ALTER TABLE ".table($a);$G=($A==""||queries("$b DROP ".(JUSH=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($A)));if(!$I["drop"])$G=queries("$b ADD".format_foreign_key($I,$Sb));}queries_redirect(ME."table=".url_escape($a),($I["drop"]?'Foreign key has been dropped.':($A!=""?'Foreign key has been altered.':'Foreign key has been created.')),$G);if(!$I["drop"])$k='Source and target columns must have the same data type, there must be an index on the target columns and the referenced data must exist.';}$Mh=false;if(!$_POST&&$A!=""){$je=foreign_keys($a);$I=idx($je,$A,array());$Mh=!$I;}page_header(($A!=""?'Alter foreign key':'Create foreign key'),$k,array("table"=>$a),h($A!=""?$A:$a),$Mh,doc_link(array('sql'=>"innodb-foreign-key-constraints.html",'mariadb'=>"foreign-keys/",'pgsql'=>"sql-createtable.html#SQL-CREATETABLE-PARMS-REFERENCES",'cockroach'=>"foreign-key",'mssql'=>"t-sql/statements/create-table-transact-sql",'oracle'=>"sqlrf/constraint.html",)));if($_POST){ksort($I["source"]);if($_POST["change"]||$_POST["change-js"])$I["target"]=array();else$I["source"][]="";}elseif($A!="")$I["source"][]="";else{$I["table"]=$a;$I["source"]=array("");}echo'
<form action="" method="post">
';$ml=array_keys(fields($a));if($I["db"]!="")connection()->select_db($I["db"]);if($I["ns"]!=""){$Ai=get_schema();set_schema($I["ns"]);}$Rj=array_keys(array_filter(table_status('',true),function(array$R){return!$R["dependent"]&&fk_support($R);}));$em=array_keys(fields(in_array($I["table"],$Rj)?$I["table"]:reset($Rj)));$c=on('change','foreignChange');echo"<p><label>".'Target table'.": ".html_select("table",$Rj,$I["table"],$c)."</label>\n";if(support("scheme")){$yk=array_filter(adminer()->schemas(),function($K){return!information_schema(DB,$K);});echo"<label>".'Schema'.": ".html_select("ns",$yk,$I["ns"]!=""?$I["ns"]:$_GET["ns"],$c)."</label>";if($I["ns"]!="")set_schema($Ai);}elseif(JUSH!="sqlite"){$vc=array();foreach(adminer()->databases()as$i){if(!information_schema($i))$vc[]=$i;}echo"<label>".'DB'.": ".html_select("db",$vc,$I["db"]!=""?$I["db"]:$_GET["db"],$c)."</label>";}echo
input_hidden("change-js"),'<noscript><p><input type=\'submit\' name=\'change\' value=\'Change\'></noscript>
<table>
<thead><tr><th id="label-source">Source<th id="label-target">Target<tbody>
';$Vf=0;foreach($I["source"]as$w=>$W){echo"<tr>","<td>".html_select("source[".(+$w)."]",array(-1=>"")+$ml,$W,($Vf==count($I["source"])-1?on('change','foreignAddRow'):""),"label-source"),"<td>".html_select("target[".(+$w)."]",$em,idx($I["target"],$w),"","label-target");$Vf++;}echo'</table>
<p>
<label>ON DELETE: ',html_select("on_delete",array(-1=>"")+explode("|",driver()->onActions),$I["on_delete"]),'</label>
<label>ON UPDATE: ',html_select("on_update",array(-1=>"")+explode("|",driver()->onActions),$I["on_update"]),'</label>
',(support("deferrable")?html_select("deferrable",array('NOT DEFERRABLE','DEFERRABLE','DEFERRABLE INITIALLY DEFERRED'),$I["deferrable"]):''),'<p>
<input type=\'submit\' value=\'Save\'>
<noscript><p><input type=\'submit\' name=\'add\' value=\'Add column\'></noscript>
';if($A!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$A)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$I=$_POST;$Bi="VIEW";if(JUSH=="pgsql"&&$a!=""){$O=table_status1($a);$Bi=strtoupper($O["Engine"]);}if($_POST&&!$k){$A=trim($I["name"]);$Fa=" AS\n$I[select]";$Bg=ME."table=".url_escape($A);$Zg='View has been altered.';$T=($_POST["materialized"]?"MATERIALIZED VIEW":"VIEW");if(!$_POST["drop"]&&$a==$A&&JUSH!="sqlite"&&$T=="VIEW"&&$Bi=="VIEW")query_redirect((JUSH=="mssql"?"ALTER":"CREATE OR REPLACE")." VIEW ".table($A).$Fa,$Bg,$Zg);else{$im="adminer_".uniqid();drop_create("DROP $Bi ".table($a),"CREATE $T ".table($A).$Fa,"DROP $T ".table($A),"CREATE $T ".table($im).$Fa,"DROP $T ".table($im),($_POST["drop"]?substr(ME,0,-1):$Bg),'View has been dropped.',$Zg,'View has been created.',$a,$A);}}$Mh=false;if(!$_POST&&$a!=""){$I=view($a);$Mh=!$I["select"];$I["name"]=$a;$I["materialized"]=($Bi!="VIEW");if(!$k)$k=adminer()->error();}page_header(($a!=""?'Alter view':'Create view'),$k,array("table"=>$a),h($a),$Mh,doc_link(array('sql'=>"create-view.html",'pgsql'=>"sql-createview.html",'cockroach'=>"create-view",'mssql'=>"t-sql/statements/create-view-transact-sql",'sqlite'=>"lang_createview.html",'oracle'=>"sqlrf/CREATE-VIEW.html",)));echo'
<form action="" method="post">
<p>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off">
',(support("materializedview")?" ".checkbox("materialized",1,$I["materialized"],'Materialized view'):""),'<p>';textarea("select",$I["select"]);echo'<p>
<input type=\'submit\' value=\'Save\'>
';if($a!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$a)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$If=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$_l=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");$I=$_POST;if($_POST&&!$k){if($_POST["drop"])query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),'Event has been dropped.');elseif(in_array($I["INTERVAL_FIELD"],$If)&&isset($_l[$I["STATUS"]])){$wk="\nON SCHEDULE ".($I["INTERVAL_VALUE"]?"EVERY ".q($I["INTERVAL_VALUE"])." $I[INTERVAL_FIELD]".($I["STARTS"]?" STARTS ".q($I["STARTS"]):"").($I["ENDS"]?" ENDS ".q($I["ENDS"]):""):"AT ".q($I["STARTS"]))." ON COMPLETION".($I["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?'Event has been altered.':'Event has been created.'),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$wk.($aa!=$I["EVENT_NAME"]?"\nRENAME TO ".idf_escape($I["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($I["EVENT_NAME"]).$wk)."\n".$_l[$I["STATUS"]]." COMMENT ".q($I["EVENT_COMMENT"]).rtrim(" DO\n$I[EVENT_DEFINITION]",";").";"));}}$Mh=false;if(!$I&&$aa!=""){$J=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$Mh=!$J;$I=reset($J);}page_header(($aa!=""?'Alter event'.": ".h($aa):'Create event'),$k,"#events","",$Mh,doc_link(array('sql'=>"create-event.html")));echo'
<form action="" method="post">
<table class="layout">
<tr><th>Name<td><input name="EVENT_NAME" value="',h($I["EVENT_NAME"]),'" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">Start<td><input name="STARTS" value="',h("$I[EXECUTE_AT]$I[STARTS]"),'">
<tr><th title="datetime">End<td><input name="ENDS" value="',h($I["ENDS"]),'">
<tr><th>Every
<td><input type="number" name="INTERVAL_VALUE" value="',h($I["INTERVAL_VALUE"]),'" class="size"> ',html_select("INTERVAL_FIELD",$If,$I["INTERVAL_FIELD"]),'<tr><th>Status<td>',html_select("STATUS",$_l,$I["STATUS"]),'<tr><th>Comment<td><input name="EVENT_COMMENT" value="',h($I["EVENT_COMMENT"]),'" data-maxlength="64">
<tr><th><td>',checkbox("ON_COMPLETION","PRESERVE",$I["ON_COMPLETION"]=="PRESERVE",'On completion preserve'),'</table>
<p>';textarea("EVENT_DEFINITION",$I["EVENT_DEFINITION"]);echo'<p>
<input type=\'submit\' value=\'Save\'>
';if($aa!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$aa)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["procedure"])){$ba=($_GET["name"]?:$_GET["procedure"]);$mk=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$I=$_POST;$I["fields"]=(array)$I["fields"];if($_POST&&!process_fields($I["fields"])&&!$k){foreach($I["fields"]as$w=>$l){if($l["field"]=="")unset($I["fields"][$w]);}$ei=routine($_GET["procedure"],$mk);$ci=($ei?routine_id($ba,$ei):"");$Dh=routine_id($I["name"],$I);$ec=create_routine($mk,$I);$Bg=substr(ME,0,-1);$Zg='Routine has been altered.';if(!$_POST["drop"]&&$ci==$Dh&&connection()->flavor!="mysql")queries_redirect($Bg,$Zg,queries(substr_replace($ec,(JUSH=="mssql"?' OR ALTER':' OR REPLACE'),6,0)));else{$im="adminer_".uniqid();drop_create("DROP $mk $ci",$ec,"DROP $mk $Dh",create_routine($mk,array("name"=>$im)+$I),"DROP $mk ".routine_id($im,$I),$Bg,'Routine has been dropped.',$Zg,'Routine has been created.',$ba,$I["name"]);}}$Mh=false;if(!$_POST&&$ba!=""){$I=routine($_GET["procedure"],$mk);$Mh=!$I;$I["name"]=$ba;}$pk=strtolower($mk);page_header(($ba!=""?(isset($_GET["function"])?'Alter function':'Alter procedure').": ".h($ba):(isset($_GET["function"])?'Create function':'Create procedure')),$k,"#routines","",$Mh,doc_link(array('sql'=>"create-procedure.html",'mariadb'=>"create-$pk/",'pgsql'=>"sql-create$pk.html",'cockroach'=>"create-$pk",'mssql'=>"t-sql/statements/create-$pk-transact-sql",)));if(!$_POST&&$ba=="")$I["language"]="sql";$Cb=(JUSH=="sql"?flat_collations():array());$nk=routine_languages();echo($Cb?"<datalist id='collations'>".optionlist($Cb)."</datalist>":""),'
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off">
',($nk?"<label>".'Language'.": ".html_select("language",array_keys($nk),$I["language"],on('change','routineLanguage',$nk))."</label>\n":""),'<input type=\'submit\' value=\'Save\'>
<div class="scrollable">
<table id="edit-fields" class="nowrap">
';edit_fields($I["fields"],$Cb,$mk);if(isset($_GET["function"])){echo"<tr><td>".'Return type';edit_type("returns",(array)$I["returns"],$Cb,array(),(JUSH=="pgsql"?array("void","trigger"):array()));}echo'</table>
',script("editFields();"),'</div>
<p>';textarea("definition",$I["definition"],20,80,($nk[$I["language"]]?:JUSH));echo'<p>
<input type=\'submit\' value=\'Save\'>
';if($ba!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$ba)),'>
';$qk=routine_options($mk);if($qk){$ri=false;foreach($qk
as$w=>$Y){$j=($Y?reset($Y):"");$I["options"][$w]=idx($I["options"],$w,$j);if($I["options"][$w]!=$j)$ri=true;}print_fieldset("options",'Options',$ri);echo"<table class='layout'>\n";foreach($qk
as$w=>$Y){$gg="label-option-$w";$qm=str_replace("_"," ",$w);$L=array();foreach($Y
as$X)$L[$X]=(strpos($X,"$qm ")===0?substr($X,strlen($qm)+1):$X);echo"<tr><th id='$gg'>$qm<td>".($L?html_select("options[$w]",$L,$I["options"][$w],"",$gg):"<input name='options[$w]' value='".h($I["options"][$w])."' aria-labelledby='$gg' autocapitalize='off'>")."\n";}echo"</table>\n</div></fieldset>\n";}echo
input_token(),'</form>
';}elseif(isset($_GET["sequence"])){$da=$_GET["sequence"];$I=$_POST;if($_POST&&!$k){$z=substr(ME,0,-1);$A=trim($I["name"]);if($_POST["drop"])query_redirect("DROP SEQUENCE ".idf_escape($da),$z,'Sequence has been dropped.');elseif($da=="")query_redirect("CREATE SEQUENCE ".idf_escape($A),$z,'Sequence has been created.');elseif($da!=$A)query_redirect("ALTER SEQUENCE ".idf_escape($da)." RENAME TO ".idf_escape($A),$z,'Sequence has been altered.');else
redirect($z);}$Mh=(!$_POST&&$da!=""&&!get_val("SELECT relname FROM pg_class WHERE relkind = 'S' AND relnamespace = ".driver()->nsOid." AND relname = ".q($da)));page_header(($da!=""?'Alter sequence'.": ".h($da):'Create sequence'),$k,"#sequences","",$Mh,doc_link(array('pgsql'=>"sql-createsequence.html",'cockroach'=>"create-sequence",)));if(!$I)$I["name"]=$da;echo'
<form action="" method="post">
<p><input name="name" value="',h($I["name"]),'" autocapitalize="off">
<input type=\'submit\' value=\'Save\'>
';if($da!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm(sprintf('Drop %s?',$da)).">\n";echo
input_token(),'</form>
';}elseif(isset($_GET["type"])){function
enum_values($Ac){$X="'(?:[^']|'')*'";if(!preg_match('~^AS\s+ENUM\s*\(\s*('.$X.'(?:\s*,\s*'.$X.')*)\s*\)$~i',$Ac,$_))return
null;preg_match_all('~'.$X.'~',$_[1],$Hg);return$Hg[0];}function
add_enum_values($T,$ai,$Bh){$gi=enum_values($ai);$Ih=enum_values($Bh);if($gi===null||$Ih===null)return
null;$H=array();$r=0;foreach($Ih
as$X){if($X===idx($gi,$r))$r++;else$H[]="ALTER TYPE ".idf_escape($T)." ADD VALUE $X".($r<count($gi)?" BEFORE ".$gi[$r]:"");}return($r==count($gi)?$H:null);}$ea=$_GET["type"];$I=$_POST;$Nm=($ea!=""?array_search($ea,types(true)):0);$T=($Nm?type_definition(+$Nm):array());$Rh=($T["kind"]=='d'?"DOMAIN":"TYPE");if($_POST&&!$k){$z=substr(ME,0,-1);$A=trim($I["name"]);$Fa=trim(str_replace("\r","",$I["as"]));$Fh=(preg_match('~^AS\s+(?!ENUM\b|RANGE\b|\()~i',$Fa)?"DOMAIN":"TYPE");$Zg='Type has been altered.';$b=(!$_POST["drop"]&&$ea!=""&&$Fh==$Rh?($Fa==$T["definition"]?array():add_enum_values($ea,$T["definition"],$Fa)):null);if($b!==null){if($ea!=$A)$b[]="ALTER $Rh ".idf_escape($ea)." RENAME TO ".idf_escape($A);if(!$b)redirect($z);$Ld=false;foreach($b
as$F){if(!queries($F)){$Ld=true;break;}}queries_redirect($z,$Zg,!$Ld);}else
drop_create("DROP $Rh ".idf_escape($ea),"CREATE $Fh ".idf_escape($A)." $Fa","","","",$z,'Type has been dropped.',$Zg,'Type has been created.',$ea,$A);}page_header(($ea!=""?'Alter type'.": ".h($ea):'Create type'),$k,"#user-types","",($Nm===false),doc_link(array('pgsql'=>"sql-createtype.html",'cockroach'=>"create-type",)));if(!$I){$I["name"]=$ea;$I["as"]=($ea!=""?$T["definition"]:"AS ");}echo'
<form action="" method="post">
<p>
','Name'.": <input name='name' value='".h($I['name'])."' autocapitalize='off'>\n";textarea("as",$I["as"]);echo"<p><input type='submit' value='".'Save'."'>\n";if($ea!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm(sprintf('Drop %s?',$ea)).">\n";echo
input_token(),'</form>
';}elseif(isset($_GET["check"])){$a=$_GET["check"];$A="$_GET[name]";$I=$_POST;if($I&&!$k){$Bg=ME."table=".url_escape($a);$ch='Check has been dropped.';$ah='Check has been altered.';$bh='Check has been created.';if(JUSH=="sqlite")queries_redirect($Bg,($I["drop"]?$ch:($A!=""?$ah:$bh)),recreate_table($a,$a,array(),array(),array(),"",array(),"$A",($I["drop"]?"":$I["clause"])));else{$b="ALTER TABLE ".table($a);$ob=" CHECK ($I[clause])";$im="adminer_".uniqid();drop_create("$b DROP CONSTRAINT ".idf_escape($A),"$b ADD".($I["name"]!=""?" CONSTRAINT ".idf_escape($I["name"]):"").$ob,"$b DROP CONSTRAINT ".idf_escape($I["name"]),"$b ADD CONSTRAINT ".idf_escape($im).$ob,"$b DROP CONSTRAINT ".idf_escape($im),$Bg,$ch,$ah,$bh,$A,$I["name"]);}}$Mh=false;if(!$I){$sb=driver()->checkConstraints($a);$Mh=($A!=""&&!$sb[$A]);$I=array("name"=>($A!=""?$A:object_name("CHECK",$a,array())),"clause"=>$sb[$A]);}page_header(($A!=""?'Alter check':'Create check'),$k,array("table"=>$a),h($A!=""?$A:$a),$Mh,doc_link(array('sql'=>"create-table-check-constraints.html",'mariadb'=>"constraint/",'pgsql'=>"ddl-constraints.html#DDL-CONSTRAINTS-CHECK-CONSTRAINTS",'cockroach'=>"check",'mssql'=>"relational-databases/tables/create-check-constraints",'sqlite'=>"lang_createtable.html#check_constraints",)));echo'
<form action="" method="post">
';if(JUSH!="sqlite")echo'<p>'.'Name'.': <input name="name" value="'.h($I["name"]).'" data-maxlength="64" autocapitalize="off">';echo'<p>';textarea("clause",$I["clause"]);echo'<p><input type=\'submit\' value=\'Save\'>
';if($A!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$A)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$A="$_GET[name]";$Im=trigger_options();$I=trigger($A,$a);$Mh=($A!=""&&!$I);$vh=str_replace("{table}",$a,adminer()->namePattern("TRIGGER"));$I+=array("Trigger"=>strtr($vh,array("{timing}"=>"b","{event}"=>"i","{columns}"=>"","{type}"=>"row")));if($_POST){if(!$k&&in_array($_POST["Timing"],$Im["Timing"])&&in_array($_POST["Event"],$Im["Event"])&&in_array($_POST["Type"],$Im["Type"])){$hi=" ON ".table($a);$Xc="DROP TRIGGER ".idf_escape($A).(JUSH=="pgsql"?$hi:"");$Bg=ME."table=".url_escape($a);if($_POST["drop"])query_redirect($Xc,$Bg,'Trigger has been dropped.');else{if($A!="")queries($Xc);queries_redirect($Bg,($A!=""?'Trigger has been altered.':'Trigger has been created.'),queries(create_trigger($hi,$_POST)));if($A!="")queries(create_trigger($hi,$I+array("Type"=>reset($Im["Type"]))));}}$I=$_POST;}page_header(($A!=""?'Alter trigger':'Create trigger'),$k,array("table"=>$a),h($A!=""?$A:$a),$Mh,doc_link(array('sql'=>"create-trigger.html",'pgsql'=>"sql-createtrigger.html",'cockroach'=>"create-trigger",'mssql'=>"t-sql/statements/create-trigger-transact-sql",'sqlite'=>"lang_createtrigger.html",'oracle'=>"lnpls/CREATE-TRIGGER-statement.html",)));$yh=strtr(preg_quote($vh),array('\{timing\}'=>'[abi]','\{event\}'=>'[iud]*','\{columns\}'=>'.*','\{type\}'=>'(row|statement)'));$Gm=on('change','triggerChange',"^$yh$",$vh);$Uh=on('input','triggerChange',"^$yh$",$vh);echo'
<form action="" method="post" id="form">
<table class="layout">
<tr><th>Time
<td>',html_select("Timing",$Im["Timing"],$I["Timing"],$Gm),'<tr><th>Event<td>',html_select("Event",$Im["Event"],$I["Event"],$Gm),(in_array("UPDATE OF",$Im["Event"])?" <input name='Of' value='".h($I["Of"])."' class='hidden'$Uh>":""),'<tr><th>Type<td>',html_select("Type",$Im["Type"],$I["Type"],$Gm),'<tr><th>Name<td><input name="Trigger" value="',h($I["Trigger"]),'" data-maxlength="64" autocapitalize="off">
</table>
',script("fire(qs('#form')['Timing'], 'change');"),'<p>';textarea("Statement",$I["Statement"]);echo'<p>
<input type=\'submit\' value=\'Save\'>
';if($A!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$A)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["user"])){function
grant($ze,array$Ej,$e,$hi){if(!$Ej)return
true;if($Ej==array("ALL PRIVILEGES","GRANT OPTION"))return($ze=="GRANT"?queries("$ze ALL PRIVILEGES$hi WITH GRANT OPTION"):queries("$ze ALL PRIVILEGES$hi")&&queries("$ze GRANT OPTION$hi"));return
queries("$ze ".preg_replace('~(GRANT OPTION)\([^)]*\)~','\1',implode("$e, ",$Ej).$e).$hi);}$fa=$_GET["user"];$Ej=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$I){foreach(explode(",",($I["Privilege"]=="Grant option"?"":$I["Context"]))as$Xb)$Ej[$Xb=="File access on server"?"Server Admin":$Xb][$I["Privilege"]]=$I["Comment"];}unset($Ej["Server Admin"]["Usage"]);foreach($Ej["Tables"]as$w=>$W)unset($Ej["Databases"][$w]);$Ch=array();if($_POST){foreach($_POST["objects"]as$w=>$W)$Ch[$W]=(array)$Ch[$W]+idx($_POST["grants"],$w,array());}$_e=array();$G=(isset($_GET["host"])?connection()->query("SHOW GRANTS FOR ".q($fa)."@".q($_GET["host"])):null);$Mh=(isset($_GET["host"])&&!$G);if($G){while($I=$G->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$I[0],$_)&&preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~',$_[1],$Hg,PREG_SET_ORDER)){foreach($Hg
as$W){if($W[1]!="USAGE")$_e["$_[2]$W[2]"][$W[1]]=true;if(preg_match('~ WITH GRANT OPTION~',$I[0]))$_e["$_[2]$W[2]"]["GRANT OPTION"]=true;}}}}if($_POST&&!$k){$fi=(isset($_GET["host"])?q($fa)."@".q($_GET["host"]):"''");if($_POST["drop"])query_redirect("DROP USER $fi",ME."privileges=",'User has been dropped.');else{$Hh=q($_POST["user"])."@".q($_POST["host"]);$Zi=$_POST["pass"];$gc=false;$G=true;if($fi!=$Hh){$gc=queries("CREATE USER $Hh IDENTIFIED BY ".($_POST["hashed"]?"PASSWORD ":"").q($Zi));$G=$gc;}elseif($Zi!="")$G=queries("SET PASSWORD FOR $Hh = ".(min_version(8,99)||$_POST["hashed"]?q($Zi):"PASSWORD(".q($Zi).")"));if($G){$ik=array();foreach($Ch
as$Rh=>$ze){if(isset($_GET["grant"]))$ze=array_filter($ze);$ze=array_keys($ze);if(isset($_GET["grant"]))$ik=array_diff(array_keys(array_filter($Ch[$Rh],'strlen')),$ze);elseif($fi==$Hh){$bi=array_keys((array)$_e[$Rh]);$ik=array_diff($bi,$ze);$ze=array_diff($ze,$bi);unset($_e[$Rh]);}if(preg_match('~^(.+)\s*(\(.*\))?$~U',$Rh,$_)&&(!grant("REVOKE",$ik,$_[2]," ON $_[1] FROM $Hh")||!grant("GRANT",$ze,$_[2]," ON $_[1] TO $Hh"))){$G=false;break;}}}if($G&&isset($_GET["host"])){if($fi!=$Hh)queries("DROP USER $fi");elseif(!isset($_GET["grant"])){foreach($_e
as$Rh=>$ik){if(preg_match('~^(.+)(\(.*\))?$~U',$Rh,$_))grant("REVOKE",array_keys($ik),$_[2]," ON $_[1] FROM $Hh");}}}if($G&&!Queries::$queries)redirect(ME."privileges=");queries_redirect(ME."privileges=",(isset($_GET["host"])?'User has been altered.':'User has been created.'),$G);if($gc)connection()->query("DROP USER $Hh");}}page_header((isset($_GET["host"])?'Username'.": ".h("$fa@$_GET[host]"):'Create user'),$k,array("privileges"=>array('','Privileges')),"",$Mh,doc_link(array('sql'=>"grant.html",'mariadb'=>"grant")));$I=$_POST;if($I)$_e=$Ch;else{$I=$_GET+array("host"=>get_val("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$_e[(DB==""||$_e?"":idf_escape(addcslashes(DB,"%_\\"))).".*"]=array();}echo'<form action="" method="post">
<table class="layout">
<tr><th>Server<td><input name="host" data-maxlength="60" value="',h($I["host"]),'" autocapitalize="off">
<tr><th>Username<td><input name="user" data-maxlength="80" value="',h($I["user"]),'" autocapitalize="off">
<tr><th>Password<td><input name="pass" id="pass" value="',h($I["pass"]),'" autocomplete="new-password">
',($I["hashed"]?"":script("typePassword(qs('#pass'));")),(min_version(8,99)?"":checkbox("hashed",1,$I["hashed"],'Hashed',on('click','hashedClick'))),'</table>

',"<table class='odds'>\n","<thead><tr><th colspan='2'>".'Privileges';$r=0;foreach($_e
as$Rh=>$ze){echo'<th>'.($Rh!="*.*"?"<input name='objects[$r]' value='".h($Rh)."' size='10' autocapitalize='off'>":input_hidden("objects[$r]","*.*")."*.*");$r++;}echo"<tbody>\n";foreach(array(""=>"","Server Admin"=>'Server',"Databases"=>'Database',"Tables"=>'Table',"Procedures"=>'Routine',)as$Xb=>$Dc){foreach((array)$Ej[$Xb]as$Dj=>$Hb){echo"<tr><td".($Dc?">$Dc<td":" colspan='2'").' lang="en" title="'.h($Hb).'">'.h($Dj);$r=0;foreach($_e
as$Rh=>$ze){$A="'grants[$r][".h(strtoupper($Dj))."]'";$X=$ze[strtoupper($Dj)];if($Xb=="Server Admin"&&$Rh!=(isset($_e["*.*"])?"*.*":".*"))echo"<td>";elseif(isset($_GET["grant"]))echo"<td><select name=$A><option><option value='1'".($X?" selected":"").">".'Grant'."<option value='0'".($X=="0"?" selected":"").">".'Revoke'."</select>";else
echo"<td align='center'><label class='block'>","<input type='checkbox' name=$A value='1'".($X?" checked":"").($Dj=="All privileges"?" id='grants-$r-all'":($Dj=="Grant option"?"":on('click','grantsClick',"grants-$r-all"))).">","</label>";$r++;}}}echo"</table>\n",'<p>
<input type=\'submit\' value=\'Save\'>
';if(isset($_GET["host"]))echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',"$fa@$_GET[host]")),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")){if($_POST&&!$k){$dg=0;foreach((array)$_POST["kill"]as$W){if(adminer()->killProcess($W))$dg++;}queries_redirect(ME."processlist=",lang_format(array('%d process has been killed.','%d processes have been killed.'),$dg),$dg||!$_POST["kill"]);}}page_header('Process list',$k);echo'
<form action="" method="post">
<div class="scrollable">
<table class="nowrap checkable odds"',on('click','tableClick').on('dblclick','tableClick'),'>
';$r=-1;foreach(adminer()->processList()as$r=>$I){if(!$r){echo"<thead><tr lang='en'>".(support("kill")?"<td class='hover'>":"");foreach($I
as$w=>$W)echo"<th>$w".doc_link(array('sql'=>"show-processlist.html#processlist_".strtolower($w),'pgsql'=>"monitoring-stats.html#PG-STAT-ACTIVITY-VIEW",'oracle'=>"refrn/V-SESSION.html",));echo"<tbody>\n";}echo"<tr>".(support("kill")?"<td class='hover'>".checkbox("kill[]",$I[JUSH=="sql"?"Id":"pid"],0):"");foreach($I
as$w=>$W)echo"<td>".($W!=""&&((JUSH=="sql"&&$w=="Info"&&preg_match("~Query|Killed~",$I["Command"]))||(JUSH=="pgsql"&&$w=="query")||(JUSH=="oracle"&&$w=="sql_text"))?"<code class='jush-".JUSH."' data-full='".h($W)."'>".shorten_utf8($W,100,"</code>").' <a href="'.h(($I["db"]!=""?preg_replace('~&db=[^&]*~','',ME)."db=".url_escape($I["db"])."&":ME)."sql=".url_escape($W)).'">'.'Clone'.'</a>'.' '.copy_icon():h($W));echo"\n";}echo'</table>
</div>
<p>
',script("copyCode(qsl('table'));");if(support("kill"))echo
format_number($r+1)."/".sprintf('%d in total',max_connections()),"<p><input type='submit' value='".'Kill'."'>\n";echo
input_token(),'</form>
',script("tableCheck();");}elseif($_GET["select"]!=""){$a=$_GET["select"];$R=table_status1($a);$v=indexes($a);$m=fields($a);$je=column_foreign_keys($a);$Zh=$R["Oid"];$kk=array();$e=array();$Bk=array();$ui=array();$lm=null;foreach($m
as$w=>$l){$A=adminer()->fieldName($l);$xh=html_entity_decode(strip_tags($A),ENT_QUOTES);if(isset($l["privileges"]["select"])&&$A!=""){$e[$w]=$xh;if(is_shortable($l))$lm=adminer()->selectLengthProcess();}if(isset($l["privileges"]["where"])&&$A!="")$Bk[$w]=$xh;if(isset($l["privileges"]["order"])&&$A!="")$ui[$w]=$xh;$kk+=$l["privileges"];}list($L,$q)=adminer()->selectColumnsProcess($e,$v);$L=array_unique($L);$q=array_unique($q);$Pf=count($q)<count($L);$Z=adminer()->selectSearchProcess($m,$v,$R);$ti=adminer()->selectOrderProcess($m,$v);$y=adminer()->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Wm=>$I){$Fa=convert_field($m[key($I)]);$L=array($Fa?:idf_escape(key($I)));$Z[]=where_check(bracket_escape($Wm,true),$m);$H=driver()->select($a,$L,$Z,$L);if($H)echo
first($H->fetch_row());}exit;}$_j=$Zm=array();foreach($v
as$u){if($u["type"]=="PRIMARY"){$_j=array_flip($u["columns"]);$Zm=($L?$_j:array());foreach($Zm
as$w=>$W){if(in_array(idf_escape($w),$L))unset($Zm[$w]);}break;}}if($Zh&&!$_j){$_j=$Zm=array($Zh=>0);$v[]=array("type"=>"PRIMARY","columns"=>array($Zh));}if($_POST&&!$k){$Gn=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$sb=array();foreach($_POST["check"]as$ob)$sb[]=where_check($ob,$m);$Gn[]="((".implode(") OR (",$sb)."))";}$In=$Gn;$Gn=($Gn?"\nWHERE ".implode(" AND ",$Gn):"");if($_POST["export"]){save_settings(array("output"=>$_POST["output"],"format"=>$_POST["format"]),"adminer_import");dump_headers($a);adminer()->dumpTable($a,"");$Fk=($L?:array("*"));$ac=convert_fields($e,$m,$L);if($ac)$Fk[]=substr($ac,2);$F="";if(is_array($_POST["check"])&&!$_j){$qe=implode(", ",$Fk)."\nFROM ".table($a);$Ce=($q&&$Pf?"\nGROUP BY ".implode(", ",$q):"").($ti?"\nORDER BY ".implode(", ",$ti):"");$Tm=array();foreach($_POST["check"]as$W)$Tm[]="(SELECT".limit($qe,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($W,$m).$Ce,1).")";$F=implode(" UNION ALL ",$Tm);}adminer()->dumpData($a,"table",$F,$Fk,$In,($Pf?$q:array()),$ti);adminer()->dumpFooter();exit;}if(!adminer()->selectEmailProcess($Z,$je)){if($_POST["save"]||$_POST["delete"]){$G=true;$ra=0;$Wa=false;$N=array();if(!$_POST["delete"]){foreach($m
as$A=>$W){$t=bracket_escape($A);if(isset($_POST["fields"][$t])||$_FILES["fields-$t"]){$W=process_input($m[$A]);if($W!==null&&($_POST["clone"]||$W!==false))$N[idf_escape($A)]=($W!==false?$W:idf_escape($A));}}}if($_POST["delete"]||$N){$F=($_POST["clone"]?"INTO ".table($a)." (".implode(", ",array_keys($N)).")\nSELECT ".implode(", ",$N)."\nFROM ".table($a):"");if($_POST["all"]||($_j&&is_array($_POST["check"]))||$Pf){$G=($_POST["delete"]?driver()->delete($a,$Gn):($_POST["clone"]?queries("INSERT $F$Gn".driver()->insertReturning($a)):driver()->update($a,$N,$Gn)));$ra=connection()->affected_rows;if(is_object($G))$ra+=$G->num_rows;}else{$Wa=count((array)$_POST["check"])>1&&driver()->begin();foreach((array)$_POST["check"]as$W){$Fn="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($W,$m);$G=($_POST["delete"]?driver()->delete($a,$Fn,1):($_POST["clone"]?queries("INSERT".limit1($a,$F,$Fn)):driver()->update($a,$N,$Fn,1)));if(!$G)break;$ra+=connection()->affected_rows;}if($Wa&&$G&&!driver()->commit())$G=false;}}$Zg=lang_format(array('%d item has been affected.','%d items have been affected.'),$ra);if($_POST["clone"]&&$G&&$ra==1){$mg=last_id($G);if($mg)$Zg=sprintf('Item%s has been inserted.'," $mg");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page|next":""),$Zg,$G);if($Wa)driver()->rollback();if(!$_POST["delete"]){$qj=(array)$_POST["fields"];edit_form($a,array_intersect_key($m,$qj),$qj,!$_POST["clone"],$k);page_footer();exit;}}elseif(!$_POST["import"]){$G=true;$ra=0;$Wa=count((array)$_POST["val"])>1&&driver()->begin();foreach((array)$_POST["val"]as$Wm=>$I){$N=array();foreach($I
as$w=>$W){$w=bracket_escape($w,true);$N[idf_escape($w)]=(preg_match('~char|text~',$m[$w]["type"])||$W!=""?adminer()->processInput($m[$w],$W):"NULL");}$G=driver()->update($a,$N," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check(bracket_escape($Wm,true),$m),($Pf||$_j?0:1)," ");if(!$G)break;$ra+=connection()->affected_rows;}if($Wa)$G=$G&&driver()->commit();queries_redirect(remove_from_uri(),lang_format(array('%d item has been affected.','%d items have been affected.'),$ra),$G);if($Wa)driver()->rollback();}else{save_settings(array("format"=>$_POST["separator"]),"adminer_import");$Td=get_file("csv_file",true);if(!is_string($Td))$k=upload_error($Td);elseif(!preg_match('~~u',$Td))$k='File must be in UTF-8 encoding.';else{$Db=array_keys($m);$Lk=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$kc=parse_csv($Td,$Lk);$ra=count($kc);driver()->begin();$J=array();foreach($kc
as$w=>$Y){if(!$w&&!array_diff($Y,$Db)){$Db=$Y;$ra--;}else{$N=array();foreach($Y
as$r=>$_b)$N[idf_escape($Db[$r])]=($_b==""&&$m[$Db[$r]]["null"]?"NULL":q(csv_value($_b)));$J[]=$N;}}$G=(!$J||driver()->insertUpdate($a,$J,$_j));if($G)driver()->commit();queries_redirect(remove_from_uri("page|next"),lang_format(array('%d row has been imported.','%d rows have been imported.'),$ra),$G);driver()->rollback();}}}}$Sl=adminer()->tableName($R);if(is_ajax()){page_headers();ob_start();}else
page_header('Select'.": $Sl",$k,array(),"",(!$m&&support("table")),($m?doc_link(array(JUSH=>driver()->tableHelp($a,is_view($R)))):""));$N=null;if(isset($kk["insert"])||!support("table")){$N="";foreach((array)$_GET["where"]as$W){$X=$W["val"];if(is_array($X))$X=(count($X)==1&&preg_match('~^val-(.*)~s',reset($X),$_)?$_[1]:"");if($W["col"]!=""&&$X!=""&&($W["op"]=="="||(!$W["op"]&&(is_array($W["val"])||!preg_match('~[_%]~',$X)))))$N
.="&set[".url_escape(bracket_escape($W["col"]))."]=".url_escape($X);}}adminer()->selectLinks($R,$N);if(!$e&&support("table"))echo"<p class='error'>".'Unable to select the table.'."\n";else{echo"<form action='' id='form'>\n","<div hidden>";hidden_fields_get();echo(DB!=""?input_hidden("db",DB).(isset($_GET["ns"])?input_hidden("ns",$_GET["ns"]):""):""),input_hidden("select",$a),"</div>\n";adminer()->selectColumnsPrint($L,$e);adminer()->selectSearchPrint($Z,$Bk,$v,$R);adminer()->selectOrderPrint($ti,$ui,$v);adminer()->selectLimitPrint($y);if($lm!==null)adminer()->selectLengthPrint($lm);adminer()->selectActionPrint($v);echo"</form>\n";foreach((array)$_GET["where"]as$W){if($W["op"]=="SQL"&&!in_array($_SERVER["HTTP_SEC_FETCH_SITE"],array("","same-origin"))){echo"<p class='error'>".'Invalid CSRF token. Submit the form again.'.' '.'If you did not send this request from Adminer, close this page.'."\n";page_footer();exit;}}$C=$_GET["page"];$me=null;if($C=="last"){$me=get_val(count_rows($a,$Z,$Pf,$q));$C=floor(max(0,intval($me)-1)/$y);}$Ek=$L;$Be=$q;if(!$Ek){$Ek[]="*";$ac=convert_fields($e,$m,$L);if($ac)$Ek[]=substr($ac,2);}foreach($L
as$w=>$W){$l=$m[idf_unescape($W)];if($l&&($Fa=convert_field($l)))$Ek[$w]="$Fa AS $W";}if(JUSH=="pgsql"||JUSH=="mssql"){foreach((array)$_GET["columns"]as$w=>$W){if(isset($Ek[$w])&&$W["fun"])$Ek[$w].=" AS ".idf_escape(apply_sql_function($W["fun"],($W["col"]!=""?$W["col"]:"*")));}}if(!$Pf&&$Zm){foreach($Zm
as$w=>$W){$Ek[]=idf_escape($w);if($Be)$Be[]=idf_escape($w);}}$G=driver()->select($a,$Ek,$Z,$Be,$ti,$y,$C,true);if(!is_object($G))echo"<p class='error'>".(adminer()->error()?:'Unknown error.')."\n";else{if(JUSH=="mssql"&&$C)$G->seek($y*$C);$kd=array();$J=array();while($I=$G->fetch_assoc()){if($C&&JUSH=="oracle")unset($I["RNUM"]);$J[]=$I;}$Ne=($y&&(support("cursor")?$_GET["next"]!="":count($J)>=$y));if(is_ajax()&&$Ne)header("X-Next-Page: ".pagination_href($C+1));if($_GET["modify"]&&$J){$Qg=max_input_vars(count($J[0])+1,20);echo($Qg&&count($J)>$Qg?"<p class='error'>".max_input_vars_error()."\n":"");}echo"<form action='' method='post' enctype='multipart/form-data'".on_upload_progress($fn).">\n";if($_GET["page"]!="last"&&$y&&$q&&$Pf&&JUSH=="sql")$me=get_val(" SELECT FOUND_ROWS()");if(!$J)echo"<p class='message'>".'No rows.'."\n";else{$Sa=adminer()->backwardKeys($a,$Sl);$gk=array();reset($L);foreach($J[0]as$w=>$W){if(!isset($Zm[$w])){$W=idx($_GET["columns"],key($L))?:array();$gk[$w]=array("fun"=>$W["fun"],"col"=>($L?$W["col"]:$w));next($L);}}echo"<div class='scrollable'>","<table id='table' class='nowrap checkable odds'".on('click','tableClick').on('dblclick','tableClick').on('keydown','editingKeydown').">\n","<thead><tr>".(!$q&&$L?"":"<td class='hover check sticky'><input type='checkbox' id='all-page' class='jsonly' title='".'All rows on this page'."'".on('click','formCheck','^check').">");$zh=array();$Oj=1;foreach($gk
as$w=>$W){$l=$m[$W["col"]];$A=($l?adminer()->fieldName($l,$Oj):($W["fun"]?"*":h($w)));if($A!=""){$Oj++;$zh[$w]=$A;$d=idf_escape($w);$df=remove_from_uri('(order|desc)[^=]*|page|next').'&order[0]='.url_escape($w);$Dc="&desc[0]=1";$jl=preg_replace('~ DESC( NULLS LAST)?$~','',$ti[0]);$ll=($jl==$d||$jl==$w);echo"<th id='th[".h(bracket_escape($w))."]'".($ll?" aria-sort='".($jl==$ti[0]?"ascending":"descending")."'":"").">";$ve=apply_sql_function(h($W["fun"]),$A);$kl=isset($l["privileges"]["order"])||$W["fun"];echo($kl?"<a href='".h($df.($ll&&$jl==$ti[0]?$Dc:''))."'>$ve</a>":$ve);$Yg=($kl?"<a href='".h($df.$Dc)."' title='".'descending'."' class='text'> ↓</a>":'');if(!$W["fun"]&&isset($l["privileges"]["where"]))$Yg
.="<a href='#fieldset-search' title='".'Search'."' class='text jsonly'".on('click','selectSearch',$w)."> =</a>";echo($Yg?"<span class='column'>$Yg</span>":"");}}$tg=array();if($_GET["modify"]){foreach($J
as$I){foreach($I
as$w=>$W)$tg[$w]=max($tg[$w],min(40,utf8_length((string)$W)));}}$Ye=array();$Xe=array();foreach((array)$_GET["where"]as$W){$W+=array("col"=>"","op"=>"","val"=>"");$_b=$W["col"];$Ak=$W["val"];if(!is_array($Ak)&&($Ak!=""||preg_match('~NULL$~',$W["op"]))&&(!$W["op"]||in_array($W["op"],adminer()->operators($R)))){$vg=strtr(preg_quote($Ak),array("%"=>".*?","_"=>"."));$fj=array("LIKE %%"=>$vg,"ILIKE %%"=>$vg,"REGEXP"=>$Ak)+(JUSH=="pgsql"?array("~"=>$Ak,"~*"=>$Ak):array())+($_b!=""?array():array("="=>'^'.preg_quote($Ak).'\z',"IN"=>'^(?:'.implode("|",array_map('preg_quote',array_map('trim',explode(",",$Ak)))).')\z',"LIKE"=>"^$vg\\z","ILIKE"=>"^$vg\\z","FIND_IN_SET"=>'(?<=^|,)'.preg_quote($Ak).'(?=,|\z)',));foreach(($_b!=""?array($_b=>$m[$_b]):$m)as$A=>$l){if($_b!=""||is_searchable($l,$W)){$ki=$W["op"]?:(!preg_match('~'.text_type().'~',$l["type"])?"IN":(preg_match('~%~',$Ak)?"LIKE":"LIKE %%"));if(isset($fj[$ki])){$vb=preg_match('~^ILIKE|\*$~',$ki)||($ki!="~"&&preg_match('~^(sql|mssql|sqlite)$~',JUSH));$Ye[$A][]="(?".($vb?"i":"").":$fj[$ki])";}elseif($ki=="IS NULL"&&$_b=="")$Xe[$A]=true;}}}}echo($Sa?"<th>".'Relations':"")."<tbody>\n";if(is_ajax())ob_end_clean();foreach(adminer()->rowDescriptions($J,$je)as$th=>$I){$Vm=unique_array($J[$th],$v);if(!$Vm){$Vm=array();foreach($J[$th]as$w=>$W){if(!in_array(idx(idx($gk,$w,array()),"fun"),driver()->grouping))$Vm[$w]=$W;}}$Wm="";$r=0;foreach($Vm
as$w=>$W){$fk=idx($gk,$w,array());$ve=idx($fk,"fun","");$_b=($ve?$fk["col"]:$w);$l=(array)$m[$_b];$Of=is_blob($l);if(!$ve&&strlen($W)>64&&driver()->md5(idf_escape($_b),$l)){$ve="md5";$W=md5($Of?(string)driver()->value($W,$l):$W);}if($ve){$Wm
.="&fun[$r]=".url_escape($ve)."&col[$r]=".url_escape($_b).($W!==null?"&val[$r]=".url_escape($W===false?"f":$W):"");$r++;}else$Wm
.="&".($W!==null?"where[".url_escape(bracket_escape($_b))."]=".url_escape($W===false?"f":$W):"null[]=".url_escape($_b));}echo"<tr>".(!$q&&$L?"":"<td class='hover check sticky'>".($Pf||information_schema(DB)?"":"<a href='".h(ME."edit=".url_escape($a).$Wm)."' class='edit'>".'edit'."</a> ").checkbox("check[]",substr($Wm,1),in_array(substr($Wm,1),(array)$_POST["check"])));foreach($I
as$w=>$W){if(isset($zh[$w])){$ve=$gk[$w]["fun"];$_b=$gk[$w]["col"];$l=(array)$m[$w];if($W!=""&&(!isset($kd[$w])||$kd[$w]!=""))$kd[$w]=(is_mail($W)?$zh[$w]:"");$z="";if(is_blob($l)&&$W!="")$z=ME.'download='.url_escape($a).'&field='.url_escape($w).$Wm;if(!$z&&$W!==null){foreach((array)$je[$w]as$o){if(count($je[$w])==1||end($o["source"])==$w){$z="";foreach($o["source"]as$r=>$ml)$z
.=where_link($r,$o["target"][$r],$J[$th][$ml]);$z=($o["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\1'.url_escape($o["db"]),ME):ME).'select='.url_escape($o["table"]).$z;if($o["ns"])$z=preg_replace('~([?&]ns=)[^&]+~','\1'.url_escape($o["ns"]),$z);if(count($o["source"])==1)break;}}}if($ve=="count"&&$_b==""){$z=ME."select=".url_escape($a);$r=0;foreach((array)$_GET["where"]as$V){if(!array_key_exists($V["col"],$Vm))$z
.=where_link($r++,$V["col"],$V["val"],$V["op"]);}foreach($Vm
as$Yf=>$V){if(idx(idx($gk,$Yf,array()),"fun")){$z="";break;}$z
.=where_link($r++,$Yf,$V);}}$ef=select_value($W,$z,$l,$lm,($ve?array():idx($Ye,$w,array())));if($W===null&&!$ve&&isset($Xe[$w]))$ef="<mark>$ef</mark>";$t=bracket_escape($Wm);$s=h("val[$t][".bracket_escape($w)."]");$sj=idx(idx($_POST["val"],$t),bracket_escape($w));$cn=idx($l["privileges"],"update")&&!is_identity_always($l);$gd=!is_array($I[$w])&&!is_blob($l)&&is_utf8($W)&&$J[$th][$w]==$W&&!$ve&&!$l["generated"]&&$cn;$T=($ve=="min"||$ve=="max"?$m[$_b]["type"]:$l["type"]);$km=preg_match('~text|json|lob~',$T);$Qf=preg_match(number_type(),$T)||preg_match('~^(avg|ceil|char_length|count|count distinct|floor|len|length|round|sum|time_to_sec)$~',$ve);echo"<td id='$s'".($Qf&&($W===null||is_numeric(strip_tags($ef))||$T=="money")?" class='number'":"");if(($_GET["modify"]&&$gd&&$W!==null)||$sj!==null){$Ie=h($sj!==null?$sj:$W);echo">".($km?"<textarea name='$s' cols='30' rows='".(substr_count($W,"\n")+1)."'>$Ie</textarea>":"<input name='$s' value='$Ie' size='$tg[$w]'>");}else{$Dg=strpos($ef,"<i>…</i>");echo($cn?" data-text='".($Dg?2:($km?1:0))."'".($gd?"":" data-warning='".'Use the edit link to modify this value.'."'"):"").">$ef";}}}if($Sa)echo"<td>";adminer()->backwardKeysPrint($Sa,$J[$th]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){$qa=get_settings("adminer_import");if($J||$C||$Ne){$_d=true;if($_GET["page"]!="last"){if(!$y||(count($J)<$y&&($J||!$C)))$me=($C?$C*$y:0)+count($J);elseif(JUSH!="sql"||!$Pf){$me=($Pf?null:found_rows($R,$Z));$_d=!driver()->hasEstimatedRows();if($me===null||(!$_d&&$me<max(1e4,2*($C+1)*$y))){$me=first(slow_query(count_rows($a,$Z,$Pf,$q)));$_d=true;}}}if(!support("cursor"))$Ne=(($me===false?count($J)+1:$me-$C*$y)>$y);$Ki=($y&&($Ne||$C));if($Ki)echo($Ne?'<p><a href="'.h(pagination_href($C+1)).'" class="loadmore"'.on('click','selectLoadMore','Loading…').'>'.'Load more data'.'</a>':''),"\n";echo"<div class='footer'><div>\n";if($Ki){$Og=($me===false?$C+($J?(count($J)>=$y?2:1):0):floor(($me-1)/$y));echo"<fieldset><legend>".'Page'."</legend>";if(!support("cursor")){echo
pagination(0,$C).($C>5?" …":"");for($r=max(1,$C-4);$r<min($Og,$C+5);$r++)echo
pagination($r,$C);if($Og>0)echo($C+5<$Og?" …":""),($_d&&$me!==false?pagination($Og,$C):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Og'>".'last'."</a>");}else
echo
pagination(0,$C).($C>1?" …":""),($C?pagination($C,$C):""),($Ne?pagination($C+1,$C)." …":"");echo"</fieldset>\n";}echo"<fieldset>","<legend>".'Whole result'."</legend>";$Lc=($_d?"":"~ ").$me;$gg=($me!==false?($_d?"":"~ ").lang_format(array('%d row','%d rows'),$me):"");echo
checkbox("all",1,0,$gg,on('click','countRows',$Lc))."\n","</fieldset>\n";if(adminer()->selectCommandPrint())echo'<fieldset',($_GET["modify"]?'':" title='".'Ctrl+click on a value to modify it.'."'"),'>
<legend><a href=\'',h($_GET["modify"]?remove_from_uri("modify"):relative_uri()."&modify=1"),'\'>Modify</a></legend><div>
<input type=\'submit\' id=\'save\' value=\'Save\'',($_GET["modify"]||$_POST["val"]?'':" class='jsonly' disabled"),'>
</div></fieldset>

<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type=\'submit\' name=\'edit\' value=\'Edit\'>
<input type=\'submit\' name=\'clone\' value=\'Clone\'>
<input type=\'submit\' name=\'delete\' value=\'Delete\'',confirm(),'>
</div></fieldset>
';$ke=adminer()->dumpFormat();foreach((array)$_GET["columns"]as$d){if($d["fun"]){unset($ke['sql']);break;}}if($ke){print_fieldset("export",'Export'." <span id='selected2'></span>");$Gi=adminer()->dumpOutput();echo($Gi?html_select("output",$Gi,$qa["output"])." ":""),html_select("format",$ke,$qa["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}adminer()->selectEmailPrint(array_filter($kd,'strlen'),$e);echo"</div></div>\n";}if(adminer()->selectImportPrint())echo"<p>","<a href='#import' class='toggle'>".'Import'."</a>","<span id='import'".($_POST["import"]?"":" class='hidden'").">: ",($fn?input_hidden(ini_get("session.upload_progress.name"),$fn):""),file_input(" name='csv_file'"," ".html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$qa["format"])." <input type='submit' name='import' value='".'Import'."'>".($fn?" <progress class='jsonly hidden' max='1' value='0'></progress>":"")),"</span>";echo
input_token(),"</form>\n",(!$q&&$L?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["variables"])){$O=isset($_GET["status"]);page_header($O?'Status':'Variables');$wn=($O?adminer()->showStatus():adminer()->showVariables());if(!$wn)echo"<p class='message'>".'No rows.'."\n";else{echo"<table>\n";foreach($wn
as$I){echo"<tr>";$w=array_shift($I);echo"<th><code class='jush-".JUSH.($O?"status":"set")."'>".h($w)."</code>";foreach($I
as$W)echo"<td>".nl_br(h($W));}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: application/json; charset=utf-8");if($_GET["script"]=="db"){$Hl=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$A=>$R){json_row("Comment-$A",h($R["Comment"]).($R["Error"]?" <span class='error'>".h($R["Error"])."</span>":""));if(!is_view($R)||preg_match('~materialized~i',$R["Engine"])){foreach(array("Engine","Collation")as$w)json_row("$w-$A",h($R[$w]));foreach(array_keys($Hl+array("Auto_increment"=>0,"Rows"=>0))as$w){if(array_key_exists($w,$R))json_row("$w-$A",format_status($R,$w));if($R[$w]!=""&&isset($Hl[$w]))$Hl[$w]+=($R["Engine"]!="InnoDB"||$w!="Data_free"?$R[$w]:0);}}}if(function_exists('Adminer\db_status'))$Hl=db_status();foreach($Hl
as$w=>$W)json_row("sum-$w",format_number($W));json_row("");}elseif($_GET["script"]=="kill"){if(!$k)connection()->query("KILL ".number($_POST["kill"]));}else{foreach(count_tables(adminer()->databases(false))as$i=>$W){json_row("tables-$i",format_number($W));json_row("size-$i",db_size($i));}json_row("");}exit;}else{if(!isset($_GET["select"])&&support("single_table")){$S=tables_list();if($S)redirect(ME.(support("table")?"table=":"select=").url_escape(key($S)));}$Vg=ME.(isset($_GET["select"])?"select=&":"");$cm=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($cm&&!$k&&!$_POST["search"]){$G=true;$Zg="";if(JUSH=="sql"&&$_POST["tables"]&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))queries("SET foreign_key_checks = 0");if($_POST["truncate"]){if($_POST["tables"])$G=truncate_tables($_POST["tables"]);$Zg='Tables have been truncated.';}elseif($_POST["move"]){$G=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Zg='Tables have been moved.';}elseif($_POST["copy"]){$G=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Zg='Tables have been copied.';}elseif($_POST["drop"]){if($_POST["views"])$G=drop_views($_POST["views"]);if($G&&$_POST["tables"])$G=drop_tables($_POST["tables"]);$Zg='Tables have been dropped.';}elseif(JUSH=="sqlite"&&$_POST["check"]){foreach((array)$_POST["tables"]as$Q){foreach(get_rows("PRAGMA integrity_check(".q($Q).")")as$I)$Zg
.="<b>".h($Q)."</b>: ".h($I["integrity_check"])."<br>";}}elseif(JUSH=="mssql"&&$_POST["check"]){foreach((array)$_POST["tables"]as$Q){foreach(get_rows("DBCC CHECKTABLE (".q(table($Q)).") WITH TABLERESULTS")as$I)$Zg
.="<b>".h($Q)."</b>: ".h($I["MessageText"])."<br>";}}elseif(JUSH!="sql"){$G=(JUSH=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?" ANALYZE":""),(array)$_POST["tables"]));$Zg='Tables have been optimized.';}elseif(!$_POST["tables"])$Zg='No tables.';elseif($G=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('Adminer\idf_escape',$_POST["tables"])))){while($I=$G->fetch_assoc())$Zg
.="<b>".h($I["Table"])."</b>: ".h($I["Msg_text"])."<br>";}queries_redirect(relative_uri(),$Zg,$G);}page_header(($_GET["ns"]==""?'Database'.": ".h(DB):'Schema'.": ".h($_GET["ns"])),$k,true);if(adminer()->homepage()){if($_GET["ns"]!==""){$ti=$_GET["order"];$se=($ti||support("fast_status"));echo"<div>\n","<h3 id='tables-views'>".'Tables and views'."</h3>\n";$bm=($se?table_status():tables_list());if(!$bm)echo"<p class='message'>".'No tables.'."\n";else{echo"<form action='' method='post'>\n";if(support("table")){echo"<fieldset><legend>".'Search data in tables'." <span id='selected2'></span></legend><div>",html_select("op",adminer()->operators(),idx($_POST,"op",JUSH=="elastic"?"should":"LIKE %%"))," <input type='search' name='query' value='".h($_POST["query"])."'".on('keydown','submitKeydown','search').">"," <input type='submit' name='search' value='".'Search'."'>\n","</div></fieldset>\n";if(!$k&&$_POST["search"]&&$_POST["query"]!=""){$_GET["where"][0]["op"]=$_POST["op"];search_tables();}}echo"<div class='scrollable'>\n","<table class='nowrap checkable odds'".on('click','tableClick').on('dblclick','tableClick').">\n",'<thead><tr>','<td class="hover"><input id="check-all" type="checkbox" class="jsonly" title="'.'All'.'"'.on('click','formCheck','^(tables|views)\[').'>','<th class="sticky"'.(!$ti&&JUSH!='sqlite'?" aria-sort='ascending'":'').'><a href="'.h(substr($Vg,0,-1)).'">'.'Table'.'</a>';$e=array("Engine"=>array('Engine'.doc_link(array('sql'=>'storage-engines.html'))));if(collations())$e["Collation"]=array('Collation'.doc_link(array('sql'=>'charset-charsets.html','mariadb'=>'supported-character-sets-and-collations/')));if(function_exists('Adminer\alter_table'))$e["Data_length"]=array('Data Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT','oracle'=>'refrn/ALL_TABLES.html')),"create",'Alter table',);if(support("indexes"))$e["Index_length"]=array('Index Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT')),"indexes",'Alter indexes',);$e["Data_free"]=array('Data Free'.doc_link(array('sql'=>'show-table-status.html')),"edit",'New item');if(function_exists('Adminer\alter_table'))$e["Auto_increment"]=array('Auto Increment'.doc_link(array('sql'=>'example-auto-increment.html','mariadb'=>'auto_increment/')),"auto_increment=1&create",'Alter table',);$e["Rows"]=array('Rows'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'catalog-pg-class.html#CATALOG-PG-CLASS','oracle'=>'refrn/ALL_TABLES.html')),"select",'Select data',);if(support("comment"))$e["Comment"]=array('Comment'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-info.html#FUNCTIONS-INFO-COMMENT-TABLE','cockroach'=>'comment-on')),);$Ga=array('Engine','Collation','Comment');foreach($e
as$w=>$d)echo"<th".($ti==$w?" aria-sort='".(in_array($w,$Ga)?"ascending":"descending")."'":"")."><a href='".h($Vg)."order=$w'>$d[0]</a>";echo"<tbody>\n";if($ti){uasort($bm,function($ha,$Pa)use($ti,$Ga){$H=($ha[$ti]<$Pa[$ti]?-1:($ha[$ti]>$Pa[$ti]?1:0));return(in_array($ti,$Ga)?$H:-$H);});}$S=0;$Hl=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach($bm
as$A=>$O){$zn=($se?is_view($O):$O!==null&&!preg_match('~table|sequence~i',$O));$O=($se?$O:array('Engine'=>$O));$s=h("Table-".$A);echo'<tr><td class="hover">'.checkbox(($zn?"views[]":"tables[]"),$A,in_array("$A",$cm,true),"","","",$s),'<th class="sticky">'.(support("table")||support("indexes")?"<a href='".h(ME)."table=".url_escape($A)."' title='".'Show structure'."' id='$s'>".h($A).'</a>':h($A));if($zn&&!preg_match('~materialized~i',$O['Engine'])){$qm='View';echo'<td colspan="'.(count($e)-(support("comment")?2:1)).'">'.(support("view")?"<a href='".h(ME)."view=".url_escape($A)."' title='".'Alter view'."'>$qm</a>":$qm),"<td align='right'><a href='".h(ME)."select=".url_escape($A)."' title='".'Select data'."'>?</a>";if(support("comment"))echo'<td>'.h($O['Comment']);}else{if($se){foreach(array_keys($Hl)as$w)$Hl[$w]+=($O["Engine"]!="InnoDB"||$w!="Data_free"?idx($O,$w):0);}foreach($e
as$w=>$d){$s=" id='$w-".h($A)."'";echo($d[1]?"<td align='right'><a href='".h(ME."$d[1]=").url_escape($A)."'$s title='$d[2]'>".format_status($O,$w)."</a>":"<td$s>".h(idx($O,$w,'?')).($w=="Comment"&&$O["Error"]?" <span class='error'>".h($O["Error"])."</span>":""));}$S++;}echo"\n";}echo"<tr><td class='hover'><th class='sticky'>".sprintf('%d in total',count($bm)),"<td>".h(JUSH=="sql"?get_val("SELECT @@default_storage_engine"):""),(collations()?"<td>".h(db_collation(DB,collations())):'');if($se&&function_exists('Adminer\db_status'))$Hl=db_status();foreach($Hl
as$w=>$Gl)echo($e[$w]?"<td align='right' id='sum-$w'>".($se?format_number($Gl):""):"");echo"\n","</table>\n",($se?'':script("ajaxSetHtml('".js_escape(ME)."script=db');")),"</div>\n";if(!information_schema(DB)){$sn="<input type='submit' value='".'Vacuum'."'".on_help("VACUUM")."> ";$pi="<input type='submit' name='optimize' value='".'Optimize'."'".on_help(JUSH=="sql"?"OPTIMIZE TABLE":"VACUUM ANALYZE")."> ";$Bj=(JUSH=="sqlite"?$sn."<input type='submit' name='check' value='".'Check'."'".on_help("PRAGMA integrity_check")."> ":(JUSH=="pgsql"?$sn.$pi:(JUSH=="mssql"?"<input type='submit' name='check' value='".'Check'."'".on_help("DBCC CHECKTABLE")."> ":(JUSH=="sql"?"<input type='submit' value='".'Analyze'."'".on_help("ANALYZE TABLE")."> ".$pi."<input type='submit' name='check' value='".'Check'."'".on_help("CHECK TABLE")."> "."<input type='submit' name='repair' value='".'Repair'."'".on_help("REPAIR TABLE")."> ":"")))).(function_exists('Adminer\truncate_tables')?"<input type='submit' name='truncate' value='".'Truncate'."'".confirm().on_help(JUSH=="sqlite"?"DELETE":"TRUNCATE".(JUSH=="pgsql"?"":" TABLE"))."> ":"").(function_exists('Adminer\drop_tables')?"<input type='submit' name='drop' value='".'Drop'."'".confirm().on_help("DROP TABLE").">":"");echo($Bj?"<div class='footer'><div>\n<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>$Bj\n</div></fieldset>\n":"");$h=(support("scheme")?adminer()->schemas():adminer()->databases());if(count($h)!=1&&function_exists('Adminer\move_tables')){echo"<fieldset><legend>".'Move to another database'." <span id='selected3'></span></legend><div>";$i=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo($h?html_select("target",$h,$i):'<input name="target" value="'.h($i).'" autocapitalize="off">'),"</label> <input type='submit' name='move' value='".'Move'."'>",(support("copy")?" <input type='submit' name='copy' value='".'Copy'."'> ".checkbox("overwrite",1,$_POST["overwrite"],'overwrite'):""),"</div></fieldset>\n";}echo"<input type='hidden' name='all' value=''".on('click','countTables',$S).">\n",input_token(),"</div></div>\n";}echo"</form>\n",script("tableCheck();");}echo(function_exists('Adminer\alter_table')?"<p class='links hover'><a href='".h(ME)."create='>".'Create table'."</a>\n":''),(support("view")?"<a href='".h(ME)."view='>".'Create view'."</a>\n":""),"</div>\n";if(support("routine")){echo"<div>\n","<h3 id='routines'>".'Routines'."</h3>\n";$sk=routines();if($sk){echo"<table class='odds'>\n",'<thead><tr><th>'.'Name'.'<th>'.'Type'.'<th>'.'Return type'."<td class='hover'><tbody>\n";foreach($sk
as$I){$A=($I["SPECIFIC_NAME"]==$I["ROUTINE_NAME"]?"":"&name=".url_escape($I["ROUTINE_NAME"]));echo'<tr>','<th><a href="'.h(ME.($I["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').url_escape($I["SPECIFIC_NAME"]).$A).'" title="'.'Call'.'">'.h($I["ROUTINE_NAME"]).'</a>','<td>'.h($I["ROUTINE_TYPE"]),'<td>'.h($I["DTD_IDENTIFIER"]),'<td class="hover"><a href="'.h(ME.($I["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').url_escape($I["SPECIFIC_NAME"]).$A).'">'.'Alter'."</a>";}echo"</table>\n";}echo'<p class="links hover">'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.'Create procedure'.'</a>':'').'<a href="'.h(ME).'function=">'.'Create function'."</a>\n","</div>\n";}if(support("sequence")){echo"<div>\n","<h3 id='sequences'>".'Sequences'."</h3>\n";$Pk=get_vals("SELECT relname FROM pg_class WHERE relkind = 'S' AND relnamespace = ".driver()->nsOid." ORDER BY relname");if($Pk){echo"<table class='odds'>\n","<thead><tr><th>".'Name'."<tbody>\n";foreach($Pk
as$W)echo"<tr><th><a href='".h(ME)."sequence=".url_escape($W)."'>".h($W)."</a>\n";echo"</table>\n";}echo"<p class='links hover'><a href='".h(ME)."sequence='>".'Create sequence'."</a>\n","</div>\n";}if(support("type")){echo"<div>\n","<h3 id='user-types'>".'User types'."</h3>\n";$pn=types();if($pn){echo"<table class='odds'>\n","<thead><tr><th>".'Name'."<tbody>\n";foreach($pn
as$W)echo"<tr><th><a href='".h(ME)."type=".url_escape($W)."'>".h($W)."</a>\n";echo"</table>\n";}echo"<p class='links hover'><a href='".h(ME)."type='>".'Create type'."</a>\n","</div>\n";}if(support("event")){echo"<div>\n","<h3 id='events'>".'Events'."</h3>\n";$J=get_rows("SHOW EVENTS");if($J){echo"<table>\n","<thead><tr><th>".'Name'."<th>".'Schedule'."<th>".'Start'."<th>".'End'."<td class='hover'><tbody>\n";foreach($J
as$I)echo"<tr>","<th>".h($I["Name"]),"<td>".($I["Execute at"]?'At given time'."<td>".h($I["Execute at"]):'Every'." ".h($I["Interval value"])." ".h($I["Interval field"])."<td>".h($I["Starts"])),"<td>".h($I["Ends"]),'<td class="hover"><a href="'.h(ME).'event='.url_escape($I["Name"]).'">'.'Alter'.'</a>';echo"</table>\n";$xd=get_val("SELECT @@event_scheduler");if($xd&&$xd!="ON")echo"<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($xd)."\n";}echo'<p class="links hover"><a href="'.h(ME).'event=">'.'Create event'."</a>\n","</div>\n";}}elseif(support("extension")){$Id=get_rows("SELECT e.extname, e.extversion, n.nspname, obj_description(e.oid, 'pg_extension') AS comment
FROM pg_extension e
JOIN pg_namespace n ON n.oid = e.extnamespace
ORDER BY e.extname");if($Id){echo"<div>\n","<h3 id='extensions'>".'Extensions'."</h3>\n","<table class='odds'>\n","<thead><tr><th>".'Name'."<th>".'Version'."<th>".'Schema'."<th>".'Comment'."<tbody>\n";foreach($Id
as$I)echo"<tr><th><code class='jush-pgsqlext'>".h($I["extname"])."</code>","<td>".h($I["extversion"]),"<td><a href='".h(substr(ME,0,-1).url_escape($I["nspname"]))."'>".h($I["nspname"])."</a>","<td>".h($I["comment"]),"\n";echo"</table>\n","</div>\n";}}}}page_footer();