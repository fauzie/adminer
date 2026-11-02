<?php
/** Adminer - Compact database management
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 6.2.0
*/namespace
Adminer;if(isset($_GET["status"]))$_GET["variables"]=$_GET["status"];if(isset($_GET["import"]))$_GET["sql"]=$_GET["import"];const
VERSION="6.2.0";error_reporting(24575);set_error_handler(function($yd,$Bd){return!!preg_match('~^Undefined (array key|offset|index)~',$Bd);},E_WARNING|E_NOTICE);$fe=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($fe||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$W){$yn=filter_input_array(constant("INPUT$W"),FILTER_UNSAFE_RAW);if($yn)$$W=$yn;}}$_COOKIE=array_filter($_COOKIE,'is_scalar');if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection($h=null){return($h?:Db::$instance);}function
adminer(){return
Adminer::$instance;}function
driver(){return
Driver::$instance;}function
connect(){$ic=adminer()->credentials();$H=Driver::connect($ic[0],$ic[1],$ic[2]);return(is_object($H)?$H:null);}function
idf_unescape($t){if(!preg_match('~^[`\'"[]~',$t))return$t;$ug=substr($t,-1);return
str_replace($ug.$ug,$ug,substr($t,1,-1));}function
q($P){return
connection()->quote($P);}function
idx($Ea,$w,$l=null){return($Ea&&array_key_exists($w,$Ea)?$Ea[$w]:$l);}function
number($W){return
preg_replace('~[^0-9]+~','',$W);}function
int_type(){return'(tiny|small|medium|big)?int(eger|\d)?';}function
number_type(){return'(^('.int_type().'|decimal|numeric|number|real|(binary_|half_|scaled_)?float\d?|(binary_)?double( precision)?|(small)?money)$)';}function
text_type(){return'char|text'.(JUSH=="sql"?'|enum|set':'');}function
is_user_type($T){return
in_array($T,idx(driver()->structuredTypes(),'User types',array()));}function
full_type_sql(array$n){$T=$n["type"];return(is_user_type($T)?idf_escape($T).substr($n["full_type"],strlen($T)):$n["full_type"]);}function
is_searchable(array$n,array$W){if(!isset($n["privileges"]["where"]))return
false;if(preg_match('~NULL$~',$W["op"]))return
true;$T=$n["type"];$Xk=$W["val"];$Xa='binary$|bytea|raw|image|bfile|^vector$'.(JUSH=="mssql"?'|^timestamp$':'|^bit').(JUSH=="oracle"?'|^blob|^long|rowid':'');if(preg_match("~$Xa~",$T))return
false;if(preg_match(number_type(),$T)){$ei='-?\d+(\.\d+)?';$zh='';if(preg_match('~IN$~',$W["op"])){$Xk=preg_replace('~^\s*\(\s*(.*?)\s*\)\s*$~s','\1',$Xk);$zh="( *, *$ei)*";}elseif($W["op"]=="BETWEEN")$zh="\\s+AND\\s+$ei";return(bool)preg_match('~^'.$ei.$zh.'$~i',$Xk);}if(preg_match('~^(small)?date|^timestamp~',$T))return(bool)preg_match('~^\d+-\d+-\d+~',$Xk);if(preg_match('~^time~',$T))return(bool)preg_match('~^\d+:\d+~',$Xk);if(preg_match('~^bool~',$T)||(JUSH=="mssql"&&$T=="bit"))return(bool)preg_match('~^(t|f|true|false|[01])$~i',$Xk);return
true;}function
remove_slashes(array$Y,$fe=false){$H=array();foreach($Y
as$w=>$W)$H[stripslashes($w)]=(is_array($W)?remove_slashes($W,$fe):($fe?$W:stripslashes($W)));return$H;}function
bracket_escape($t,$Qa=false){static$Zm=array(':'=>':1',']'=>':2','['=>':3','"'=>':4','='=>':5');return
strtr($t,($Qa?array_flip($Zm):$Zm));}function
url_escape($P){static$Zm=array();if(!$Zm){$Zm=array(' '=>'+');foreach(str_split("\"'<>#%&+=?".ini_get("arg_separator.input"))as$mb)$Zm[$mb]=sprintf('%%%02X',ord($mb));for($r=0;$r<256;$r++){if($r<32||$r>126)$Zm[chr($r)]=sprintf('%%%02X',$r);}}return
strtr((string)$P,$Zm);}function
min_version($Xn,$Pg="",$h=null){$h=connection($h);$ul=$h->server_info;if($Pg&&preg_match('~([\d.]+)-MariaDB~',$ul,$_)){$ul=$_[1];$Xn=$Pg;}return$Xn&&version_compare($ul,$Xn)>=0;}function
charset(Db$g){return(min_version("5.5.3",0,$g)?"utf8mb4":"utf8");}function
ini_set($Ci,$X){return(function_exists('ini_set')?\ini_set($Ci,$X):false);}function
ini_bool($Lf){$W=ini_get($Lf);return(preg_match('~^(on|true|yes)$~i',$W)||(int)$W);}function
ini_bytes($Lf){$W=ini_get($Lf);switch(strtolower(substr($W,-1))){case'g':$W=(int)$W*1024;case'm':$W=(int)$W*1024;case'k':$W=(int)$W*1024;}return$W;}function
max_input_vars($I,$Ui){$Tg=(int)ini_get("max_input_vars");return($Tg?(int)floor(($Tg-$Ui)/$I):0);}function
max_input_vars_error(){$Lf="max_input_vars";return
sprintf('Maximum number of allowed fields exceeded. Please increase %s.',"<b>$Lf = ".ini_get($Lf)."</b>");}function
sid(){static$H;if($H===null)$H=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$H;}function
set_password($Wn,$M,$U,$D){$_SESSION["pwds"][$Wn][$M][$U]=($_COOKIE["adminer_key"]&&is_string($D)?array(encrypt_string($D,$_COOKIE["adminer_key"])):$D);}function
get_password(){$H=get_session("pwds");if(is_array($H))$H=($_COOKIE["adminer_key"]?decrypt_string($H[0],$_COOKIE["adminer_key"]):false);return$H;}function
get_val($F,$n=0,$Qb=null){$Qb=connection($Qb);$G=$Qb->query($F);if(!is_object($G))return
false;$I=$G->fetch_row();return($I?$I[$n]:false);}function
get_vals($F,$d=0){$H=array();$G=connection()->query($F);if(is_object($G)){while($I=$G->fetch_row())$H[]=$I[$d];}return$H;}function
get_key_vals($F,$h=null,$xl=true){$h=connection($h);$H=array();$G=$h->query($F);if(is_object($G)){while($I=$G->fetch_row()){if($xl)$H[$I[0]]=$I[1];else$H[]=$I[0];}}return$H;}function
get_rows($F,$h=null,$m="<p class='error'>"){$Qb=connection($h);$H=array();$G=$Qb->query($F);if(is_object($G)){while($I=$G->fetch_assoc())$H[]=$I;}elseif(!$G&&!$h&&$m&&(defined('Adminer\PAGE_HEADER')||$m=="-- "))echo$m.adminer()->error()."\n";return$H;}function
unique_array($I,array$v){foreach($v
as$u){if(preg_match("~^(PRIMARY|UNIQUE)$~",$u["type"])&&!$u["partial"]&&!$u["invalid"]){$H=array();foreach($u["columns"]as$w){if(!isset($I[$w]))continue
2;$H[$w]=$I[$w];}return$H;}}}function
where_function($Ce,$d,array$n){if($Ce=="md5")return
driver()->md5($d,$n)?:$d;return(in_array($Ce,driver()->functions)||in_array($Ce,driver()->grouping)?apply_sql_function($Ce,$d):$d);}function
where(array$Z,array$o=array()){$H=array();foreach((array)$Z["where"]as$w=>$W){$w=bracket_escape($w,true);$d=idf_escape($w);$n=idx($o,$w,array());$Zd=$n["type"];$Yf=$n&&(is_blob($n)||preg_match('~binary~',$Zd));$H[]=$d.($Yf&&!is_utf8($W)?" = ".driver()->quoteBinary($W):(JUSH=="sql"&&$Zd=="json"?" = CAST(".q($W)." AS JSON)":(JUSH=="pgsql"&&preg_match('~^jsonb?$~',$n["full_type"])?"::jsonb = ".q($W)."::jsonb":(JUSH=="pgsql"&&preg_match('~\.~',$Zd)?"::text = ".q($W):(JUSH=="sql"&&is_numeric($W)&&preg_match('~\.~',$W)?" LIKE ".q($W):(JUSH=="mssql"&&strpos($Zd,"datetime")===false?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$W)):" = ".unconvert_field($n,q($W))))))));if(JUSH=="sql"&&preg_match('~char|text~',$Zd)&&preg_match("~[^ -@]~",$W))$H[]="$d = ".q($W)." COLLATE ".charset(connection())."_bin";}foreach((array)$Z["null"]as$w)$H[]=idf_escape($w)." IS NULL";foreach((array)$Z["col"]as$r=>$Bb){$W=idx($Z["val"],$r);$H[]=where_function(idx($Z["fun"],$r),idf_escape($Bb),idx($o,$Bb,array())).($W!==null?" = ".q($W):" IS NULL");}return
implode(" AND ",$H);}function
where_columns(array$o){$H=array();foreach((array)$_GET["null"]as$w)$H[$w]=true;foreach(array_keys((array)$_GET["where"])as$w)$H[bracket_escape($w,true)]=true;foreach((array)$_GET["col"]as$Bb)$H[$Bb]=true;return
array_intersect_key($H,$o);}function
where_check($W,array$o=array()){parse_str($W,$pb);remove_slashes(array(&$pb));return
where($pb,$o);}function
where_link($r,$d,$X,$_i="="){$xi=($X!==null?$_i:"IS NULL");return"&where[$r][col]=".url_escape($d).($xi!=first(adminer()->operators())?"&where[$r][op]=".url_escape($xi):"")."&where[$r][val]=".url_escape($X);}function
convert_fields(array$e,array$o,array$L=array()){$H="";foreach($e
as$w=>$W){if($L&&!in_array(idf_escape($w),$L))continue;$Fa=convert_field($o[$w]);if($Fa)$H
.=", $Fa AS ".idf_escape($w);}return$H;}function
cookie_path(){return
strtr(preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),array(";"=>"%3B",","=>"%2C"));}function
cookie($A,$X,$Dg=2592000){header("Set-Cookie: $A=".rawurlencode($X).($Dg?"; expires=".gmdate("D, d M Y H:i:s",time()+$Dg)." GMT":"")."; path=".cookie_path().(HTTPS?"; secure":"").($A=="adminer_import"?"":"; HttpOnly")."; SameSite=lax",false);}function
php_error_text($m){return(ini_bool("html_errors")?html_entity_decode(strip_tags($m)):$m);}function
get_url($Gn,$Zb){$http_response_header=null;$Ad=array();set_error_handler(function($yd,$m)use(&$Ad){$Ad[]=preg_replace('~^file_get_contents\([^)]*\)( \[[^]]*\])?:\s*~','',php_error_text($m));return
true;});$H=file_get_contents($Gn,false,$Zb);restore_error_handler();$cf=(function_exists('http_get_last_response_headers')?http_get_last_response_headers():$http_response_header);return
array($H,(preg_match('~^HTTP/[\d.]+ (\d+)~',idx($cf,0,''),$_)?$_[1]:''),(array)$cf,($H===false?implode("\n",$Ad):''),);}function
json_decode_exact($gg){$gg=preg_replace('~"(\\\\u0001(?:[^"\\\\]|\\\\.)*+")|"(?:[^"\\\\]|\\\\.)*+"(*SKIP)(*FAIL)~','"\\\\u0001$1',$gg);return
json_decode(preg_replace('~"(?:[^"\\\\]|\\\\.)*+"(*SKIP)(*FAIL)|-?\d[-+.\deE]*+~','"\\\\u0001$0"',$gg));}function
json_scalar($W){return(is_string($W)&&substr($W,0,1)=="\1"?substr($W,1):$W);}function
json_encode_exact($W,$le=0){return
preg_replace('~"\\\\u0001(-?\d[^"\\\\]*)"|(")\\\\u0001(\\\\u0001(?:[^"\\\\]|\\\\.)*+")|"(?:[^"\\\\]|\\\\.)*+"(*SKIP)(*FAIL)~','$1$2$3',json_encode($W,$le));}function
get_settings($dc){parse_str($_COOKIE[$dc],$yl);return$yl;}function
get_setting($w,$dc="adminer_settings",$l=null){return
idx(get_settings($dc),$w,$l);}function
save_settings(array$yl,$dc="adminer_settings"){$X=http_build_query($yl+get_settings($dc));cookie($dc,$X);$_COOKIE[$dc]=$X;}function
restart_session(){if(!ini_bool("session.use_cookies")&&(!function_exists('session_status')||session_status()==PHP_SESSION_NONE))session_start();}function
stop_session($oe=false){$Jn=ini_bool("session.use_cookies");if(!$Jn||$oe){session_write_close();if($Jn&&ini_set("session.use_cookies",'0')===false)session_start();}}function&get_session($w){return$_SESSION[$w][DRIVER][SERVER][$_GET["username"]];}function
set_session($w,$W){$_SESSION[$w][DRIVER][SERVER][$_GET["username"]]=$W;}function
auth_url($Wn,$M,$U,$k=null){$Fn=remove_from_uri(implode("|",array_keys(SqlDriver::$drivers))."|username|ext|".($k!==null?"db|":"").($Wn=='mssql'||$Wn=='pgsql'?"":"ns|").session_name());preg_match('~([^?]*)\??(.*)~',$Fn,$_);return"$_[1]?".(sid()?SID."&":"").($_GET["ext"]?"ext=".url_escape($_GET["ext"])."&":"").($Wn!="server"||$M!=""?url_escape($Wn)."=".url_escape($M)."&":"")."username=".url_escape($U).($k!=""?"&db=".url_escape($k):"").($_[2]?"&$_[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($Lg,$jh=null){if($jh!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($Lg!==null?$Lg:$_SERVER["REQUEST_URI"]))][]=$jh;}if($Lg!==null){if($Lg=="")$Lg=".";header("Location: $Lg");exit;}}function
query_redirect($F,$Lg,$jh,$mk=true,$Id=true,$Td=false,$Lm=""){if($Id){$Tl=microtime(true);$Td=!connection()->query($F);$Lm=format_time($Tl);}$Ml=($F?adminer()->messageQuery($F,$Lm,$Td):"");if($Td){adminer()->error
.=adminer()->error().$Ml.script("messagesPrint();")."<br>";return
false;}if($mk)redirect($Lg,$jh.$Ml);return
true;}class
Queries{static$queries=array();static$start=0;}function
remember_query($F){if(!Queries::$start)Queries::$start=microtime(true);Queries::$queries[]=(driver()->delimiter!=';'?$F:(preg_match('~;$~',$F)?"DELIMITER ;;\n$F;\nDELIMITER ":$F).";");}function
queries($F){remember_query($F);return
connection()->query($F);}function
apply_queries($F,array$S,$Cd='Adminer\table'){foreach($S
as$Q){if(!queries("$F ".$Cd($Q)))return
false;}return
true;}function
queries_redirect($Lg,$jh,$mk){$ek=implode("\n",Queries::$queries);$Lm=format_time(Queries::$start);return
query_redirect($ek,$Lg,$jh,$mk,false,!$mk,$Lm);}function
format_time($Tl){return
sprintf('%.3f s',max(0,microtime(true)-$Tl));}function
relative_uri($Fn=''){return
preg_replace_callback('~^[^?]*~',function($_){return
str_replace(":","%3A",$_[0]);},preg_replace('~^[^?]*/([^?]*)~','\1',($Fn?:$_SERVER["REQUEST_URI"])));}function
remove_from_uri($bj=""){return
substr(preg_replace("~(?<=[?&])($bj".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
get_files($A,$xc=false){$be=$_FILES[$A];if(!$be)return
null;foreach($be
as$w=>$W)$be[$w]=(array)$W;$H=array();foreach($be["error"]as$w=>$m){if($m)return$m;$p=$be["name"][$w];$Tm=$be["tmp_name"][$w];$Xb=file_get_contents($xc&&preg_match('~\.gz$~',$p)?"compress.zlib://$Tm":$Tm);if($xc){$Tl=substr($Xb,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$Tl))$Xb=iconv("utf-16","utf-8",$Xb);elseif($Tl=="\xEF\xBB\xBF")$Xb=substr($Xb,3);}$H[]=array($p,$Xb);}return$H;}function
get_file($w,$xc=false,$Ec=""){$ee=get_files($w,$xc);if(!is_array($ee))return$ee;$H='';foreach($ee
as$be){$Xb=$be[1];$H
.=$Xb;if($Ec)$H
.=(preg_match("($Ec\\s*\$)",$Xb)?"":$Ec)."\n\n";}return$H;}function
upload_error($m){$bh=($m==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($m?'Unable to upload a file.'.($bh?" ".sprintf('Maximum allowed file size is %sB.',$bh):""):'File does not exist.');}function
is_utf8($W){return(preg_match('~~u',$W)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$W));}function
utf8_length($W){return
strlen(preg_replace('~[\x80-\xBF]~','',$W));}function
format_number($W){preg_match('~^#+([^#0]+)(?:(#+)\1)?(#*0)$~u','#,##0',$_);$Bl=strlen($_[3]);$H=number_format($W,0,".","");$H=preg_replace('~\B(?=(\d{'.(strlen($_[2])?:$Bl).'})*\d{'.$Bl.'}$)~',$_[1],$H);return
strtr($H,preg_split('~~u','0123456789',-1,PREG_SPLIT_NO_EMPTY));}function
format_status(array$R,$w){$W=idx($R,$w,'?');if(!is_numeric($W))return
h($W);if($W<0)return'?';$Aa=($w=="Rows"&&(JUSH=="sqlite"||$R["Engine"]==(JUSH=="pgsql"?"table":"InnoDB")));$W=in_array($w,array("Data_length","Index_length","Data_free"))?adminer()->formatSizeValue($W):format_number($W);return($Aa?"~ ":"").$W;}function
friendly_url($W){return
preg_replace('~\W~i','-',$W);}function
table_status1($Q,$Ud=false){$H=table_status($Q,$Ud);return($H?reset($H):array("Name"=>$Q));}function
column_foreign_keys($Q){$H=array();foreach(adminer()->foreignKeys($Q)as$q){foreach($q["source"]as$W)$H[$W][]=$q;}return$H;}function
fields_from_edit(){$H=array();foreach((array)$_POST["field_keys"]as$w=>$W){if($W!=""){$W=bracket_escape($W);$_POST["function"][$W]=$_POST["field_funs"][$w];$_POST["fields"][$W]=$_POST["field_vals"][$w];}}foreach((array)$_POST["fields"]as$w=>$W){$A=bracket_escape($w,true);$H[$A]=array("field"=>$A,"full_type"=>"","type"=>"","privileges"=>array("insert"=>1,"update"=>1,"where"=>1,"order"=>1),"null"=>true,"auto_increment"=>($A==driver()->primary),);}return$H;}function
dump_headers($qf,$Ch=false){$H=adminer()->dumpHeaders($qf,$Ch);$Wi=$_POST["output"];if($Wi!="text"||$H=="tar"){$Mb=($Wi!="text"&&$Wi!="file"&&preg_match('~^[0-9a-z]+$~',$Wi)?".$Wi":"");header("Content-Disposition: attachment; filename=".adminer()->dumpFilename($qf).".$H$Mb");}session_write_close();if(!ob_get_level())ob_start(null,4096);ob_flush();flush();return$H;}function
dump_csv(array$I){$ln=$_POST["format"]=="tsv";foreach($I
as$w=>$W){if(preg_match('~["\n]|^0[^.]|\.\d*0$|'.($ln?'\t':'[,;]|^$').'~',$W))$I[$w]='"'.str_replace('"','""',$W).'"';}echo
implode(($_POST["format"]=="csv"?",":($ln?"\t":";")),$I)."\r\n";}function
parse_csv($lc,$il){$H=array();preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$lc,$Rg);foreach($Rg[0]as$I){preg_match_all("~((?>\"[^\"]*\")+|[^$il]*)$il~",$I.$il,$Sg);$H[]=$Sg[1];}return$H;}function
csv_value($W){return(preg_match('~^".*"$~s',$W)?idf_unescape($W):$W);}function
apply_sql_function($De,$d){return($De?($De=="unixepoch"?"DATETIME($d, '$De')":($De=="count distinct"?"COUNT(DISTINCT ":strtoupper("$De("))."$d)"):$d);}function
get_temp_dir(){return
ini_get("upload_tmp_dir")?:sys_get_temp_dir();}function
file_open_lock($p){if(is_link($p))return;$ve=@fopen($p,"c+");if(!$ve)return;@chmod($p,0660);if(!flock($ve,LOCK_EX)){fclose($ve);return;}return$ve;}function
file_write_unlock($ve,$pc){rewind($ve);fwrite($ve,$pc);ftruncate($ve,strlen($pc));file_unlock($ve);}function
file_unlock($ve){flock($ve,LOCK_UN);fclose($ve);}function
first(array$Ea){return
reset($Ea);}function
password_file($i){$p=get_temp_dir()."/adminer.key";if(!$i&&!file_exists($p))return'';$ve=file_open_lock($p);if(!$ve)return'';$H=stream_get_contents($ve);if(!$H){$H=rand_string();file_write_unlock($ve,$H);}else
file_unlock($ve);return$H;}function
rand_string(){return(function_exists('random_bytes')?bin2hex(random_bytes(16)):md5(uniqid(strval(mt_rand()),true)));}function
select_value($W,$z,array$n,$Jm,array$xj=array()){if(is_array($W)){$H="";if(array_filter($W,'is_array')==array_values($W)){$lg=array();foreach($W
as$V)$lg+=array_fill_keys(array_keys($V),null);foreach(array_keys($lg)as$ig)$H
.="<th>".h($ig);foreach($W
as$V){$H
.="<tr>";foreach(array_merge($lg,$V)as$Qn)$H
.="<td>".select_value($Qn,$z,$n,$Jm,$xj);}}else{foreach($W
as$ig=>$V)$H
.="<tr>".($W!=array_values($W)?"<th>".h($ig):"")."<td>".select_value($V,$z,$n,$Jm,$xj);}return"<table>$H</table>";}if(!$z)$z=adminer()->selectLink($W,$n);if($z===null){if(is_mail($W))$z="mailto:$W";if(is_url($W))$z=$W;}$W=driver()->value($W,$n);$H=adminer()->editVal($W,$n);if($H!==null){if(!is_utf8($H))$H="\0";elseif($Jm!=""&&is_shortable($n))$H=shorten_utf8($H,max(0,+$Jm),"",$xj);else$H=highlight_matches($H,$xj);}return
adminer()->selectVal($H,$z,$n,$W);}function
is_blob(array$n){return
preg_match('~blob|bytea|raw|file'.(JUSH=="mssql"?'|binary|image':'').'~',$n["type"])&&!in_array($n["type"],idx(driver()->structuredTypes(),'User types',array()));}function
is_identity_always(array$n){return$n["auto_increment"]&&(JUSH=="mssql"||$n["default"]=="GENERATED ALWAYS AS IDENTITY");}function
is_mail($nd){$Ha='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$Xc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$wj="$Ha+(\\.$Ha+)*@($Xc?\\.)+$Xc";return
is_string($nd)&&preg_match("(^$wj(,\\s*$wj)*\$)i",$nd);}function
is_url($P){$Xc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^((https?):)?//($Xc?\\.)+$Xc(:\\d+)?(/\\S*)?(\\?\\S*)?(#\\S*)?\$~iD",$P);}function
is_ipv6($na){$Ke='[\da-f]{1,4}';$Xf='\d{1,3}(\.\d{1,3}){3}';return(bool)preg_match("~^(($Ke:){7}$Ke|($Ke:){6}$Xf|(($Ke:)*$Ke)?::(($Ke:)*($Ke|$Xf))?)$~iD",$na);}function
is_shortable(array$n){return!preg_match('~'.number_type().'|date|time|year~',$n["type"]);}function
url_host($mf){return(strpos($mf,":")!==false?"[$mf]":$mf);}function
server_parts(array$qj){return
array("scheme"=>(string)$qj["scheme"],"host"=>(string)$qj["host"],"port"=>(string)$qj["port"],"socket"=>(string)$qj["socket"],"path"=>(string)$qj["path"],);}function
parse_server($M){if($M=="")return
server_parts(array());if($M[0]==":"&&!is_ipv6($M)){$_k=substr($M,1);if(preg_match('~^\d+$~D',$_k))return
server_parts(array("port"=>$_k));return(preg_match('~^/[-\w.:/]*$~D',$_k)?server_parts(array("socket"=>$_k)):null);}$Vk="";if(preg_match('~^([-+.\w]+)://~',$M,$_)){$Vk=strtolower($_[1]);$M=substr($M,strlen($_[0]));}if(preg_match('~^\[(.+)](:(\d+))?(/[-\w./]*)?$~D',$M,$_))return(is_ipv6($_[1])?server_parts(array("scheme"=>$Vk,"host"=>$_[1],"port"=>$_[3],"path"=>$_[4])):null);if(is_ipv6($M))return
server_parts(array("scheme"=>$Vk,"host"=>$M));if(preg_match('~^(/[-\w./]*)(:(\d+))?$~D',$M,$_))return
server_parts(array("scheme"=>$Vk,"host"=>$_[1],"port"=>$_[3]));return(preg_match('~^([-\w.]*)(:(\d+))?(/[-\w./]*)?$~D',$M,$_)?server_parts(array("scheme"=>$Vk,"host"=>$_[1],"port"=>$_[3],"path"=>$_[4])):null);}function
count_rows($Q,array$Z,$Zf,array$Ke){$F=" FROM ".table($Q).($Z?" WHERE ".implode(" AND ",$Z):"");return($Zf&&(JUSH=="sql"||count($Ke)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$Ke).")$F":"SELECT COUNT(*)".($Zf?" FROM (SELECT 1$F GROUP BY ".implode(", ",$Ke).") x":$F));}function
slow_query($F){$k=adminer()->database();$Mm=adminer()->queryTimeout();$Dl=driver()->slowQuery($F,$Mm);$h=null;if(!$Dl&&support("kill")){$h=connect();if($h&&($k==""||$h->select_db($k))){$mg=number(get_val(connection_id(),0,$h));echo
script("const timeout = setTimeout(() => { ajax('".js_escape(ME)."script=kill', function () {}, 'kill=$mg&token=".get_token()."'); }, 1000 * $Mm);");}}ob_flush();flush();$H=@get_key_vals(($Dl?:$F),$h,false);if($h){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$H;}function
get_token(){$jk=rand(1,1e6);return($jk^$_SESSION["token"]).":$jk";}function
verify_token(){list($Um,$jk)=explode(":",$_POST["token"]);return($jk^$_SESSION["token"])==$Um&&in_array($_SERVER["HTTP_SEC_FETCH_SITE"],array("","same-origin"));}function
compress_alphabet(){return
strtr(implode(range('"','~')),"'\\","!\n");}function
decompress_string($P,$Lc=""){$ya=array_flip(str_split(compress_alphabet()));$x=strlen($P);$Tn=($x?13*($x-1)/2-$ya[$P[0]]:0);$Xa="";$_k=0;$Ak=0;for($r=1;$r<$x;$r+=2){$_k=($_k<<13)+$ya[$P[$r]]*93+$ya[$P[$r+1]];$Ak+=13;while($Ak>=8&&$Tn>=8){$Ak-=8;$Tn-=8;$Xa
.=chr($_k>>$Ak);$_k&=(1<<$Ak)-1;}}if($Xa=="")return"";if($Lc!=""&&function_exists('inflate_init'))return
inflate_add(inflate_init(ZLIB_ENCODING_RAW,array('dictionary'=>$Lc)),$Xa,ZLIB_FINISH);return($Lc==""&&function_exists('gzinflate')?gzinflate($Xa):inflate($Xa,$Lc));}function
inflate($Xa,$Lc=""){$Ag=array(3,4,5,6,7,8,9,10,11,13,15,17,19,23,27,31,35,43,51,59,67,83,99,115,131,163,195,227,258);$Bg=array(0,0,0,0,0,0,0,0,1,1,1,1,2,2,2,2,3,3,3,3,4,4,4,4,5,5,5,5,0);$Pc=array(1,2,3,4,5,7,9,13,17,25,33,49,65,97,129,193,257,385,513,769,1025,1537,2049,3073,4097,6145,8193,12289,16385,24577);$Rc=array(0,0,0,0,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12,13,13);$H=$Lc;$E=0;do{$ge=inflate_bits($Xa,$E,1);$T=inflate_bits($Xa,$E,2);if(!$T){$E=($E+7)&~7;$x=inflate_bits($Xa,$E,16);$E+=16;$H
.=substr($Xa,$E>>3,$x);$E+=$x<<3;}else{if($T==1){$Jg=array_merge(array_fill(0,144,8),array_fill(0,112,9),array_fill(0,24,7),array_fill(0,8,8));$Sc=array_fill(0,30,5);}else{$Ig=inflate_bits($Xa,$E,5)+257;$Qc=inflate_bits($Xa,$E,5)+1;$Hi=array(16,17,18,0,8,7,9,6,10,5,11,4,12,3,13,2,14,1,15);$qh=array_fill(0,19,0);$ph=inflate_bits($Xa,$E,4)+4;for($r=0;$r<$ph;$r++)$qh[$Hi[$r]]=inflate_bits($Xa,$E,3);$rh=inflate_table($qh);$Cg=array();while(count($Cg)<$Ig+$Qc){$im=inflate_symbol($Xa,$E,$rh);if($im==16)$Cg=array_merge($Cg,array_fill(0,inflate_bits($Xa,$E,2)+3,end($Cg)));elseif($im==17)$Cg=array_merge($Cg,array_fill(0,inflate_bits($Xa,$E,3)+3,0));elseif($im==18)$Cg=array_merge($Cg,array_fill(0,inflate_bits($Xa,$E,7)+11,0));else$Cg[]=$im;}$Jg=array_slice($Cg,0,$Ig);$Sc=array_slice($Cg,$Ig);}$Kg=inflate_table($Jg);$Uc=inflate_table($Sc);while(($im=inflate_symbol($Xa,$E,$Kg))!=256){if($im<256)$H
.=chr($im);else{$x=$Ag[$im-257]+inflate_bits($Xa,$E,$Bg[$im-257]);$Tc=inflate_symbol($Xa,$E,$Uc);$B=strlen($H)-$Pc[$Tc]-inflate_bits($Xa,$E,$Rc[$Tc]);for($r=0;$r<$x;$r++)$H
.=$H[$B+$r];}}}}while(!$ge);return($Lc==""?$H:substr($H,strlen($Lc)));}function
inflate_bits($Xa,&$E,$fc){$H=0;for($r=0;$r<$fc;$r++){$H+=((ord($Xa[$E>>3])>>($E&7))&1)<<$r;$E++;}return$H;}function
inflate_table(array$Cg){$Q=array();$Ab=0;for($Ya=1;$Ya<=max($Cg);$Ya++){foreach($Cg
as$im=>$x){if($x==$Ya){$Q[$Ya][$Ab]=$im;$Ab++;}}$Ab<<=1;}return$Q;}function
inflate_symbol($Xa,&$E,array$Q){$Ab=0;$Ya=0;do{$Ab=($Ab<<1)+inflate_bits($Xa,$E,1);$Ya++;}while(!isset($Q[$Ya][$Ab]));return$Q[$Ya][$Ab];}function
script($Il,$Ym="\n"){return"<script".nonce().">$Il</script>$Ym";}function
script_src($Gn,$Ac=false){return"<script src='".h($Gn)."'".nonce().($Ac?" defer":"")."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
on($Dd,$Te,$Ba=null){$Da=array();foreach(array_slice(func_get_args(),2)as$W)$Da[]=json_encode($W,256);return" data-on$Dd='".str_replace(array('&','<',"'"),array('&amp;','&lt;','&#039;'),"$Te(".implode(", ",$Da).")")."'";}function
input_hidden($A,$X=""){return"<input type='hidden' name='".h($A)."' value='".h($X)."'>\n";}function
input_token(){return
input_hidden("token",get_token());}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($P){return
str_replace(array('&','<','"',"'","\0"),array('&amp;','&lt;','&quot;','&#039;','&#0;'),$P);}function
nl_br($P){return
str_replace("\n","<br>",$P);}function
checkbox($A,$X,$sb,$qg="",$c="",$yb="",$sg=""){$H="<input type='checkbox' name='$A' value='".h($X)."'".($sb?" checked":"").($qg==""&&$yb?" class='$yb'":"").($sg?" aria-labelledby='$sg'":"").$c.">";return($qg!=""?"<label".($yb?" class='$yb'":"").">$H".h($qg)."</label>":$H);}function
optionlist($Ei,$el=null,$Kn=false){$H="";foreach($Ei
as$ig=>$V){$Gi=array($ig=>$V);if(is_array($V)){$H
.='<optgroup label="'.h($ig).'">';$Gi=$V;}foreach($Gi
as$w=>$W)$H
.='<option'.($Kn||is_string($w)?' value="'.h($w).'"':'').($el!==null&&($Kn||is_string($w)?(string)$w:$W)===$el?' selected':'').'>'.h($W);if(is_array($V))$H
.='</optgroup>';}return$H;}function
group_system(array$Kh,$Uk=false){$H=array();$jm=array();foreach($Kh
as$A){if($Uk?driver()->isSystem(DB,$A):driver()->isSystem($A))$jm[]=$A;else$H[]=$A;}if($jm)$H[sprintf('System%s','')]=$jm;return$H;}function
html_select($A,array$Ei,$X="",$c="",$sg=""){static$qg=0;$rg="";if(!$sg&&substr($Ei[""],0,1)=="("){$qg++;$sg="label-$qg";$rg="<option value='' id='$sg'>".h($Ei[""]);unset($Ei[""]);}return"<select name='".h($A)."'".($sg?" aria-labelledby='$sg'":"")."$c>".$rg.optionlist($Ei,$X)."</select>";}function
html_radios($A,array$Ei,$X="",$il=""){$H="";foreach($Ei
as$w=>$W)$H
.="<label><input type='radio' name='".h($A)."' value='".h($w)."'".($w==$X?" checked":"").">".h($W)."</label>$il";return$H;}function
confirm($jh=""){return
on('click','confirmClick',$jh?:'Are you sure?');}function
print_fieldset($s,$_g,$ao=false){echo"<fieldset><legend>","<a href='#fieldset-$s' class='toggle'>$_g</a>","</legend>","<div id='fieldset-$s'".($ao?"":" class='hidden'").">\n";}function
bold($Za,$yb=""){return($Za?" class='active $yb'":($yb?" class='$yb'":""));}function
js_escape($P){return
str_replace("<","\\x3C",addcslashes($P,"\r\n'\\"));}function
js_escape_re($P){return
addcslashes(preg_quote($P,"/"),"\r\n");}function
pagination_href($C){return
remove_from_uri("page|next").($C?"&page=$C".($_GET["next"]!=""?"&next=".url_escape($_GET["next"]):""):"");}function
pagination($C,$mc){return" ".($C==$mc?($C?"<b>".($C+1)."</b>":$C+1):'<a href="'.h(pagination_href($C)).'">'.($C+1)."</a>");}function
hidden_fields(array$Xj,array$vf=array(),$Mj=''){$H=false;foreach($Xj
as$w=>$W){if(!in_array($w,$vf)){if(is_array($W))hidden_fields($W,array(),$w);else{$H=true;echo
input_hidden(($Mj?$Mj."[$w]":$w),$W);}}}return$H;}function
hidden_fields_get(){echo(sid()?input_hidden(session_name(),session_id()):''),($_GET["ext"]?input_hidden("ext",$_GET["ext"]):""),(isset($_GET[DRIVER])?input_hidden(DRIVER,SERVER):""),input_hidden("username",$_GET["username"]);}function
on_upload_progress(&$En){$En=(ini_bool("session.upload_progress.enabled")&&ini_get("session.upload_progress.name")?rand_string():"");return($En?on('submit','uploadProgress',ME."upload=$En",SESSION_NAME."=$En"):"");}function
file_input($c,$_k=""){$Vg="max_file_uploads";$Wg=ini_get($Vg);$bh="upload_max_filesize";$ch=ini_bytes($bh);$Ij=ini_bytes("post_max_size");if($Ij&&$Ij<$ch){$bh="post_max_size";$ch=$Ij;}$dh=ini_get($bh);return(ini_bool("file_uploads")?"<input type='file'$c".on('change','fileChange',(int)$Wg,sprintf('Increase %s.',"$Vg = $Wg"),$ch,sprintf('Increase %s.',"$bh = $dh")).">$_k":'File uploads are disabled.');}function
enum_input($T,$c,array$n,$X,$qd=""){preg_match_all("~".driver()->enumLength."~",$n["length"],$Rg);$Mj=($n["type"]=="enum"?"val-":"");$sb=(is_array($X)?in_array("null",$X):$X===null);$H=($n["null"]&&$Mj?"<label><input type='$T'$c value='null'".($sb?" checked":"")."><i>$qd</i></label>":"");foreach($Rg[0]as$W){$W=stripcslashes(idf_unescape($W));$sb=(is_array($X)?in_array($Mj.$W,$X):$X===$W);$H
.=" <label><input type='$T'$c value='".h($Mj.$W)."'".($sb?' checked':'').'>'.h(adminer()->editVal($W,$n)).'</label>';}return$H;}function
input(array$n,$X,$De,$Oa=false,$Bn=false){$A=h(bracket_escape($n["field"]));echo"<td class='function'>";$xd=driver()->enumLength($n);if($xd){$n["type"]="enum";$n["length"]=$xd;}$Ei=($n["type"]=="enum"||$n["type"]=="set");if(is_array($X)&&!$De&&!$Ei)$De="json";$gg=($De=="json"||preg_match('~^jsonb?$~',$n["full_type"]));if($gg&&$X!=''&&(JUSH!="pgsql"||$n["type"]!="json")&&(is_array($X)||!$_POST["save"]))$X=(is_array($X)?json_encode($X,128|64|256):json_encode_exact(json_decode_exact($X),128|64|256));$zk=($Bn&&is_identity_always($n));if($zk&&!$_POST["save"])$De=null;$Ee=(isset($_GET["select"])||$zk?array("orig"=>'original'):array())+adminer()->editFunctions($n);$c=" name='fields[$A]".($Ei?"[]":"")."'".($Oa?" autofocus":"");echo
driver()->unconvertFunction($n)." ";$Q=$_GET["edit"]?:$_GET["select"];if($n["type"]=="enum")echo
h($Ee[""])."<td>".adminer()->editInput($Q,$n,$c,$X);else{$Ve=(in_array($De,$Ee)||isset($Ee[$De]));$he=0;foreach($Ee
as$w=>$W){if($w===""||!$W)break;$he++;}echo(count($Ee)>1?"<select name='function[$A]'".on('change','functionChange').on_help_value('^SQL$').">".optionlist($Ee,$De===null||$Ve?$De:"")."</select>":h(reset($Ee)))."<td".($he&&count($Ee)>1?on('input','skipOriginal',$he):"").">";$Nf=adminer()->editInput($Q,$n,$c,$X);if($Nf!="")echo$Nf;elseif(preg_match('~bool~',$n["type"]))echo"<input type='hidden'$c value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$X)?" checked":"")."$c value='1'>";elseif($n["type"]=="set")echo
enum_input("checkbox",$c,$n,(is_string($X)?explode(",",$X):$X));elseif(is_blob($n)&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$A'>";elseif($gg)echo"<textarea$c cols='50' rows='12' class='jush-json'>\n".h($X).'</textarea>';elseif(($Im=preg_match('~text|lob|memo~i',$n["type"]))||preg_match("~\n~",$X)){if($Im&&JUSH!="sqlite")$c
.=" cols='50' rows='12'";else{$J=min(12,substr_count($X,"\n")+1);$c
.=" cols='30' rows='$J'";}echo"<textarea$c>\n".h($X).'</textarea>';}else{$qn=driver()->types();$on=$qn[$n["type"]];$Ea=preg_match('~\[]~',$n["full_type"]);if($Ea)$eh=0;elseif(preg_match('~date|time|year~',$n["type"])){$Lj=($n["length"]==""&&JUSH=="pgsql"?6:$n["length"]);$we=(preg_match('~time~',$n["type"])&&preg_match('~^[1-9]\d*$~',$Lj)?$Lj+1:0);$eh=($on?$on+$we:0);}elseif(!preg_match('~int|vector~',$n["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$n["length"],$_))$eh=(preg_match("~binary~",$n["type"])?2:1)*$_[1]+($_[3]?1:0)+($_[2]&&!$n["unsigned"]?1:0);else$eh=($on?$on+($n["unsigned"]?0:1):0);echo"<input".((!$Ve||$De==="")&&preg_match('~^'.int_type().'$~',$n["type"])&&!$Ea?" type='number'":"")." value='".h($X)."'".($eh?" data-maxlength='$eh'":"").(preg_match('~char|binary~',$n["type"])&&$eh>20?" size='".($eh>99?60:40)."'":"")."$c>";}echo
adminer()->editHint($Q,$n,$X),(count($Ee)>1?script("fire(qs('select', qsl('td').previousSibling), 'change');",""):"");}}function
process_input(array$n){$t=bracket_escape($n["field"]);$De=idx($_POST["function"],$t);if($De=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$n["on_update"])?idf_escape($n["field"]):false);if($De=="NULL")return"NULL";if(is_blob($n)&&ini_bool("file_uploads")){$be=get_file("fields-$t");if(!is_string($be))return
false;return
driver()->quoteBinary($be);}$X=idx($_POST["fields"],$t);if($X===null)return
false;if($n["type"]=="enum"||driver()->enumLength($n)){$X=idx($X,0);if($X=="orig"||!$X)return
false;if($X=="null")return"NULL";$X=substr($X,4);}if($n["auto_increment"]&&$X=="")return
null;if($n["type"]=="set")$X=implode(",",(array)$X);if($De=="json"){$X=json_decode($X,true);if(!is_array($X))return
false;return$X;}return
adminer()->processInput($n,$X,$De);}function
search_tables(){$_GET["where"][0]["val"]=$_POST["query"];$hl="<ul>\n";foreach(table_status('',true)as$Q=>$R){$A=adminer()->tableName($R);if(isset($R["Engine"])&&$A!=""&&(!$_POST["tables"]||in_array($Q,$_POST["tables"]))){$G=connection()->query("SELECT".limit("1 FROM ".table($Q)," WHERE ".implode(" AND ",adminer()->selectSearchProcess(fields($Q),array(),$R)),1));if(!$G||$G->fetch_row()){$Tj="<a href='".h(ME."select=".url_escape($Q)."&where[0][op]=".url_escape($_GET["where"][0]["op"])."&where[0][val]=".url_escape($_GET["where"][0]["val"]))."'>$A</a>";echo"$hl<li>".($G?$Tj:"<p class='error'>$Tj: ".adminer()->error())."\n";$hl="";}}}echo($hl?"<p class='message'>".'No tables.':"</ul>")."\n";}function
on_help($Im,$Al=0){return
on('mouseover','helpMouseover',$Im,$Al).on('mouseout','helpMouseout');}function
on_help_value($tk="",$yk=""){return
on('mouseover','helpValueMouseover',$tk,$yk).on('mouseout','helpMouseout');}function
edit_form($Q,array$o,$I,$Bn,$m='',$F='',$Lm=''){$rm=adminer()->tableName(table_status1($Q,true));page_header(($Bn?'Edit':'Insert'),$m,array("select"=>array($Q,$rm)),$rm);adminer()->editRowPrint($Q,$o,$I,$Bn,$F,$Lm);if($I===false){echo"<p class='error'>".'No rows.'."\n";return;}echo"<form action='' method='post' enctype='multipart/form-data' id='form'>\n";$ld=false;$ho=($Bn&&!isset($_GET["select"])?where_columns($o):array());$ac=(count($ho)!=count($o));if(!$ac)$ho=array();if(!$o)echo"<p class='error'>".'You have no privileges to update this table.'."\n";else{echo"<table class='layout nowrap'".on('keydown','editingKeydown').">\n";$Oa=!$_POST;foreach($o
as$A=>$n){echo"<tr".($ho[$A]?on('change','whereChange'):"")."><th>".adminer()->fieldName($n);$l=idx($_GET["set"],bracket_escape($A));if($l===null){$l=$n["default"];if($n["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$l,$vk))$l=$vk[1];if(JUSH=="sql"&&preg_match('~binary~',$n["type"]))$l=bin2hex($l);}$X=($I!==null?($n["type"]=="set"&&is_array($I[$A])?implode(",",$I[$A]):(is_bool($I[$A])?+$I[$A]:$I[$A])):(!$Bn&&$n["auto_increment"]?"":(isset($_GET["select"])?false:$l)));if(!$_POST["save"]&&is_string($X))$X=adminer()->editVal($X,$n);if(($Bn&&!isset($n["privileges"]["update"]))||$n["generated"])echo"<td class='function'><td>".select_value($X,'',$n,null);else{$ld=true;$De=($_POST["save"]?idx($_POST["function"],bracket_escape($A),""):($Bn&&preg_match('~^CURRENT_TIMESTAMP~i',$n["on_update"])?"now":($X===false?null:($X!==null?'':'NULL'))));if(!$_POST&&!$Bn&&$X==$n["default"]&&($n["default_sql"]||preg_match('~^[\w.]+\(~',$X)))$De="SQL";if(preg_match("~time~",$n["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$X)){$X="";$De=(in_array("now",adminer()->editFunctions($n))?"now":"current_timestamp");}if(preg_match("~date~",$n["type"])&&preg_match('~^CURRENT_DATE~i',$X)){$X="";$De=(in_array("now",adminer()->editFunctions($n))?"now":"current_date");}if($n["type"]=="uuid"&&$X=="uuid()"){$X="";$De="uuid";}if($Oa!==false)$Oa=($n["auto_increment"]||$De=="now"||$De=="uuid"?null:true);input($n,$X,$De,$Oa,$Bn);if($Oa)$Oa=false;}}if(!fields($Q)&&driver()->primary!="")echo"<tr>"."<th><input name='field_keys[]'".on('input','fieldChange').">"."<td class='function'>".html_select("field_funs[]",adminer()->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>";echo"</table>\n";}echo"<p>\n";if($ld){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"])&&$ac){$Mc=($ho&&($m!=""||adminer()->error!="")?" disabled":"");echo"<input type='submit' name='insert' value='".($Bn?'Save and continue editing':'Save and insert next')."' title='Ctrl+Shift+Enter'$Mc".($Bn?on('click','ajaxForm','Saving…'):"").">\n";}}echo($Bn?"<input type='submit' name='delete' value='".'Delete'."'".confirm().">\n":"");if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo
input_hidden("referer",(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"])),input_hidden("save",1),input_token(),"</form>\n";}function
repeat_pattern($wj,$x){return
str_repeat("$wj{0,65535}",$x/65535)."$wj{0,".($x%65535)."}";}function
shorten_utf8($P,$x=80,$dm="",array$xj=array()){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$x).")($)?)u",$P,$_))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$x).")($)?)",$P,$_);$x=strlen(isset($_[2])?$_[1]:preg_replace('~\n[^\n]*\z~',"\n",$_[1]));return
highlight_matches($P,$xj,$x).$dm.(isset($_[2])?"":"<i>…</i>");}function
highlight_matches($P,array$xj,$x=null){if($x===null)$x=strlen($P);$H="";$E=0;if($xj&&@preg_match_all("((?|".implode("|",$xj)."))su",$P,$Rg,PREG_OFFSET_CAPTURE)){foreach($Rg[0]as$_){list($Im,$Tl)=$_;if($Im!=""&&$Tl<$x){$sd=min($Tl+strlen($Im),$x);$H
.=h(substr($P,$E,$Tl-$E))."<mark>".h(substr($P,$Tl,$sd-$Tl))."</mark>";$E=$sd;}}}return$H.h(substr($P,$E,$x-$E));}function
icon($pf,$A,$of,$Om,$c=""){return"<button ".($A?"type='submit' name='$A'":"draggable='true' tabindex='-1'")." title='".h($Om)."' class='icon icon-$pf".($A?"":" jsonly")."'$c><span>$of</span></button>";}function
copy_icon(){$ec='Copy';return"<a href='' class='jsonly icon-copy' title='$ec'><span>$ec</span></a>";}if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");ini_set("zlib.output_compression",'1');if($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
decompress_string(',c(<UcrZ9FloAdLIHT,e^12P>vJpDt~s)jpQ5n*/jNlpj4]q19=eVp+w8a%oBQUTAw)IeW/u7lvs|iu0YUhhis?by`^2y`w^^.@t-OS(=WOR|CP&H43h,8fIox:9}+Dr41T<m4m%{PjoXQ&uX>x]gL$Cv>O&?K0e2t6KKZc]hR1[7PmhM1DHfTAWT:%[6bp<T/81fB
.r29*O)#Tst!3PTs7R./CjgEHA#(jbnyfb<iJlUvk{igwY622G;UTZa%AjWa=un-JD;P"b3c<*O>GW;|by+@<eF.,aIW0o9w#7A?P6hx<(,;THCs"hN+JLZ8"S8uS$H?@Mn-Ex6P@.*Y"8*e4u2{IWt{GJ`8QH/*!]T>-TFc9G$kOi9`vgYxwn
c%g)X-E
)EzoS79gZ,B^(JY>CV+i%U#DXw(U!jShr7E.,O/H=?y_RTf@C
um+TP6JsRX*6UHOW)XH&`0uU-%[B_5b/PGK%V6F"*VOYlO%r~e%7r4zIiZe)OGZ+6pFLA:V,Br4Lp[>S/$K&z12m)VZY9)vPI5?F%YP7Ogq>&-5];3(c`Imh^fc!C3hse,H43n$0!iXAQqbf`(tm1orwwDZb+`#pZ;K#kLB(y*uQDg@<,=2_=(-jcNo-p6l/mQFggd1JnOB>B-p/=ZD<=<t;t.KXa4sgH)Ltuj>H,".5g`>k8G`jTY]_-$T]X^Ytg4B+4Pe5dn[0Jmv0vEi+(l~M}[
k-y`fw,^#?q2G;jQ#Se!4D;B@:uF+ui]U<[N^fhhFUN5c%$N+yT,Q9)A"}di>"HNulNmg7&eH$fncswB:21#8WH~dZ.B4u[RQYCj17"f:w(Ftt+[2v*CdE?AKm]3XAUA/"$*$Hx-`6G@)skomr,jBx3je[fAY!+&;z7"<6tq,{mH[(s
CZ.eg*0.$Z[ctG`C;2yvj};T_:TBs[DdMHH;"3prfxZj3
U3e%Z"
N>j@wO55C[8"f&k2gZj[Q:m;xHD7ZJQBzlbSC;1B87#?eSY06Y65k!fOU6_!*Wx:h2q-(T&.*g0OSNSp]kAIgfoTd7i*{J1gg
0YX[M%&kFnF!owS$[QI4)#U=a"[;5oLjT;#D[I1sFRw#|h/g4eM:m%T,SUdq_rWvCQ+@=3hZVZUnr82v==?Rh?#bz&o4$U_6N6)0WPGCy>@WB2tyW9Rvy4j3+.nL(0e:5hy9?UE[27WG9ri*kXwFC-:iQ$"0?d!DJ_A5FHzo<^X+//:a;o|-:)5067zUG^N#rr$qI"K3Q0Xdjg_CG:s(Arl+%Hr)}I5K}9[CJw8qYgb"JY|H#,CZd;l8ef/9hi$fBrt3InM_uo+%kThD;YF5R-(i$n2Q6WR3DTRd|b"9d;7y~"Gt0x]NewuUp^RT$]H8?OEUD4t1{>B?u3"c&.2So29(`lG5I0F?J]*Ai?TgR)%y}q:BvlK%7lqi8N8aATIByV*OrWO,U-__]e!Ts(.tI!v[=1g-0$D:2Zl^e_7PF>e?,FN4_&RsZ=]c-:?ICbC&Zk+vq2F3iEN9cq*##I=Fy@N>$2/={hUbQ[Q%
9XvUvMh<Njh(4i^u_0%RI(<Q-([F
JO39W9H8brm;?_!1ov4P:i,suDlpy`5!*.#@`/c
*=R>+2ZO$IAog/083.+hmP;I|sToi7w=1k},|0v&jyZf8,e[cHgD[^S,#pE
O7O6$7bWPM&P
#{mOOuY<0N=0/}8;_1*pPz`fF;d*^Xs~E`I$,E.eJh-}gkVW#(ZgifL&ku5^Um"P9?lD/t%YRj1`>H)-*Z(uqlXTp{l|6#"JT_-Glw*koUuh"1oVQ3c:q`e(Ou;1f.ML1?
+s*ZYj[Qyz!@k10A9>P?:uQy3r5lp6SS5Cw&bAcsPdf-?4Pn/lw(zfj$CIY**c+;#Ey#G%A#-LO9qG`;!Q$d?g/RT;.;#Jg-&5#c4+xKX>3aOGIOK5g.1&i1qC=5ctCP5w*GWOJJ3nBlcOUQ&ghC^]Lfk>[1IvfA+@Qnx7x-2e`=V->-`sATlbT;jm&Av`@(2b)[HuUYwi.d@B;[+5H-g?od@X?Ftr1dMb{L5LqO5[MV~]!0.@ZTZ+x,,Q$BARIm
h@hLPR&jgL+kr;%KQu*a&qK*s5;#"v?e%lK;`wo+J_/6Zl%Ks>:|B6rsAu]AJb,A8>>u^neEbPK8e%q{,OYFf,eK2W,e;=Sj&DsZ;rd*+-(ISV"Z<#!
I3)4(/n($*PXlC3"juk.,g[eCju"B]4)(!8
VPkw&`](PnO=i*dE[v51@.@.;&1oaqDR?T09$atN?[#E!0dVODQL/,
BQ{+-4SPW1y""2?.e_JUNua[{yx/]wYvW.gva;M5Kjr^0`w34l^Fz5"HnDC["&M_MH;x3H((+I1?qfqFaH^Dkrty?ZJ]cxZUg3sjkeU/hBvu7)bV^l}^}FKH}:=V)3p;X]T&5ez"GU-^zA@e}K.0,*0/MI^*Lju
2LFaQ"h$(F8fG[_UE)@YLWTw|G=t#[$0E"T)@B=ic;G+?K(Tp$Cn,XyT=ksXNR%A?9z@CSo2]8jb8X>VC#r$}hCoey#e?#n4J-XScK<=W4(k"xtQ`4/kEOsp;H%"k%)8^D2Z123^V%v7=.^EUBwvU:u^Vxlfbtq8-iQ-]7HTcxYjtn?y-u0GTq
wp]^pen
U99`GHj#xFV5MZ%|Mb1:f!McteBgmGctX_uWcZh0yj
-BagRa~aVtIohyF!<DR4Vi8I-a%1FHOiTx1G{?,a.vqBUSHCcy2]/_8k_pB:)0i:h27GmUes4E5LO7V[{uza]nh0MRLc$S<l1/^fkBxj;[)z(DAz$#351rq/r;rEdyx2F/-7%HgbAO#PQvW"HJ1j928ZIG]H:j9g0R#t!ylx;w>w>L/D^.}Gec,dWBgw}i{:}6J+JWK!J2<WI
jx*7H]F[_LodM&3e2V9ClhnH=,#m^MrXCK$==0"+vs,8Fu2^iB}Df1uf5g]2:3tw$x#Mcb{0uCjJ}DGbxZefXH0v
VmmzSOrX?`2%D;)2HK,D9HsXy/-D(@kw?LL*1mf1]TQJ&K-.G)37rIQtuaI&!EF]MjyB5L4k$
sA){bj
SJ$<v0gaAU*Z+=*N`"$xR]y/jlpTj5%6Z0
Z2N[+17YTn!;Btc8Lgk/Siv[hI&on{4Vq)h`rM%LC#x2qih>Lttn-b$b^H
G?G-BFJ,i^
WoKzKO*9JgIyk+#V8.tNx~lh-rc`^4:OIv7=TQyc[8s!wP$IEcfCO$oHtQq+y
5m]^w@4}xOw=jqWP62c1Yl){GG6!Cy"::&FxJInJ-ngn5wksWC48^r;7AcS6KoKne(t)HbCElAdGR$V.3)=[y]Ow#_ai]R$*a}Hz7Ofy"J^l)bw"&@bpAh$F/NKht0@|)zwKoE`)N<O32$?-BgND[uv#=Q-ZoYD|cjeCZ@
|AfVIMj1/+o*,ta,vh;0fWp<eP|%rr<5%105iU]AbU25gR=Q9hCO1thcwVPosG8aOCG*]*d7gu5n4?Yuz_Uxn7t>#yZM.3t38D~Bl5TLlUC?p3tkj2/o-^l(?=zxU
&cpoJ7w4RD;0*X"eL8Bkw^MGYA,[w(YEZ>3>Z)qJ->CJx4V19I]wRubX
_w0Ie3OfT/-9";*19V@l0I-/J/LAym^m&Esv!b9r;rDh[Dm.&a1%fvQ|BYH;`@Tk4!n

v,A?3Hi)3MFc6j6ywXs.%8QTf@8H/3@xo*b]|5zwG1+N7bXn<h++/,3@H%=%yedr{w4J+c:Ope$URGK6UJz+CA^DL/[/GQr4a(Yl;^l`c`6Z__M]e]d>Ix}YXyDm/jH^LgAH0b"<|;*P<@8mask^oIs-bh!G,AqnX*(&L^wXEPx^G2=ATA30gwZ
AM~^5Cz)c`Q+fTVlGqV(24fdqMJkcc
Htc/oh(x^Ho6eVS}4n7l2$Jgl^f^;(vtCzm2m^uzdK2h=$/h2fIx:.tf9Z=VeHNay!XBR=EfYV8s[$x"@?t7!mN8/27#QR^HWlA~[DGw2HCKLlixcs(H>YW;4d8L.(#L@2#W?ej",!%Y:Em6R5
[LDp9.MRw+zi45/lEsrgr&aZ"J`_Jso-&sV>dc{Sg+)Qwm!)k:/a(^#+aNxos+>Nx5UT9Na+YC>lUl&Nndk35j
"sF$v=4|#e:<IaPI+33.`8REe<d4rWs*%:33sR7#ry^!jzVrVoa=+>5bBND8`4+?AP$X:m/E5")Z?@#|5%V`o$^bYCRXeWj/a[x%n{
KLVWhANf_5*_aTkgtBZL5HgL!F@XdOSn.*m#*-jM`*v"8sfTaf//F)kEF4(EWc*U*SLZfNNU2j4j`s#&0Pd)hS5#]+b(C]Jf*H9ekiPhOPS3HJYSAb9v-J*4w8YqF+.F}W0!v,XO,,O?Aal_qb3jxt3nlV{X~
FMxK6`a!sMJFIja+FhrMGMZ
{Yy9v"WU(?s
T&1((n{)}/TcRnu?D6N5c]gUyXN5O9$J#Gs1mG2>Un7H1y5NxEx0
g{JsUdtLh%t;E3<~?=RK%~jH-5vO]~;w_{[ygo.p_yb$@_x/O3Uu8FGQ:?Zs6Q5PM[TSpW&=_%L[NJC$-*y,ObV_#NJURX/}4=d{C$$7]q-$.SLFX!w*M.OcBtmF5,SFx@lQJcBrI6wPAs6iAG4;tjveo)');}elseif($_GET["file"]=="dark.css"){header("Content-Type: text/css; charset=utf-8");echo
decompress_string('(Osc*b3V?$d0U7vYy8!&<6V+#5Y(O]{_E`f_Nnud89X^o)QQ3qBMrdN46cZSQwh=;$0.!*Zw(S_-i>;II-}+0!f6`8I9QVZ;o&E5[c=8273*kW8ozGkc^+xN7#Y.@kai=uf)pXra4.tvBDppE(0O%pyGdHQ;9&iXKL5h"f}sTF^w<u+N`QqVkc//{&C#v2fr*He5[n^S~K}LsnolvKKV$0UQ^5Q%aL,,EO.YcxHfUAFE[fz;NTDXihkd+*v)=*:H<h4m}7:MZ$(T9?)fvQe/+rs-@_!6s.%kFS8BlkTM7:.>T!T1a_Q]r,{G#1K#@DJtTWbKDhYs+d&f5$
e2r-x}$$E]x1jKb?*hC:U6#$nP!Yl_fhiNpqSQn,rFAH^Ej0WL6iVbFm)Dly<^b#KHc5d]rDfY=:)ji_M)GclRh68dd;^5K<_T5a@_N~m<-fPk)n,Yt2%c*H]%J|-hp$0S7[OX%#$N4]]QP3#il!.zdMS9b~OTUbf28N;.2e#?[uF^Zs-}.fOTD2kINN/_9i>)k^J;)<,p0Vjb7{tkV#@~3e%r)x]^Rga3?&.ot]hsKK
0RvY8Rcd$eg%SZw7upRx"vEUKc1n8!TO-X!gT-)*AjLt.nkxls<V_YmQicVt-sXf%[@j#pX#mg5h9(
>WFWD=eQShDcxKsdG*FxQ|on**[[%wssZ|,@a(y#A:>EurcPU7t,+8`@x02UbWotPkWM72h9$VBmG7q6Idd1z"B(
HVL8"i4&o!o;@W$S3NSF
!BELq[+mkj"L<1^->lc)Q6h?EbbC(-no]nk$(P8TBDBS#qatq6(vP68>:4v,f]68(fz%qby9w!D")/mB?*V6=xmTa%JY26w{H/DA0;97<0skOU_*7{taCF#q1&FBZ[^a&(jOS}tC&kDpe1T6J@z)gFAJr|[sNFt-OPIbHH>j62GOYJM]Oh,VmV(uW*f+C^.;Jx1(Ax^ue6[u$/O^15L%NPP|#[ukll8d_fwk[a;
fjC]c/-/7w&n');}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
decompress_string('!]b]`nsZ32#tW"t=D^f-cSgTKbHU
`OQ{E!:WmYA8@oVFA!5]8%:/.^",RA)Ub_b@nC";#1ls_vRz_}Mm@*^+?S
d)r*3/j:]J$kH:(B;r<jgc|?(dmI@U4)R3n2kL9UT1gEMGg]/WVrMy$/EqLC4/]M~)2pzyAUwk;F*6iAe=Z6}eCL%wk`d_Z,x
a3Dp_48d+,{nQpcZlcB>k2`oF!PH<_u`fkthnQm[G6(H+d_s37zD&)Wj&a/
Bt0uh54EUb@Zr[o):P]Fn
n[JJW(:>>g%@bjvV=7ZbycmJIvr)$.PLTrblmguSSx&2wrv*ScLO<`%=
Sc^(o+VLYb[l7L7Y[d
e
9x0UtD^Q9o}w_;[0S&p]GYqp98YN*XSAJk-`m8}Ja<e91
a]BTN[9]L:cDRf+BNloiH;si{,DA0rBj
UNXpRyi<n?M"D};Gk_m@-nv<GCm!_qO/
p()/cK<Y|UkyUS;4m^(LzJYmV[hMQyrSLskLVl_JupCd!34h;q4x"XaXut5ygpFSt]"+"fz^(;yyE)C5sb3BQei+:m"hjL1hq7Qu4p$u_=7/GdV12T_norT!9nVx$suQ0;G0K@tan[<mk:Yz%ooMsJ;)J%~MQe0y3Tri}/2XU4;*Uv<0,,e+Y+yEj7*)A.!pH%<M6U0Uk#:$6E57
DS7#)]=We<7$[yhZV<kF`Cx3[nmQ%s@|X#e3:<r5VG<~%sNnDA9(vjjfcNT@LLb*>"@?hk;<VH/^`!U6VX4NnkIUN!>}#6v^Zn`i7LsCjW,8hbmT,NdO0CCB^0-R]5*Mow9-GAv!)9Uc+[RqooDSs9Gn/-<8G4]QUA2`AL%,!|!HZet<,pk$k(rQaum?K_YP0NLelj?4ZtD}t_E]u/."4MM|s5S*fY.tgvVjyYegI+IYF!m]+oGsIecU;"1M7y=2x,Q^<EBDnQQJk:p
/T=C*G*g%i3|;:`73~dWY90x@dVS@:$S3h;$Cl,{sy^`Q7k,Nk3z:#tWUTB,q*3Z4q<7np
UPK)?=#,`9mANW%&`cOfDxT!?x&
*gWa892sog;MjK,gLh5#G4CSw5Q?-[r1-TmTGw4V?Ho!eXC)&ZEJ7F3RsoEm}W8uR]fLf37gRVv!]PQ0E"qN4e)V*pgu)"^z!s4uiIum:(sTX"cD-W)2/"%EVROBl-3*8ur6Yc|N=yoN79@"(svOEEQKLqpx[[!$rNPd+]4Rt:Dz"VTx8OhxcTG!hg*%;m,/5.?Z*pFN=)*b#7
9Ju,GqE>@&Du6qx66Z<2dei2mjcpy7;9n*t~u..k."Yf6r!<n0V"iE3T;-oZjQqS3i4Dd)`S/w6F:TZoBO#)D=IzG#MiNBHw,XH}(&-ltd)Q%Gj|UGa(sGJC=1?(faK+T[Tg<Ww)1w,k^j&utOIqp-s1M*nAq]Gn2IK7TZN%3ry0`b827p-YVg5/LdQY-*W<7Rx-:n*UM*8~X$/[5hnP6+x
U`w~aZ<yZbyC_s(a3f75"a,@EMIn6CVo8_,88?K6^exjh0/WtwKR@|1aOFfDgKPW5N*XhZHkF2$Dn%NStlp593m+`[WM&"r*ElnA6h5|kD1VIMaO>=g51`Xp.a$oLUB/2cs-&T/lq,`/Bm!|]-.SKsmE8
8VA0AZ*^C=?UX^D3$se(nfD
:IBR$`VYR%`lV!n{<1CZp<9Lqw:9(jIpN7A/I`$&?z@j#G3:Yr=]0h
EUM:|WoI5p
ZA,s)dx]#ai{x.g2@z%}(b&a-0
3l23J:E%d("5~`E1puIJ7y-Q#8zcI[]E3LA6fbFHBjNcv"KYYN,b0Tq4/BJ)q,lc24r.N
glI
t$>4p[*]tM3SChr_xpe`Lr.a8hP8ar[wDi<X*"=A*h0wtGK:hIjl]C%2*%_u3xO)20r+Ia^^,_JwpCdR+H_!C4q%=jTV
)R(dQV7@"cLg7LtufnQG6f&mcg+:gF(c"t3Cs*W<Nyws,O#Sv|VE8yG0hY]>REONb]F0BokTvuO4Z99$JdH4qmLx1OY
yx$A@K=oP/i8ljb;GbmO^CXao/J%fc,QJtle9G01]81hP?[>y+FP`NG^N1J.lPPJxYT%D[[%eSd.=W;E)y*$?Qjp5^-Xu.t<;},kf>p!`PR3s$Z1t^PopL=[JT@A)W
~YvT,ISw,M[j5"bJ<=eM=NxP)@4IP6}<g#@kdu_F4#CA&V|AD_~7BwY,.d?LHjB+`W0ht8&[-"b##NJ-V>>=7DTjn5?C+uq`i1+L<5
TIX]
m?r67.&nJ9zx`9Ne9N)s`,{VPuLpaM$%0``Z(e7xM734[X(C~T+Gktqn:fT?@Pc^eA7u;6;ez#W%O*U=5b8nr8UZEEM:ja!-
YxO/kprD#QwCNO69pt.@FJ5G"C!jAP8T4<[<v
o`wnrLpo3NdC`-hlt<!S;)?DJ%n~`xNV9DF66Q`rR+P|)j/Ijs@8/3rc%LVjII&".PTx9!KLX*/Zm=4L/)Y(0DThSUl_+]b_!%8}b/5/T-tEGPX&Vl24P
2"H(xb_HFn$!OVcN/1S%KFf7:3C%UR)v[uZ#[NDFE|B{]6_dQ)PTt9dU@i4`pm2bX4LiqWY)!%lje.K=7"OM1vx_V-KdAoINfWp*ZwO).3e1=g07(APZHU&hyECZXXqGoo&oaEA0emN!r
&^QW/<
K>]ub3&c/lbuqvWEj;iBIY
_pGBV:LK@"Qm"aor$MayBb4RS*F`Al;SvUsj1f[^B$%.5#=j]86*R~_nREK?=L:6JFjgT>jJ!u`Q(VU2rV%K_8".40T8kd"kxn(q*X;&"f-D5JD*g!Qzhp[Ud*<T
P[c<xjK"0jxM/&m:TR1N$d(`;N/mArY&ZCub!t/XVG;ZL0=Vq"/Wad~m*5w=r+`[+<Pr<b4*u?z^J;]FkP&sq!@SIL82ye`TDUPhZrk-*f4Qwbk:)9*R[_#1m1_)3o/<ENlMV44c4!)f~t(8XSr#el8:p_Ksbgx@oN3<Z)k@p@uAwAJ!L"B>+=%%!w&+I@[Z3eoFK=kgaM`Pc-UG~0*^jT(.h!tr_=a_NTLk/euq8=
iP>vx0M2*EDb=>lt:#:ad"EK@mK!&NXRQI>iDmgCLo-T</Wki%&91Ioe7
>i9{?[.T`5I<jj7#ufNOb/!C/C5Kt/j?.RJt+8(39JEn`(,-g_SSfuN=8#X`jve3)Drt6SO_[Nb{v!uv"vJr5RFrn>Kr4|&vi1xgwU87*3_9=
AA:b/jdjhvNUtvdP`6nLFiQL>df[v}4Vdwq{[L9Ta-NYaOrWAV!Le2`wG1rs*Tiel@$s!CkcJ)<DU}r"#QtG3`*Z
M"W>8Qu1S#j(xP|
PTj8~n/lfEPH,Vbxk9S%5EL!U_-J&V>,2b8V&2Z7dUH9Im1QZjMG0pGGRLLE
SCYC(KqGW$`G)bO{GeRwqrFhP;^{/uAZGwt[D_w{Ak@0:+Q}hX"1d_A]7/
dT/PV.-rN/QQ2Tv!6YAMiX
VW=h=Sg*)6n*$*]-E0s`P{,-v~CrwI$Fp]=[d%)WGB,!h1AJR(-c"ERDggxxeh_pL#Zd6Sg_:?90,CFyoN$(
xcVRp[+ALsx4lI}Ta:-5M9SSg5H/R!s7M+qyUILe_=P*J"b-P4w7R#KM]orS1
h5>ZjTbit
3j.UuOom3g?_X4Q-m&_<:eZUdG;C5D%8pi-2R[@6:GN`&
81mA6I1(]#*$5Gt9p.MVT:[iY]^5sRGOPJ!VbU?!Zp>gLKrmVB0uGT02`x!,bWy.sfg<Yl>gZ*[a}l?W>p&0%?:(RSf&.;Q1{ASUGQ}u+d
WR/6d_5]]NkzrWxtG.5
o9;Z.[k)_)eOg^v<1{EAY%&~Ya]&h-*U%c@P7(,Zl;M~5T=,c-/*-^bfRb
~`&j|*oRoi|
_?v>Wu_bv8Y=``7@N=bA|M*U4.Jjzf|45RR&w1!
!:r=tMp8OC_#v)EGM1Yw_n!ap$]A{`J+CRid(!Me5;@!9S=
UccJg
?Kq
42dq%_f_.u;S}D|%DL6m`;C^yVAS@c,nmy4tYk~Av-<nB$~uG3co#vu&;pd&KQ`PEE1>0n+OI?&,M=&++m
KN-FmZfhL+E8U2YL`VAFth^d4Mv0r
l&f6E]$4Y@
ue&b7?0ZbLIOEEE[BkMhkHU#c@q5aXBMb_&4LdRH3(#3?H+Xs&)xqc{0i%q3sU7<z@m%jE^S|U^-*l&EJ[G,BphbX2&$jKtF)Gy&A[4DnbZe2iUS5-)+eT2Bj-+bD:frDEDjRqD,$#AN2VBAfWxd4-M?gaf?G:l*.oZ`A.,@8t;eH%2ReQtnUWmcyIyVxl7r^2l.U@D4)pB.H21W#PmDid:>"@h-pPxva#p[{WK/_.PWS/#:c$8BDna![8e_l*Nd;[d08:Gmt1A=Q<#VHcZftj.s{$b9HN($X:K,PX2]X0).DYx81:Q@mVeuTDS9O^}Zj^|_Gg=:=3`P*FaK{+aE%J6Ea#7H.wwc_.;
S#)QiqL$(^LnFH&@R<&)>ChD.Luiw]5E,15[fDfC"j*N|vDqMnKgVy9eV?AiNsg#%H^0U_fZLh,iKb78zdDb6;YXxQ!s[fX0ec9f{y_m.KwL<$
-Wi!tQjfy}5Yd52tq+4.$
LIY1])3s+v6`XY57Mx<J8F]LTD^7xOyi<hxp7r`#Hh(tV]DP_nq*+xYQs*th;bmdY[CjAqW1H`WNU.YU>AxvC!e:-Lxl@ppW$eZ}4`[m;2/?W3TTZ%QRtOu(`L4Lu8tA$LD&Fc;8[`(Fgd>1_Mr
]gkESP8D2c.y9Seg;FS+Y]2)"Dw81:pPey_
K-%+X5:DEgJ%g:N_o-auA<UvH.AD^e8ppd+*OfByyf
;_4"gLicsEj/R#=h>ZTUS;%B$[!h#"y^BPa)m#jE)P~l=hboMBuBguuv;c{C2im"Y_`=ix6F&[a3k[fT+4@@sCjDOF6g5r$G+E&a|[:tC1;5VG=tx3LokL;&#W&,%d0oA)H?}xnR@M<9R11a7sD,@,^lMucPhT}kWBzC#:q7|&Q$_QVuWa{Bq%XVe_pn&B{7/5wA9+~vqmpGZNSQZ,^g(OrCJKi9
Pycb9A!^+2e+JTbLQm,K5jI:/WlZo))M+S%57IQaVcB==|A,a4Mew|m0"WAe
@SK!_rk@JGMUrP*i167A?)XgEw]CD[^H;gW`vN9r,,CMQ/&D#%Vws++BOL5$-ux%9Gs?/ASCa=X-Wx6hgHT8;F&F:FX($rFqDK{;3+"F5(XEPk_#t)42F]%l3LP6@KJ
:pR%fJED-NPV,WFE=q^0|_9K;%tA00h
~HJr7pK=`4Q4Y_5F,"<_Wk=6<:@FJ#P$DgLIp_7f(&W
ux"ye
_E"29*^SBb}L)^`bA
CvEbSL/[nFkwENK,gN6cMS@c
u{#hdr5*ZS`).+2_(?V}BoW_e):gs42ifov!r4kY/P)yN4L*,Og3Of"4pht2bB#^@>OtWn0-Df%xtTm}W$soifY]6/O_0w.9=jw6sW@V++2y+fb?O}I8`8u&7kx_TJ*Lv]j<P<?6sJNg3p:>9$xdp6#GL?u=TO^{,jmK.f";f$J%tHXpt"Nbcn#NPagM@cupRFfT<]+-*{GI&*kP4=5YN~<<Hye_d65<@a2?JG/tWK4m+]0w;f46@cgF&5P)Ao6g0{Rk;f`q.V/e@oKf
/NDtjw;Qi2mM<CO/+Hz5+N^sa7;BSVO"RByUBR}5XOF[46K8hCQ@02`Hn"]7Vvwl2%wmW1wcSycJ6Zy@h+W@2AwR3y[h0tx]_AQGf/CXYP5Qe<&JZD&-":0N)=c%4alPl6`;mx*FN>Ptq0p;g1Y_EF!0+C,V8)g_:>!@"=)UpX3gvMWG&jPfY`n&+vP>U4aw+1t
/S`k-8BW<h<SI`[(`Xd;jRdG;
^?!(uqqn@<<]Ru^A:j2fb7p$ghuu94ZFp?M&TDuJOY,DU`-3<2/,z/KR%0];3Sw`%<XbgATX>,&jLFy;4H29GhZar;:3K4`W85[j^u:@b*>IM)
9o&C7AL@oxp9S8EA"g.>>MT3Fh5|TY24(
f5&9)A2Q4gEqTnb#Ow
WKB3cj8ff?[h/CvL2I$Jx4+h_4RXE>q#@qbCcf-?GdCjE5;$8PB&FJ%jpUW]p-Q(@15K^<[Fzpm[#2QD>D&Qz3yA:ALG*TX0ux1b:[d]wUy2@jc#JU6%eQt[h$Iin=;RVq7nlOJa(aG5)9?63:&9*x2SO[M(G^xc,8Mwo3V)/BRd2X%R~7&9|Ctx,yNLSa~C/a#v=!2ofvn-4av;^F(FEj%Sf]#YVN:N5(@krJfKw]/!q/JDp[9;;Y
gtui:BLAQR@G_AYy0{LbC6792~,?TyS]9*WUN:!:VxG{x*=SeiW?H
Z&j4na?<bLe-Wj]$F}Zw-^&[0z/1b@n"]
x(%ok_Ea]=gI<^2TYPwo9?+ft;Lc
^gjc;cnmx9}1^_`SZ9jlTBP9hu`01-/RE`U
)FRJ(r%hvlxR/OowL+`4}0Rnda<p#Ul>~s31[]YJvp0os$`D!OZlwy^/x@oOd*66dZaSJ//_m*_=xYwsjj:,rjdb.sNrTR-guj!(CI.^<V_+<=s`L9/x,S_5]K,*kAKp"8.RF?2[N<&l6BKkmRQDkvcV-j-J?"G&@crL6N|b4D;pv&IVtVD&fS;2~m(h+#{:|Kp5=ZwEu!^Txl~.B(.1&o5$FM[ROSvGs/ZCp%7P>3VxE
*#$9zv/JX++(id-@&iEI39LXbR$8U
zl`41IbIO
b%p8Bp$NWj!_hW|OqP3ghx*Px*LGH_z=NBWn7HIa0o6F~i$Ho#6"E4Ri@&i6ziQOGi4U.^TdsAwm3Hg2:jrp4%1#YMgW0?Ev)CS"BXa
Bi$+?L,@H)Hfy(nenu+Dz69Nq0
M[?$$fuZCJ+>3?im9eMW_%_#yZxTM?7vD}%[0P_M$
?V`>4s=^Zs?D^aB
:
aqRw%>bAvmQK7[-^.}o|;nO7WaY`Ugq2G)K`hd10qNJv_@pR[%R+55I!@k`s"_UvcBl5H{?/>^DlB*nO:XY`pC!H
$#aj![0u1ynw)?(wLQdvmMHx4BMYwjnHa8`5%r9<&O
)~3h4JQ}?zCQof`J)rRa^6=j2T4(&CV593F^#YsWx$Z/4Kp4^"C4&jPNkS-5*%2--w7QqqDh!Sgdu.]E5)AA_NhHd>F96TZM$_0oIHK{Jo$J^sp^yx@)k)t1Y2%_u#WpfN#H*f^;)PE;vJ@s^FY|)j")L^]`NgAiJ2a>,_afEiahb<L%[uNVrC1gW($/6$P=+4!zq8IkV;CX_=I.7e?R:!CJQ*$,/J4Xd58y_e;Ro%7!Q2)mw
e":nc5v%Qf`Z?Xfd:Sv`G!Wp:+youu6zP-=hm.kGi%_3:a82`[t-nD-B8%4c=SKCTxmmaHBs.zSOr<Mx#rBq7}
bN1h;TM_|k?aN)Ou2JzMsy`s?Ip)KBiJxvTL@Yi0Lh|Z[fn_0<D@3!A]*7*bmEl=jFJJD/iM=ET9,YOxR2i$F#"4G"-kgLf._e]FsL`J4I6n#p~5
fLgRD%N}$8pHl0*!MjWBcMqe!~
gPwTaL{C~TS%Bja3]N
?UjI7{dn&!Y3L;k/k"*NxqZo(HR@4]EO0QYsUt(,!|XC&e0;N7;Vx|)<1.F;i%nRS{bg9/$3#~jRU}8+/9w./kRqOr>9"=7A_?p0ThPrX=!a"Q7m2$T$,6O62QAAU$WD]7kCUPUQGyM}>`f>r>d28GWe0[8tLayzVZe7!OyI+xRBp{BRCj1_I~UVL^2J0*Z`=u7r7]H(=dkR&0x6Y|B&[3
B";<jS2!3-skW]y$AB-Ls:G)XOZU2QYJkxW;KWV
&$?Ex)Pt#4fqZ
2EA0s6{@8Mf8-+26nk5Jd*~jnp_Wk5+F{%NJ6SmI3e(a1BC+s_o2P<CjVU06sRH8UPShlmphPI%.[7
1DnKG11Id[&~cwWQ:^^#xBq[uRk^%H6FL
LfS&,;G{rxQ}XxQ6r)b/I};c(J!5l9cog}j?pOo/[.82y+Qn;Yn7HT1.hj*6ID[!n|W%hW#(6je0ab$UW+33_3f@y)L)t7V7?vgJ%x]$nDLq`;u@WCa+mHm$JTn}V3pHuaLv7e)o9$Z6"sdN3+4^4$,.YvsYZu1i7@
R0k;g71^|6Kf@4x7tY_U9T$][dACxrO
JPZtt^gMPLe:geKfw<5MoKjw2`LAIcb@tJu&LH7R5Ys<k%*F]x,Uw<t*Akqipi!sids/#:.+l3MK<1Qoj8/E@0qQ_lntb<;sFEf>!Y~fL=Msb*mDCn1r!G^auK6UwtI14#[RJ_npL+0d%4%>T&KD}5FoL>M?9@ti^/S1$`<s*<`h^
BngN
2dj8A<)D[?WI?F.>g-X&qVR%jN&yfcuhy%spuruvz!0za[H>!M7W97__
yN8&tfg7xvB*|JNDY8/y=NvK?X8p1RvBQ&Iv6D{"#"qmDV_EjoAb>1}x{K#ceqo0L=lr<Jp^"LLFDs")rXB#1sfB5/uEE#,%jukAK],hv&=<4f&/P(^amoiFSABIMiWyi-7.d=b"HIcKU?!R3!Qt$p0S2YwbV3~Fa4vqiw(^x:}"LA>a8[+Z_,oON#v],8&_p]FJ5l#%_&NT!`%C0eAs5;CImn;HxJi4&ZQ*:O;L5n!E6LwGx(+eq(hTe:d?n$89#5@uHuy:hmKQMDA(TjO)&dJYQQfM-WV1P)^gx6r0Ve1crPEe0-pR%n/@dQ~F>Aiq6?n>)/>E!E*&NBYGhf
o7BCCqv?:?>L9_4{$0hTD9OcT$)`U}X55QTG,$ddm$gL*zZ_9gy`iP4Ae0L`r4GMqIm*@Rstv/$}jj%?#5=HJtNF_iYS[[f;=-g-?U=(LvPSS[xWE8V~cUgpSrkJO]xU"SWAB"$sI7rUTc1"<@*6#n=iru/5u,$W/:#"wW!nd+DwY?saG+f}P_s<-dOi
UHzlVZkEu9`QVtFd57ZkW=J*m0`D4@GD^gJHiPbLHJ;*YO^]Y.r<,VkCOGt7C>>7"k;ey+I9pEESke6$S*gPepmldJq!`<P=vCbQGf*w:lib,4i=aSy&E"VcAG.73G&]`KAr[X>&c4k,}2(QLxhM/&gl$5BeNV>nl)N&9&pAw`%qMws(U?{/vwA^zTZX`XySt.<M6g]^0&xOd=Ok6>|36dQO&ePDKYdU9;12F;!?**z?;Id^c74u;ZfqSn,7V*lm<ULY:S
"VL0f)NH3%XMos#Fb5K*dNg{mFAI:gagdH6Pd:(7]ln`-5)B2ZV<`RU*JiiWq[4wx,>^DTl`Y5TKhQ,^Gdk8Y
%)V0SDB1NEcb8#d%$6^b.GbZg.8tM+pS!5v+9#;Glqxvk?D>)c3o3(-OLp"
3_(BDJ:}gs[@o1IhXhF`iF
o>1_LK+U
%J5m-p.BbAKj`0%23=QoFf`yD[1#VJ/y5`K$>P"E144"qY2fM#JvZXd[WsjG+mt&K$/.8KnC?d]h6HFk3o#QR[rv@)&sX^tD-MRd`~VkR[*I<Ko@,H>s_wrArH8|i2TTd;U9h#u{k=#sT0y7bURXI-/I&#Cfg+.+Bd-*W@h#h>_`t4fU[kCkeOL<l$?w^RExGW/=f~/J,r=%YE:J-HA7pqFb1_/u<$+.]%+)=(`Tf}E`:@
qf}a^badolO`Lwh-uf0$
5[u_/7`(K$<UYHw*g5+Mm.(F<H%o6et%sEe6J*P@)G[WCR5fr/(D[!"#
xN/X[>ghoX8(<+M"WhQ3GwUs_]o-Gn0ZNjFvpN;*v3#&3KBI-sdP*Ls&sPKm3<~oyK9rBgu;1XFv2Q%0OmUQiG3r(^H_]dI,3+7*~!rSx,Ul?CnnDa4.U7mviQ9oj_|;cdq(l6{_bYg3g8b!Rb<-|Lor4E*1(jKUqcNQbq9KkA+key9k{fzw:S2QZqGWc?7MXsX`3$vcEPtkZ]"!R"rQmbpO$k5%GL[z$Tumfo;fA&
Fsj1;"K-(C4C(sD#r_ud?ru5+YJ^GCQVm4rlg}!>T>?]^UDl-fY#3(<h+=TG
~4l%kEq(@$5=@E|H^IVYK#;99Z4QEN+GH#;=Db8]^8]Yf/q<Z:@Y=>4gwAm_{5|Kpx8m%bS_Jtdlbf0u&9%9}(1Z?pm0
Q@k}c_-NkUAK$s-zNxBD/CLkC=rj0-gm!CAyjEa
J~d5Nz9a%f@NlwDnF*Br/O*Yt,.hc%-.;T]g0l:la-vv;ksTg7?3.]&X
.Upr[QM4LfWmzLD;Qswvie+`sR4]AJr)tq6gq>)5[P=>j1>2|g64A(YqtVWhVGARY)K_7Zn50B77]idHYQY:RB9h;7HEdL!>~q}9j5Ad-Crih`c.F2K&h%/[U^MY.(tt=m0kgHEW|6%OaqMsU[NF~`|Mn/fB7]v[r?O?&mxg73co|jNx!@q@Uc{_EH)3&A.T!Ww3Z/rjLfqTZ;zmPm|*s^/]n[{UixmvrpX438UXg%1ZNEk`b(k[6k"QO[NgOF.<+w:&oYYcIkdF1MRk(eIWl!*)mcVC.^kW?4,qd.Pi7Ki^1#s^*K.k]vYYi<xZa@eDxrajuxI#|1*Lx#*Vs^zc,.D1>a4Oic_)TYx#:tj^jZ6HGaG3(.Ig#4(G"DzI$T:%hVs6n(8*Z$|ks)G@a
p6SSjrL!P
7dnKxni&`=}G(Ta.DRId&-wS
m264u%pI7E#;6zaO=^Wf-;"7)fi9L!MHr{sK7MW_5e.(HF84apfaPc)EwptjNcK6M2S/#BKd/209?)DwG0$}R
Ui[0jQrS=IVtVGP_F&tL^q!}CV&;QPdSG>,v:r(M^[.P:*kRv;I)?
L!;,d[w1_m6&p}3lmzFgK(0EdG_0%S
4EZ(ndr3HOq`i;x0O/j:dJwHW`c$K<PT!
f6Qxs_OI(oPe3Y_L)khbx@O.0qrhhbLU63Yz#t4Uo.KT`<|Z192#"G2j7
}Js[-@%yN6pL4;#@12DJvrx68a.nLa$$CAsS~FrvtRE.69FZ]Z{MbS/<:Rte8rqR.[N>^1RDSQ(&+2rTqyckM(`HYG2dEbqMbhcqfFjct^Mhg%ymP0-So%f&4FvA^a;K@@n9`]nlu
JTG]3JQ/""A
WtT^j
z=_yx^E?5V2a2fYDXo(hfx8]X]C9i[2Y`o~r2bm&XQYFx)ekNx)l|2WeViJ>O-}MI`P>;sk#)6XaZ2Lsz
2SMX(-XX+wN7*muu%ND+En92H,~]:R}1?2cimh-4h9w%BJXAIalxj1`q*p+XY1y;HTl&fXp"eIUVP^DfGl_:"@m*/t#fn!3Ga
R@![/k`V}O784*3(~>nPsdwjjhY]tydoswjX%]r
}-tO_+SntrI>3[Qk[,/R2c]m8lItMv(4,s;-ze%lEpMXe=`>0UF<<bx=J?Dx$qIK!WAGI&M01nsm939>uTmfCH)nh.keYiptR,{=d:U*W<5x.C/QOYfY,O3@V0bAZ&2cdNt)_xf5I=o=$-=Q)[w+A"T%fqO?9!CC).;#]@:e.f#={>T`^m/8*yJjN8`c$-S
86Ok&=vpfK]U9[]2-ewMf$#bIW_3Zdiri]AR}auvYco7KaE=q==I/vnyQjA2:JonFMm0!QO_y`3Yue]Zq.B/Ox6bR>mZWR_*78>M/Wcm?:J`%L<,d"!YRs=(jB`p31MG{rwUzPvd]hA5fXc]6:[0
(~w
wZ1,a+<g<5F$7wq+AN?("&_bSo.NR
9hT,QJL/9!yFIOcRJ;I~YVBihywvu&&~Fl/n_&so3p?NW>a$<.6n,qsKI([k7Ge3=Nayr[ctpMwnu"RhGXb{;&m/+#kzht+sih`w42F1XNOX/32z<kP7y
#Qh<;c!PRCO0#T/$0%FMUg>LARsFUCNn6Y^"@;5-v$e]g)1&;0:kDaXYYErWjYJEMN[f_N0/@rSM_^`a4B7%N&"xL/)"`)GHa2sYGGoC?pA58RD+vr8gr!>02wx~FG$1<.Lz4C3Q,cXMkFt!4md<E?x
s#FP_fMpy"_?*rDd2@.4k1#=IJUuk[?E4myz_M/wx_j+)Z(~QFD{-J:.ch)rFh<N#J`Un~7|>M)QQ_wZMu"J!lMY"/w@IJaAey>BPFe:Ff*dMY09fck
8!;qVdecULvxlOwQ#5<>_NHt4;c@oCA2lod9nqq:]-vGE/xU7fj<
HVd6:_GAx>!#X,l%pSz1gby;K2_cLP;#$BRjD3*HB&^:afTmqECAe#/M)
u7<!Ixp7?r:pf<l<n6-J[@~rn,e2xr8P,5^0*84DLQ@OSWNb}-a?{hx(;Dm*uj0knEJ&axyq).?y(rM+`yI%75>!$
j<MY[hNK.y}U$2&Sq>1Eta*pB>gwz;.?+7LkDZ<OrJ(P|Mw!W4EwCQNW*ECd+fX<@]
YMY+Rt2RTEu<!Kx5#ry~V,UR)/f9d:*PoDX~e1V9e-+{PJOI3wXZUP6b#;t)P7%]8sig7T4Gqc:docRqa$c#uz
Pu5Xxr|y@hqt%chw13}[m4e9V2"B)f|)V3j$MrpI>TBl-9DOg^(N[q&m
hW--,OTuM5<Jb"dSgB2MwmAu<b9hS1SB)wvWi~8DSLtCj9`=V>%dox.4SVmbHGb
3-$YRAQ~[&txB4(GTp,bd.b9+ju(p.lJC|
;]6>-Gj^SP,lKWKnY9FB2hn%`#E(XSFO5/u3p5B2GI#N8At/a&bF[Y]Fh*4r3gwHBZT2x:MXKQcZ3?Y":nXX~tBqI]$B/[l55X}d,-A>NN;P|jNE{=SFa6j`B=xJr]s:SF.VK
mJX>A$}O5N"Yekb*}a&G>iSsUyfD5XI]4lE9t<87Z
xqd5LFFTvS|Y1J<+}k9JDh3^%
L;)WJE@yVIoNMCVc|Xq%%<~sWVgEKJkO1O+uR7oyIMU4<cD#:Mjh:(02-uxy[CgZ}d[36%I)zgO7GMmDVl5nW5WP0$Gg<QGig2,c(ooIU<)J=Ovs4FxLd-j,i8,)nnJ)d^#U^,1=GshFvK)5h&h!3kd(X*|qEjqgh#9w#U]x5<RoXYDa7SO?E2<oA*j[EJ9QY@SP]iJ
(V5WaakAZFijXpnVc^pn5)XOr[(H|%r8!6D@/0C@~5Bh-_Z&QYfe,TXL^MaB:([w&sfGMBuwP&swv<yo}nxmvYM3beL,Y_TGM({;%<hRG/}xz[@3rQ=:T#bPE>K$EmbvoIVpOS5v&Dn7G#<#1o1U7-d>6/WdV=#jiHr+v,Em]@9vwe2S"AxvV5rQQOwh}+nn&gJ?Giya)q%$_>A[!,E={Ri0YFbx6flya?<F{i733$BcEGDIx,OAoh:V2Fy"dJ<#mg#FskCg$EEWl@QLUag!yJw.K,!-nVAsB[@0
Jara1wL"2w30W!r-X8
E>/:MGJ[g-mq#o`iG)sN4Vr0pEyp"?y`s$6Qc8(h%)Nb=eYRXrKP"*>e"]LO<64]yVOb@.A4Bx<WPZ1Dgx36=pWF*ESh_woVjMbd)6%@i&EUNN/C-@mVSlA4P+0Xsp$i*r%?Tg@#MqA*|V@J=ju<Sxv:^2b9Y>TZx@leeOzFuXa<:EB.kC4K}gx9r(*bt@nPxe:]c6=j!1eS

:uL`BOSihCw`@?y"YCl[D.U,:;{y/4`gh"3_hw(tAlk+QycvvMkp*<32YOJv*.[_G)gv~l[:fZm=V#EaRMI!w+V`CuwYMyg#!OvX0![D4R4/!D)-/3U!XTj@M50XB](!OEl/we,npx-m<)ehkpyaWM2vC6F?#Mj-9T@
w%/obM*hioe%LCR8-mICksEG,Po<L;Ypy99qxb_bwc4kzX$9@3Y$u2$;!:VEOYlDb5
]
_$WS=7:EED^sdxkW#fPLaw9D$qt(`Odw$0wtPC(%,)wd.2mo]:vMmFM5Yr9)PP>sa3=&h|M`"p]KyAE[ED+`r2yZOG0g$+)BY".ASVtK_BpyIUX+[Z-K1("yk:Sr-<5Up/P+BC7,#Mj~"YO]b+tqX6E4P{plm)hRe+5vIj
~0?MyV3OvAV9*V]fH#7ud:UGSESeu^_#[h4lXAaeFmj)@tLOXNr%WPxUgp9pp9{d-dN"&)z&Zi=bV^v:`W-sIO3&nsDim?NdI-:h!!7/1r^NAI+4n.C/R%Chj!g_{5G[!sD0^=%CFC_s!45i~Np6jC22h;vF-;.:&9mr#wD=6bcL.]XK}U7+No_8[5/kf1=_8)#14u.?I[e(WJif0L3"%j;cjNpdoWFA6Hi;&4##.K.)lm|71CIG"Q9.9F(c5=(qU(>"uT^jgSctt6r%<RwBz#n^D`=X-#Q1(5Td1m|sLbfRN
rFU?1
=;8/*fV.qqC4:&yP{P`7HfWt(7QVFRT*<hwqg[He""!Ke!N-G-|p-1VR}-EVjCN
]N2x~0Ew`o5aRnA2Ur/pO<ltsX&7[R7y@D"_4eo"nLtdP:
p9$.=;M*v/XlQqIII;"1L,+zq0rL,FdK[j>k:kVG?jE{L4.Y
3Hg8|U,xX0i79yC&mk$ER=Ysi$A8lsC^jb^VbS1Gq:oObBy,`R=&iG$*dm!sa1$nn&UNK>Vd4vWnO(JK"L{stC4@sdY5|D7He@5QeKsO7l^R7+|Wni#,%x1?Xu+F_Blq+Pkoh$<US$/n=Ze245L,?Xya.y6lq:9r<$q9=Y&,`');}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
decompress_string(',hk^CG^ZE.5v]nf>]Jce[)I4
vksP"e$sSk._%<(:+l$J@n;gd9=*1
;0,cpiiV]^
gkO1//h0It!.BFd@7w/^+kWb9?hNMRQMN.bo&iSEM2}2i%?W8rOlkwT]kbsGLOzB_[fz%JgXpu"Fn_,RqnWpi]7HD_pQrbYcTt58Bxr]ylV
hw-(HS/8c=Khzj(BVbN<`Mjk:A_ss<!xZW`;k1L1(Ir9KyQg[Shv.upYdnot3mw7&Wp*3y2xmr
uXH.Q[21RT*5I4L_:jMrTfw~)8)?1FmQ
>&tSJ#!e&.hHzCQXaX7vI#z+<jDTt&@Mgw-knFa%K,y1HBJjAkZ,APu!y?)(H2&7vXsuHL3udHxV3GQ$7(r?o/*KBA&Ph3[*O(-OTxVe~OW-vA9w0GQy2>!B[TS<h%gmAt!k&ho*,uynwwD^OHg,6cx;<"y4ypcFr6iF]Fo6zv#7,6[4yt2S=y6Z"a0qxe@M:EG#U1d=OKQu!yWVeEzkiEB_g*3r!%Hax&0)@Ag]MX_eDV~ravc;~JS%CFcU2n0[AT?3sz%xryChU[zTuyB4gUJe=Z$KKH4lEkpR2B-UNE-CO49L~B)d^AD5Np?J<FBT"h)72ujiGBbvVDf`(I.8Y$^0?PdJAdZ:<SGCwj2ANT{QO>-BJ)AJ;tAEA8OQyXpY?,URlSG)"wPU,9Z-.n9`ZWJ&Ycs0ErUs@e%PZMz7JE2>RM_n9H;W
*kk78v!.OiNOnZa&*$0M0jsi)^]_90/qU]x"c0W:mCx~W^R/:fGfnih(U`OuW"M
yoGI`m#&MJ=="M4O_1u@65=vO
H:[cq"EvT:mJ#qO85UHt(4j@xkR()&]!JB4)LBcc]^q`sp1<)l:H9]2}J/_#Tb&Os%<r6ArH4MZ%1mw/]>qAft,aNl(PeH3vZm3dAdpvkpgYQ5Xi
i!u*~I^WOF0rpkQ?QraMYXf;]ktm!RlyWZ6m|g.=`GR<!44I#:0*>[#<p.O?^U.2.9!4D"#7rD%2GMXmMZuk|Ds>ulilzozTJS0FkV::nMhe.<c&#Lv?#w*)x=<unGgQtJBH9`MH4,c$#8l2a_o5&6<wnfr)`Zap7Z<]3tRnIu7jdp31W,_ZjnJ@7P1L;+DGp1Dux2-wzL[XksnVh];"0n)SajnY!W1hwxcKx4eUplEXSe^Pw?hp:bS&3SOb.6?`5cfmnti&J:+u
VXNET7<DiiG`bKyqcX3-F-fC.?C;LTu1bBD2RC`=Zq#dFYudW{j8bj]
s5w:AmoBeR
$Mct12GrWvs7TC+QNf7^_?9??oMyB@6
:t.0*Qfxbc"t%u&L!AXkBa5LmCRhhe1)VSo@K"rT;5%[sL4+F)wV~ohMdk:/e(f[:iPk,qd<xXTOuZf`JP"fk8sfAe9q^eY7Bn@<BvJ)
M-gEy44zCL>Ji""1/.*I!.k*04<ehJLG-MJ:J5c~^35rXz&Z6R>BIt&Fo24q#B`Eqzsv_<A!,;UT>q!)*f*J4,]}vb/R5=y-D#:9d"PDp(UyGA:qY
EeoV:OTm9L0f=#GvD6bsL_4_xFp;uyRky)^"]MkBU1
92|,&09L/@X*hQ
&4G_UJh2PR^U93az]=aPAEjL3e%B>L2xR!3vv9s5B)EcMe
n.dBAXw!Cm^@B7^]$+Fj=nI9r$ltEYzvWx;Pd)>2bqCLII}jl)G<z
^M.QdMg$6V+=(hKdo4WSn],z$<Nb*k),|E*w]RI[q
2e%vQI%M~V)Mp2`Kb)NMX&c:Zrh8NnXkuY
FdVc;m]r`F(itETcAI
5tP<tNqE,e`4=gVa-Nt</`D<D:!L)uQF#e9w,!wUUyqSHc
`5N$MOntFE4?P^BVR1B(.>#0`"n,+.%Sei`9M++2+WX3)n/zj:Z6bZnE)"[:^i`:hX;69v#d%a0c=ub=W_cC2LV9JP(+*!<D@iv?0!=6bE]gUuN$/"qZjrt0K@=O3X
<B>h~e**/4+%*@)_v`>m%h6if)a4C6|()p+E/VAd5RiCZq@hH$d@3lIt]
O`(O@h3cA3t*84yMEbt,<#/fNp;<?PqUKI%X+=PsRRFccl$21e8VQRPex6s<51cVh]djS4Z5q+/@pn?BwQt5b&KE29JsU?ujjsv1A:ZMKv8
,LdF,O~E15:EZ?hrIHgY(C~FC<%tBdWd%L?
zV8WMA4w<]jrnesY;<Ty9oOJoW0!;B5[MD}UjihK:Hc<8yA)"K{?7)Y5q/
Bc
IGnuZ9%"LUZCvt=C/q{>Obz>=6h:Aoc5Z?`E]2oSUMnIMnK^ye7%1Nwdq)oN}.nx;cd+wA:dD9"IaC-%_n:69S]wY5x=P

OpFYK:5.HA]p(ZC
`t>QZv,m#]MML!6-5%C
Gw,HaOELbTDI+o!7jI81Lnhc.PvY+i#C*PIRMF"N
q8$:bxpi=${%5?6j7ofGv(4H;uKErLFVW`%gBadL
,J?k]"YlPWtJ&=dd
7LjBrhDNv=e8p
SSh=%tVbXF4rrgflutxHGb,%xUG9>*xE8`M,-O~:zAR.ap#:sYsN,d-cRw=6U`m=5;aQ[5*,-WP?xX0u(q>2;0aSVU7^
k3lrXj`"TcuX^UTB"gP~5PEjG0BGtpI8yFiZoym&=|xtvo!Q+UG+Xr8(
D4
)HK~Tj,yH-^5vu*lh87dw:2{cdKM-P5z47$
61dRS>.@4xv1eOP)lEy"l19:8zA`=igEg?^J_qL{-M;Ke;)<r?Rnyx4rJmX#98ml^E7!CVcDe@`kqeBqQER8QSV*PY)xj~u)ZIMP2B/yW,9jwI6+
!y8<"tJpJK|wqkBxtQ^b?XOrTj-Ki^Uge]5WCvejf>_3?+jXat@Z<!-?_[+YQ`5=ed%c.b?
CRzS?Xp@9Cv,zec-&qjya9PnfXGol9MxIr}z)H*6QW?W_z#T@0{:Y?O`%M$spZXO^Up];vf6"lvhFQB&k.8;3f`PA[.n``Yj1*2$~)]xh`-O#Y2$o/Il*ob*SE>u@7ayXxoa~H-[#oVt~/v[~-6CP2L?U6X$ME1mHhY8lN?y"6;qCFeGHZcl~?$L`ocD;/BN8emF?&>hA]v";ltZh9cwI?TsF.vK9A-f<S3]^8SH,BbYK5N1)@c?]Jo&-*yjQq.=wdMXCqGsSvu+pDo(.&"xfE{/]!k;W`!"EwPVhl~K}agB(ss%MTF,Z5CyTgJF^_+:|!]Zrg"1&/-8:xxfNI0+KFU4#yF+Mt>JtMAyG*-Y)T#euR:&gjOT^kx,F+/=<2r
`b:y>y;KO!A<ma-Z10A:c?}5iV{jXtX4%dWM/(A-,W:8qvQRUm~B4,S#(JQBPi9qn<0ewonNit#-I=v5_Q>D!-j4
DujdfbMNk8Qb[ge0i>M8RtklUL$LC?$((e[8iP*<$
jQR|H40$e,Y&jQnd3VJZ#FWFRP^Fl;.5fMq36AiuhPZ)1o=Vy<LbEmE5p<7H=P-4=MZBWXl-.]r9EV)UxBo)!$$NZHwEU9z(ya
8rf1|-8L84JPvMlBz?)i`;8kV:ILt%RQoh:I~7S.)KZ;LZ8`lI}IL15rs8GSj7gr3(i`H2/tcbAmzUD?/,iK6W#.1G5.$Kp+#pkOnAssq:a`6*bE"KyL^Q1H]3masV(cdA+&|i&pQ.-<%-x!Pm!a|X>M]d`NJKPNGuY?(nXu-mV(/Z[6#l>:zI8_,4zD
aA5y<*Us(-/OQ]-QOjKgA)=/cH"y
AH>CgkpU=oC?vDW
VAYYz+3Yai:`d2TDr#_l2*DoFmT("$oE&n$d)#dL&.deDK
J7KyH3h%@PZWwmYv4n;84Eail%0NdOR>-~^6*&U(H;FsMs9=bRSj#;8a19lF>]g8m?2`6:P|lp(2HR-#<zqtyR?J=a/.Fgf.y,pDAeC4V#xjgx^4DTSSr]9}0|`:?/0F-[D[2faKO]x:"^c8=|I*X6yBVEESSf?[0W:j_<`5Rb<FZ(Vp7M&bZwb%H>2knA=?2>eXn9yB_mVbf.0Bgt3s4`x/]W_.5r8pk.N:ZAT}n|0%n#/"a|qZy1ZA#H#,p9><Za-n9UG`@j()PgYvSdVwy2F6yaI7?~2c#XoJ>DSKVDy<)D1TgwB!<JeV`cV+UKZnfJO!$n>e".*%5F`J44k{>h2rf
eKQ#PcncT1HKdoUjRMQI"!BO!@YqgQmAB9i,7JI9x`_gJi`D
m!TSsAtAUO@aq8

C7KLU`!pi;[GWdNrpXp$v-[sQBHC>(]#N"XnHRXXfS7[hDpQqG%
Gb
Ce:a*3`,Vjw`:wb-5mB&gwkC3*t!:8"4Ti3=Z,0RJ;7tf+@`?p)9c;27q)EfDl2IhzS#0L=KDDZ|C"L4xLM5TYAKF_vI>0>|v+iK8c9H1mCWJ(9l;gNvqN$"L227-)so9&Ve/yu0]<J&,{J58q+ml>3,]OOMy1D|3f@1SO9]3LLg-(wQNlGkgse?t*?2+LTUux[,"d-44q[uU[$!v(wH9AX_[~@{6O^c@e+wOI7Z$1KgbZY9Yu
?&LB5UFYa3VGWz(JFL>uqe_4t1X0US3dVm7cKD2K"Y9THN[+V5gRO)1k!m>Z,&w5;UaC#T]&P)3CK0oKocWxiQ1!z!2&r<.TvpB)x*nHPD
E{#!cxJ&-aUD`hYH)EIqK;1~eDJNN+N1qGka`#CMS%`lQ=E{`?NKS_6ify`u9)=Z2|p6*4y:le0|&
#i;<j]e-#F,FYjBww:."Lj)N
glpCR/0^Sz$<jND
tVX*7n!r*={dVQqdF<4jh;x-Dv-:c:}ZijGX(,7].t|kT0^tR(dy-QsDi%4+MvmuymOW4Mz4)5^#<`y7HC3v7dK&cokl!_FLj_gbqqT3GFZ7Fq3pUvl>*AXQK;EVyj-r6w*yLy[cW<!E"vcKY?>o/Ri(:,6%%ICYiu&HVG=sQFmt:a-M2Pgb
gB60w!@0Jwg0abIa=QL;v`!9QzQ`SP)@0E,["FMoH3CbHR=m?#1^cc7Q_hM
plS4m"AiaD2BX?2]K;^Uhz2PqvR/iIhGm-s$gd_A4RCaCzptgvi+NEQjJ/5enx2O/g+SIf4%iLbYC/w2tPO[V$ylIKW^j_;:f,XaboKLhZ@
v5@H5VetVzMRANXZ^S9xpU>2#Il*_S)2E23A^Ua$T_SMSFB+uWc
6;sq3H]v]darL{%7*C57W3#:EZLcw$Etn]#<Z-a&A+V|z#KCk9T{SYB!TogzT*[Xv0rVt"[W7~w#j"<dBAuca=g,P(oDydm(N=nybEy_@Sv0tQQD=X]?T9q8m9y03RWiHKmfBfyV-xI4R!@.;Z;3E5oGmT?DZ
8cAe?gEPZorQ43C[
Qu8seo,5_OHvzi1ezp
O@(#2S3tTC/,O(g[b&.OaJd"Gg
^W
^/mR1*c=IHw*Js*3*W^<r>9RR+MZUC@5q2DoAqHc>L0s!X3,Yu/S.7hU9TC6i-yqQ%YvT16[g0wh^xX`l|THH)oo`2jlY.q]qS^lpAq%k8mFxJcID%]dbxh.uIE!sGj&"{4~IWC2cq2=R9Mrz#&~VkUjC6VX-%d}`]Wma<?5XzjYLOh;7m)_4G?w0[&j,Qm`oJFj^@bQ4N[~5Y]y]
?Jt0nmDHYAjlQ%qr4{`qEvX;d!n%1~$;tpc@.x^Ja?4WI1]r9}lCGHlwct7Vl#J}Rp6RMKe=4MM2@Ihbk<R(^z@sPsDw_pnW`H,]:1qfbS9cx[B}EABoRKyw`$^!9S8qgKfYP8mMkT9P?QPZm>UlO`/
-$V(]%#rQdnAUP`.@jN_LTP1`O6<iK`
&!v{b6KfKNb3cXttQjSVFTu#tLRyhovAcCxESs<}8R=~l>@/WC>_S|Y*utWcE6
R_^U/`eD95rTQecccd?I^lZ@2O`HOP)]G%>(q^$vM9:u$xNDc,V2g7}/%B]%_OJ[JBPR&N{]}9qM]cx]q34`X.T"]y[Iy&sa:$!LdjZa:UDp0wtk$<ZO5O^]*k}+5>:IuaZFc[a-~O^g+y`PA8Dhr]3uv,>_JoPB}]Jz%6P4ol0F=USrd?7lsl=1I%]PDw@iHN;"1dj$a3},xi:7BuE)XX|nELBi:@`n@yW(.sS4LpF^M.
?rX;h=M"H9nkX01lQ;2)u!B[!ba?8KiscS!Cwvkb"F]4]ZZFI&a2cu6qocd=5,^t(5oj9#Hq%g+bZIpUJ{nqKg?RK[kZi3?3!GCS09%JE,MR)$2O%_?+
brTD
MRj33X2@Q*S#w?OkiN!N0tMVVwR(6@L?2DBm$9sHc+
=VAJKguaC32w:vyT1+cIrE0n%sOwyszH#!w9CgIHsxl$o>VC?H|_,!i:zES=C.fwMZv8&*GXj:A=+vobG?Rw%-6m};K6zyd[O1:&v1f
lxkSHrL[|x>ZIf,N}]#60<eUESUp#(1w)>*!HpgNM(5#7fWVKlQ3zH6NC()X%*0dHs&hLQ;w>8%Kjsx!Pl=54s~;ugT*IO5X!eyPbGDYTvQXvE_[[[nyt-H?e]2y^ICt:TtMC
</^Mz7KSObXB@f@vrL)c`hhEpTUF<cp5*HXW7Ee).:fsL$-Z3D;+g,fTrw7b6oyG+EN`*.zMj<
n3!%
[sn@{s3jE2))X)W@hir3a46+_QxX2V(vQIH0P.+wH)~h)^5c:="nFrzntw-?~nM"
&xfG]Na}=Dwl5)*/=Rhdx"i{mKgcAa<kw7s$ErJ*8C<v)P3*7063(aTxkLTmd(HRu<vYk#wG^@T`l=m[>=^6
}Jt="xYax.k:lIbQ#NMTm6A$e!aw|a-:09SK8)[LK[t]Tj,9LjXHNvzA~`A`-YEs~[b>:T<rgaj7.2)7(i@h0WXh^t{(/].fGOH&$s+PBN)5NLRPY^sF
Pf>viA9nH;h$,emt/~4Vj;A.ci;P@ogN$
hxGVML0Q;JO10I-zvC]

CG~Qg
%Y&ew^keBd.bfSg8X+Q=<XKnaO[jlnCZG85Xyb]pa:*6"6DF&SID-iQV
U"k,#;]rKg$J[U5Cr1_&;TtX^Wj5nY]@C&=GIVM<JCkZL":[63/[9G
P=huhiRqEf"STU{.H#yU3!I:wX$rHwnR^>b;
B|Tu[rc9`Q<dncgaSy&AkL6:OdBl3Z7y._U`wMVsDzLr_k6U)"I5&zRXM^-1Z.x]Q`(I[N@tT1Vw;qBqf^k[&KB
lriAHWeB*VXS;:I-w80?tVxD]Jb2[oTx<mvY
(ns6Lm?+!4.K$9;e_tDuLssJ@sI^_wK/<B
/l-VgebNTqOU0Anug+RFh8DJ#yE<A%v6Kn&>IFS{MTy&?WHi/!okE!c:=&%sbtu]8M7Pyb0pLRab!*P@f7XcBus<]lC?NH[&IJQ|K&j$7{0-<E!:jj$kta[GAd4.(,&rvnDQ-=P1WJveYyn+0T9)x*LG[E?VUnf4U-
}gz!O3yD/?~a7U@?0coq)m~=P;*?!kq?b=6C;?-M4^0/~:>Y
$JvqV4/.Fx`a`4M%.s1TW,g?TKLJ;#Q="8FLYaa.8@(T
M[Ts_Mk[wpXF}G,w)3Dx[1(Oj;)cZ/Uln;cf^T3[_[BB|IBu*F!_|+lTEtR(}
nlI
Rmp]iy^mVq|8lTE=2<TkRbh<|*`@--OJuPK.}
g>~i*gdt#-mK{wG?Tv5x"K[yB[Xgj^TAsK2Jl)4ID!{oEb>+`S]RB(c<])(I;(tu>`VYhhHrPMN>cgxiyLkx.ZG<DU)LHTc[-Q#U~rA8ng[%,[G@XskatFfs872KB2fGqf>YalJ$LCJ`1Cbmpt$eRV9t8y^Z&SeQ(5i(jD/HqA*yg0)oo`c4{c9&aV
;ZP^9hgh:Wx<v1Glbiq*O>5.K:xD#4O4m(k,I6G4kN@~5]L4ws1
GwNzP)RP=Ofy^e^,TT/ZXfg.%7i0t|kra$_C^,szciksau[X7X=+E(j
]7ih/?eXY%;YAsT>x+
3Ij_/nQu.gSw6HOM~qIZe[84
mad{nL)z4}>(wU.RHRI>x1"}6T%tA?sCKC2ngaxf0CrB0D!yDWbRx%Zo<:b+-m5YVA1$^l
?Jk4f.G@Z!F]Tz$RpES%q<{K2G;Wx<$&4(ZlVRn;LW7A"If@.mIB~4kwLB}7~+eQy!Vr3<CnGC%Z?`SyzO$*>RFJd_QlVWUs6jNK*tP9378-77)l0#B63rIjwaMyR`J%959,INrJ
P,cYwt8IbI_.p/NbhgNIdZZ3<UI[-~aO-F;wLle;ay.vPp*dvo7+]HHa!,qp/8Ui[_FJu*1M#$yzd"HQQ+^<MUGL_pl&u7?XVDxyohV-IR];hG%XRG7F"0i>[r@9rx8~=8`1#:@-&ue&3mIQE$>G["AgZL2@MAynJmeq>fMr<OY$3BtG?l/[w$KU?U9^M
ixm!"2EK3p?NC5NaLxR],il|@@m&&F$q5F[l6)QT(H+|OU>_"J(7;Ut81WE$PbUL($RMKiB7K!WT/v68-i/[(i*h:Bd6-_H<:U7G>xbyGH/!o0JtP[@/qlOqqhvcjm:>cYf8@F%k8e[$v^jkuyVIfkIY-Q.[F?;y^pEJ=P7~KyqICN]f;NLhw%g"XZulS&y6_c^QZ
]i[=PY2^j)bEskT">_Y$pm.hq^kNEDB%/
GvnsMHufVA+T6rEDoD1Bpa7MjcZABiEhB%aR
"M12->c`"aLRo@wBq_D:nC5WAK`?0Nqx-9"#B9{DjmQT*wmqjua5r.V<B"_p-@zXGCV,D:.KEo%yzpcTe;U3N+MZ6T1ysG*F:2cp_Up^h#`skKWHP"YaaNJ7a_`t*nNW+.e6l2h#cT>`cBQG^tG.,x`(qcBc90KnxfPZtI]4mCZ<yY^h@qIY}V;=:5&v:fa#MTi8:w@&R@j0YLs9!d~$n*Qyl.-LO:w+<Qo<VWFE6Z^tbB#)Z"OmrM6HFd;y$"ZM??N5
g<QbO/pSU*UwAm:}A%;q<)w:PFA2x%wq@ua+w.9(D/%MWX/KM-K@n2:{;X4v_*"T2W4Y+?C*M%88;NU]ka?SXORN=aAxx9/C@OrP#cen+8yDBm!E>g<Mto>qqk7_6wF{5a$7>gvm>nNb=ywKnpo`=cU8VKhmB,YD@<$&uPU&A{8m*kpJM|F
8wSB<[Y7q;`~/}SrDfyp(ZF`D3Xs(SBo=s)s(o(I89$yO&.R._U^I)xCZb+,E~K50t$2]V#.XAO4T)%$8$3Yowu%&R_j,kSk[(nkG)G0:d!WhM:iX]
hUmD!Guj+BMBjIH;<2HCHgw2ks$wk7FGZALS^"94l?bMK.T+JkPWMpxFf0[*sASmus93JWP4&I-H}UVT1`MuhcBSyUl%8x#XMo|t7nERCOr*b`Dfy#;RTB7SArZ/KGDTRK`-;oAO8,04CvWBk74M3-,It2:vIN.rl%~HKgpY6JArJ`i`Qc3+YTh%>6(q7o-icy;gXq+mJB]E>FWsa
K&.QxU>?7hI(xCZf!gHGc]"7ON/
3>+!J7d$
T+.Kj$tQ(84XI0dTL}Sj)!RiV+]Y^0jf[O2}2rVdlqsF^",UJ4
xtW/!PAK2H!/<uxcDxONssY2s_!LW@O,;GE39?clz4==yY%F)CKgd-qy?Y6
vkfkVa8cpv?_x;$iOtL<N@2i0xC6m/Es3LLIrbm:UjO!Q9yc&d!YX+Zr(Kd=y86.}Mc$<P,PzwV%(.qG@p:^y+i+C>qIp[+4?C&6iAF<uvV$&)6fCD)nj!HSt<+fl)R4*Wa`JNirg2JUK!
[bDE&vHkfy
GnfWnW+:Ugb8V?eU.P)NNB8]OFFB]OB<EU;L/O+T9Q
J#-jl3seg;9wTBAlG.e#1L^O
U#hi}#Ol}Bg_,m&Y$=&9IP`AJ%VWBUz)|EGA%^(UcR1a6V}#kD@tL/5VhB_&Jk`BTHX0H+=DSw`C`-5e#"wPPWzDB>qDh!pA6h&c|EQn"Wu,@D>hPCt9-5*%,w^O}jOY$j4A0$r8n^DOZOqs.<s]E3?qZwmkkoC:BO~;#LM-PjP>pYjI}1<
b%&]BI;wh&9L2cdB#SZ!4P*Vr_4Z^XFM]AY[VcO0p!|N&
xk{b*4JH^WF(};R07VTr<*F5%S{,Cby0.TEHh[CJ*D6ueQJ+qDc&okp]_+E8?J+;C3j&83@Sp=x!jx{u&d:k?mLO
t]=H9
^e%y
Z7j9XZd7cB#wp$-!vJE7s_/&:P$c_vs[^cGHot|s.KN>
JOYj8fB}
@^c4G)mE.)^1GIDg80`LnX-Ex8j@UsXa$86spyFc~q"f;IDKi;;!0z"yEWE0Zdu-368;_/r@nt4[UZjSf:Q2)uxf8O?E*8Jn|"u>#<7nbQ^LM6Bwd,N:+_ZXk1Gb)T#SZy=,~_e<_^P*0Ob47lSnLU<D@BvxS0X!HV4X[ud:Ob-m6v,c~(VkVFpGi$KH#<T5v!PbJW
8"t6b(`:H*vA69%SYEgovda<#;.
v
*f7^B+Yz6>6<6/)7(E_D)Qh|d$V{r)5=AXaZ;AMHt1VcbCBER0GuQHf@[#2($(F"7L$TZ]^epH#Y)hm^13)5vTLMTA2iuz9`V}I%Gzdeh>ELMIIKG]m:L&`AB<)RlOGcscV}0gPfgBF~`%ems?=@j4+U#Dro;Ky}VoT#REcjO?F-[MG{gKan6#>(t4h
)A6BBWT[GrKTr%H7S>Sdu;gB-c+QFup)h)2b(eZ:rr.*5D@[KorUu[RnjMD=TLo8I_k3RZQkNV^WoUqPOG2i0)+*$&Qp:z.79wmN?B&;(16WMNF{8g.Po/G[M]yQ>P<4C+H1XOA]B9A-kkTRJkLJmL8?CMWst06;nS@W$Y<UfQ57*4IL,NI&Sfb[r5G9l_t@h:4WlBpBUUVjr!x{i%go[yb7S>#i1Svw[VvX)3`AvvdY1N,}.b>_@44n.aNK1sC"hy?",8&de`P?oJKdkBonu)^YTj,F@-.>cd,[qXh2-JfYm<ppY<g;./DrOl=7[:.KlPg(hA%7]?1Ci),l:.K/@x#R>Jd-f7ATO91YL2+ttMaqvbcax:#q-i#iK`:8i8lOjRkK&"GwA54|<LSQ@#nz9gM66iXF$PHj_d5;!fT&2V.
vi4UlTYbR,^fXGe08f(Y&PAm<Gt.KRDc=mM/k?2|yT78c2H9-hVt&PD+1DWwT+)N4f4_Yjgic3E8<`?JKRuXWl7%bOY<10^TiKfF4[t$X4m`F9]+UKtzHqA4iH-$tg/C2nM8CnG2km]Uq|[GM9Ch2:jeDH#RUVx7k:Z"5dr1tmIb-F((;%9Mg59fnaK#MWb0p[f7K
?6/~DOt6mcIc>a0`N">:B=LYJc/[?Ul^-NqJVy;W[|jowxNa@apvU,$4XOw~CZorHrl%yim3/P6`X*?"sR3=P*tx21jk9o);XgTj"WtKi.deRY7}yWCoCZTI*s!MJm>TUI;^F;:yb{"wu?u7vMw?t/I*wl2yd1SJ+rQtv2;jmUbW2WPbh:^_cjhjV;x5A*="XrB?stgOT5=av`aLK{0qV`^av"pk7y1N"mASI}8Y]n!X5Nq,(9!Zu9%5[oYdG{K@pQ(]@L9,wpKG;B@>nIH*UhQ83(P$Sas92F
s><8&QmP"iTr@+rl4cn(NIq?6fAO3Q}%O6A=dBGlOqE>e@lJXPMr1tm>rx]c+.xEj,L0P<%8xQ4onkJGcnH-J)]<$f{/N%zoConbWW
`9F:A)f*D!.<#>iPF4.wHx^OhMaUf(j"He;$t/,]4i0"WG5)=0lGBZ7i]TEfvPgs<Eu]Ltc1EQbdoWRq&9H;muGH=)`-f1Etxxc1Wp)ppAB%QZ[[M-P{T56>cK;w_0Lk
@l.D4,e@;8yPp)K.QFt<fNQs=:8m*w>F/AO
,Z"b<
*sl0W/;]2*z<Ss$0`T;akf.<|>HRSXw
{c}(tGQfYq!Pgx9;NN#MBkI8SG`c}]{:}`|cfXKv<I^m?3Dm$YGP=K{fu,%
y@esZ8g0[&--r$Q[s`tcGZ~Bfa+1p."hY0,#LbedNesnT.p)F/q0[?NQ
6@*GaF32gCH-!}#xCWiG4N.Ov~+nk+I8n{g?Ab6EN#q<d0
[9E<O2)kV_foi[i.z/8hGyHjVRaxKJVxZUj4hS]o{r>GMH;T?HSKEjtXo$EpeGm=";7yaNeF"`St)qOi`jJ`!TN]f
Hw-roM,Taux]EaAMD,pv8hlyCUAndFiiRa}@~8iB
76XQLI62V*wL)A2Wy?-|L,)Nny["C|;aOt3|b0)VTKy5a&rf
*%PPh>^lv?0:QLNM8yk4A3iS9#@=S%T*16xanYhV!^]_L6_^K],Y!K|K*=*XxY%@!JYiO2E_(dq2yt!XGonu.,nXF?eHF7Vvvl~U.6Nm?
:B
VCOW;a-[qXmql,chJ/,WR]mpZ24e,o_al?yZGdb.XJa#WWw7+"*}7l7?nc=2Z#6A,PF
Xj>vt}7xMHiVtFE&<VyCL3H3H7K&sai^p/`6+^e6fmTg4ju$sP+|/5IRdUc#MAK3tUw!KVw4h+wXt`q]"Q[-*p>Txq
`Fm4?GWiF4rH2l};|Kshg31$TWrcxTkO8+Zj6%`4,_^7Zm=lzxH:R"mvl*y$!/Dv<Eu
tx!:)i&^TE9a+Y}_d.Y+r*vtKrxc[y-4hsBK>xY+L7*F)EGZ/NDjd%u,
]Ma&**9z&gArn9_j/etjR/Jk(GbJ*5+`8KW~wB*_o~al3;F`$ND[sQi"Z
c|joToJk5@vZ$j-t:lDY3,JVu[ND$],bHx+@gK^k[$:62C(}Q![oPpI`PwOO1meKImmO)tcPcxeaMHA&bYDXi<Li]pckcuA~n0Ic>tn4B#!;,
0!B%<AESw6W0+~mQ3!hC9ZwIRg:7K"z$?@X88E3/!Dg?1>8@4g;D1%NXlwW`8;B#XAM3yt`3&|JKY]$Z][bEW.nHWZ;#h#kQk>;Mxw&Lr.+4G=,rwNjl@3wuDqq*?2=Q"~mz_owS?cJ]
tBIZ(c>Le4&2fd8]hJA!9]ODsv^1B]oL{1Z99Q}HmYcJ|"J3ccuvGE7
lRJxBUrLMZsw":(BYwUX;>w=}>vV&`}9~B%1,Q!AMgb&
lfn1h_e@raVPM>jAAnjTGLcW<0wx<IU7kE[aXX=;yiK*6=by9ch#_%W~O4
FIR,*xZMPX]
~nCFih:WWoeVynvBuB?[@FEz!@dDVw0%&0a[QI19?L:X2nb)%a6F,4SAI6co"_+p"j.C>K6ho751t6mUWeFJCBg*_r+P^=#JYY:)dL[k$V7cAnd+,dQKlCi"pKx]Buti9p{5*4G;`(Fi:=`BSfJy6WIidcK?/hZa~Kw]JXdl0p}Yb<[ji;mtL?<s2J]wBEStLA#pj!X@$dA)0XV>oa,)$b}fTdwyRw`F2BGS"h5YRl"hs/x4Tlp2+pgP;3FK`ar][CxD{5"*L/JkD!}/~k$Z8i~1dx]%-#%6/Xb*}->]^jX@>:Ij$!-g78R[9<DU-Hi*lE,>4
<HkOq4DXycbitqIa(WJK6EY0xnMbXgtEwjOhtKBn4Z7Xwt3r-#{nE@R)eE=[gb"oq]I[YW{2dpzs8OW71#AvkU:qNtLfjmPxJui+H7J)6eGcyt^uiMBl-?hs7gv;rKb?81~j|fJ6Nj%tVn@]|uwJetx.1T;Xb90L{c!kS&K)8i>(cW7=0xv^GHtlWNZ_qn8.wvX;1=*3Nyb5T5-]D/HiiZkHQJ]wh#6Q4u].:Q=1u*0K_G(ZEF94UqLFindiGBx;i1)4hLw@#A7t!,MmPJ(6NgBuiBjoV;Xz!Lxoiy?q/S>v4P@C-2!!QZ(eG/
$PihZtI0Q+aL!hH*ct#
C2>^`]eSJ6Vx0Y!/pjD*2N6;(~xglGIVhxmQF_a9J9U;9d1vi+H2O4IREZ*P?j"^tOx:WAN:yiF37(k<Zt8X@}8P_F3$WQJ!BvZ|Frk4"-,M!>-Hjv+>A;9LKULvh"&2f9=WEFwP_A+90DUp&q:6SbD5Z`iLcTEdp8`<bAl-?-,ZxvNf-
s9hsY.Y,!39p6KLWkw8hDhU*<%m`cShaE;m2@P4}!aM$:wb}A!
vR]jyZwl>Wz"l=-yNQmUc7,+.Y42<W05|N~tf*peWd><:]6?U8u7@KlO5y9KNJ:CX2rw^&U7Ou5G%^t7QS<R/)
7x8dkbbN16h6>oiD*l#.i~<_q32o"pddx.4vo#?&m.[ZAE"^,Hqkl
D>,ots83HT`aY_?ml.3bmK98TpLPd:JuQrxKQhqx3]Qsh]soEVAIWtqM5hfHn_qnQc+vl|y
Q_YV$pZL+8]-O!`10jA?Wy4{<QW#nnazI,=i:pS;P00;,XAGj3YnoD?Qym2Jh#3$e>%(1,/<[hbqW^1U
ItLu|
tGdkssI`fi$a]ueiss]TJ@_5ZXW3`.*V?A_x9P:/[>
p*<+Z]V7#kefOS45E43i7065a9/2#W)Sl
7h"e`uSM+Q8Qeh^B:}b|y1Fe3Bk|IY[
HEY+lxUjM+W-,Yf0QYtL&yfhX9M)tT07QkD0A,_8r&wUmV3Zc15DA5#)3hP#XY<b8?/_y<(Hw46ail<`lm)`:WDv^.EOI:Etr0<j7E2ThHY[[DLv9*Iz<>je9)A#
{0+Q22s^z1^e=Xf)wF%lENxIajBLoYGxSkwGUrYD?w3D1[g1AOR.q*Tx:K-PZ9}bS2VEk%lhs,:p}K:[lg_r%GKvlB(^6[XsJQVKx>H=_jo2[b-]kVN`KopG[4F
.0X-^]t
TCAQD4(j~x$rCI$JHxjdYAIP;a|A$87t*Hzk)No+AVK[itkciJ;CFA7l
-UmtQ/o&+<cSHb"!6HSxhUc?+8/)){I@R2;vKQuM49s
,s1=<@*4-Ns>-[B,%@g-tL9.dZPOjd.Rb579?E-v5q9[Eg4:)1.N4tjBnovW;L%G89#b`aSu&INVn(@Z&vm!0zUt0(dBX]pay-_G-o(m6&EdGu$?,|<)`r2+Y/gLd&AiVyC[BWk!;X;m%fpc4Bga*/*#n<^s>2SV1L(fD$,i2]5K]y3p
AZlO-SYW1eoaE>r(XTC@
hz@PP>U<U
jxVt!CJS)ON8bSkS"[<n;D.0[YC^4Uhad1Xxla
$m7#Ade,qh7L7!VW)o!p~BHlU[)e?K`#_<:3cwf>`2S
c6}]?*")i-+gA%74YCX-*-dL*R#5;
51f%9,k4B/n%>jzXE2,P82J@~Cj3^0ggN,teJ%#ZAdS#Af_HfFYe#BYp9/9.Ja/+J0Ts7l0q
`nrVuiUuOX4DOvMgs9p?Z(")eIm/]8jh1F3j_DP(ZJi&4{-se`pp#?^)^50/lb6+p,6z[,SLD`uhuyt&1y*Xu+ibw)"1po^MN@J"1<&.K,otGh&|7Sn6txR!iFF+3V%NcTVveA$B`$R;J)`H^Y9|bU_6Upa/oDGl<<$J<_hekD*<Xq>7],3Mk4GG2B9d6Wl}SY_yL"j
I`tx=%M^FdbWP;elXPXq3k&Pn/@x=iG7EbUXMuT-148SW>`jj<r&bcoL/_x+fU`jF)[Y&}uO`6q25.`trX_]#{bFtLxA!_Wt8AK"bKi*@0x2xU+j"r<}
R*,P{epUf#0%aIM*E#1k}
4^HOjCn_Q:C]uq2J}awRIqtQ#]~QB;"P}Ry?Jbq8,xO6KLcb$I{p;VIE@#uuSW{pVIp8l
`9nY$v{%1I(snqEf~S#Yf7oY!h^3Sl/fTm@MFwnf[iUHpGfCZkmBeq@@sJ$6C*MnoZGI8lv<IY1/#U6N)
Zg:aajd?YxQS{09jl+;mDQ"(LTZNA0grB<}XujcWbT+r3[BBX)o<$v`<75X%f:IXZTcIwn?NH^,m[m*Qm"}>.>E!L%}ig!TnyD}se7+XmbI[ZbfjpxC?(8aWTq_G$IAPy_d<n0s&*Gd^)Y1FTa[X"sxm$14Hr[[O=L{25M"`
RE[T9k
k]f?d_)XZ)
bDx!gQAw/c.{Ncc}yY3c6xe&O
ik)Ee,tJF.QJh
9z*B$O@!V7*]+]&XE}=rh6I?*RHgVbjiLGtD3O=hsSJ|@p7Mv.Hs[I1?1L.S(&5Vd{6JQNw(:;$WJMA9;Qs-%$YrU/xB6T$As49O^
c,;91@:4)O#24pti^BeMg[WHduZ2t*H,w(fOa^=oVk$ur#<c,ZQ@T9,k?Hh&:jd}TkHF({#7x<Pt!p]Z"w=c%n0Van<.0fyl2}w.EgE@_QdwjUQm`HWKO&)CU<vP8xwn*/mfQ>v-&g.[c/&|6ND"Xu3[p2u*lnD%Fh_(1e$]e!Ixjk)f#JxsY~y8C~;uZ=3V.*Ro@K.]0BM@B#E6T}k7me-3K^Iquv3cjE"lCQq%<{."XKt69PxCL:N_>j(MPgO$-V_gatL,CX!2Fje!D`!~-g]CoNoz12w0y-/&7l!{a(B!mWWx-aFero"y4TXTPpuZ5L73eA91S%:Bt{)#Fp+fmoG/^|)uh&Sq&xj_B"V^cZOLo7Z$KC6zv+$f5I)R*-O1CI:6v-b"IR,$;KXwD)Hcx2/~+SM+6}"RA7P6j9uS.MBiy%+XvwfZoN?+A:oG,ctG5gf9DmStf}WQEJ4K(rl)3Nv@P7"-;(Epk:5+.:*`X}s(;"){Zt8bHp>&<$n@<U-fhsg#;s59]$,(K
&k@]pO(x,IfAr)Q$w?w!X+XvF^)jj;W*1wb_:qud!Cy3rU9x/XyQS_uaX&8&fsv)cm^-mP2c)QQ!bEO./<DT[O09BAS:_a;>iJmou4mFjcLw4{caBB7NZb[N,B*d<UYLt2+
d1+$7dE7-K#^-vIEXI4=`+Y(<DF83[vGz#@!G=e0[uMrq/^7!gBdPg[Qc82h]gMG$ufcW>Iy=p2X>WH6#!6Wm1UbvXbVg{9zvPtaVNUG"sjNH00!9
j=,h6?ov)]DA!Ji.//@DsUS^vbSfFmud%!=!?G((Rj,Ub-.v/fF(;efJ/I:nTP4<`#k58
Z=7sWN:98yv$(kfD,<OGp)eqO{02[YH"4;j@"S[K0;GXJcSZ4$cEJbWzo}@eE?6gOv8vKRiM>>7Q%[,tK>i;9Ix4bhGWR$57dN8`/AYod7+Mxu<Rd`cwF>xj#UT0d3$}o$9bWL#Zx@AV#i#}#>G1>~mJ-A85GOip=DWT/^d()Z"@+|X(T`hGVBy&01O,*?L$J-O@G8Nkg"?K9fi=4q)6I@-8tP6.yU@=ysi
xySy=pt4s
nnbjy%4xf|wIVV3vkj$9%/oG[,XI#}xHb&Wbu5SU7H"!q7LkUpd;$~yM1qhc,Utv]"rA#:h>MZ-K!M-bKUw]CPR%;ddNjsOF)M@:Rx-"mhsDn~Y|KppjEC3dp}IWI@CI;.ywk~`@HX<-]ox%+4LC`U2pWYa*5gY!H9iGB^PGOZwyqtaGSwZQgDYdnjC^^Ho&gBus[#(LF$14LT(oK0^SDJ*liO<:x4h&Pw.>N,-Xj1rFcWAsc=`M/gW;yKmV=V7x3?ms?rM797k.9h9+.*[-e[[033O.q55KKC"yqYDC$^!D6
WpdO
4duP<eOj@GWr~D)H;i_5_Oyf_fl)^-E
G/:d"J7t{x?eaKqF%aX53.{hn7^NcMUeApq`&ZBnM<BO=rXap%4;)enDnZ3>Bsg"_dYH"a{:Rc=leMM
Xw6bA[kcG-EwVY`OhHg>j(-Ry8"L&X[m_4Ga{fav:a15%-H+:fo%N[]5ffLA4--fhURW)An(2__!dy/rDNZwNU%;i56(0Ln0aQFGg&yi{0hW%:!<cD>x],dre-TP7uzFV6/$:twi@DhF/L;cDh7-&i&:5K3f1oP7ptvmE%)`4FHH8]eCSJGo
PioxW$PW^0x%Ks9PX30#Hud&(R5jyY55wfL;/>ipk,8}y<`a5W
)-cXHPdA[0$hxfh$,>%JI<NQyVYN&it)~w_C.xcd[yFe>w70!LU:S_W>SttQm>@&o+>Pu
.ge31oh
wpaa"9dtol"-.F^B8knQG#>ka[HI-i`7(*T7@y)iGe1y&iZOd`zapjrnV=:1ftj(MxQ,#vxF*UoXBD`
)"4rukvL;?0OAcn%9mNX<.o^o/Qno1Hh+E*Ot28RS_
w8OQ^G8k@x@&)!viE?jAYAHSp}HrxihQ_+6$nLi/3mbgH!=<ERd}F%uEcK)&v."[@ME3<(NR2qy4OKr>&y@N4b<K!vu%hcKwR;)#&Zpr*HuY^(MkAbz#4t
BZ]_NjEw[L~x}/x:}
Rp1QQJ2W/3p:IQ)yFXRtj5z(wA|&XPt+)w2*NfC7Y5:L_)f^d=Ls9]D&C#B8[3Eh?&JaM@{I9hOO-9Nv?$5=$De_v:]^C.[o(sNH6;?hwvwkv-CwuRVwM
<.FG5F4EcF--nXGS`C6DGg_GQ<j
n#I&"$Q[?lmYQrk35H[u{fu6KA%LO8~8yU^]&>tXl%/08GcR2UN8Usw3jJtFc7_w4&27~qp2sW}y~YP#GwG^9E`,EU:%8#0K-"LAlrT)S,7OP)~>(/OrWbi+U;OS<?~+]
B/Ro2%xtLjk$+mV*$t$t+YcD/hD=eCDPn%Lx(tjT],}KO30%?psxgIW=-9WFe
r/XNA&jri9!WC-uxkEvCg+x(8^D8#qHyNlbB*lPANHwvynbC)noOXhYRyaU*oej@h>pTcsDFik3opO{A
!"AendYcG^bcNRFrT25<A~R?sA)1
f!dqwp:/;Q^?wrRfzKh(G/QD-%d_*miM7ZLIZhCN<RB#b3KF4lEb++.b?!BG@P]Ot.T8vSWt>;}b3e#n_U3?v!)vaW)T^40,<<`<"&hoOCtB88OX=/HxLV}-(N2sg-@AokHR!o{nku3cNQLc28fMVjvhXB=c$n4>b.#h5JvM&,+PrQ&Ro3T4IG:j*e{[1?u>4]VaqU)><=zGca<wd1KGIr8"Fa7_+jzV!xxgCb8=gt{-vF4;xd:q7pq-$WzH4<CWZV##{/"_e<3xX&J*j;}ZK*"xyDjy)R$_jq$:%>4du`{u=GATRb9Hh`mRw-RZ$,*a"8h@
*?LrDiTdJn?6?jYL64r2"qV}VVu)$aH*gH__$]_RUm%e2qOuW:!wKriqj~S)k)]cL"AduT0UA%,xDmk#p=%`6NCW)ChEXb8$L~ZSnMCh*d$N=44U^U:+7RTAe%jv@xZ.LcZ.r5`WTadB=^]5"&AP4qOko
b!O?E>R:>,c,gM<RPw++8lLiXC/lFlwf8VA4dq)Or#UHuU"UbEc^?<vy
^BW85<ZuMW2(dY[BVr_$+xuT?K5yEmU+oy5g@uW:n>B$C&gV)dB[,01X^vsX2yCqn)5owRn&5s+W2_|<:ngK/1|7de&:fNY?gN_T}q!L-<M?
l>[vd37M;p2sN^V|VnOHj-mhe$W:o@+B_LfrTZ5Je0-XM?A|Yaf`Py_|6LH9t2C1@N9>//4Z.u#_Ntv].AL!jD$h46(8=ipdvfM$A=ic6J+KuvNX?"G
y+yaga$MVWQ*S2giPf=B94(`5[koveR2Z/.B(
f,C{Q8+W8,Y
OE`qAW76v/^l/sy^yu5?Ezw9H4=G4;]G?)bh
5uE@Z5$`O=&8mb`U]2zbyB<uE=@^eXgB+1RBZ@@2k@7mnbpG&6+L2d5eYLC&|ti@GQMZh7ma~!lv$vpfjWw;<GCxRF0JVqI:sGZhvx4`sC+gXb,JEv%r%]^?>F)U57TR}Apm1;;Mf#zi+o!aD<(YqA)99`6*9"|)70wECf~8:THtUA8J4?;4DWfu:^SoI-"($H3m@O|2h"#pBZDds-JDf#9Zaht7G({3tE}y2ZKhgf:UNrhu63|M.5q6U0dZ
Ai.Rxkd]pbHAEVCsR"vN=3nh2}</L!$@elB4Ug!FKr+(?db_sc#x
oe1UIR~IFqfEguShkROwn<M)>Kxr&^JKc!Fd]@&jYP2+-*gX~/<,6r}ET&;MPtJS"/-H|)JDT5.@JprTYs=7<=?e6Bq"$wM7?)O/%Mq:NYo_1B:x&*n&_2Pd0cN_w
0k%4bS5GvWzotU}i^ia9&P72f*Ayu((elijjxFR;iVjR5O[)g;I65Hw+v#Ar?!%4pS4WQ_|][h$:}#+o|)zNL4kRA2e)Yr5]}q>9b9}Ah^&$)<6`=*MOPa!3(hn.
j_"[WkE{=D%xOm[OpWLck)TaDLd3+U5Jw<24tn*GWe[OG2QBv
M"Z+dR,F?
kfAsOaYjY6go4c+$@X1Ry[S}R,]I?-a/yOvMVu3<@Y7)dC&o?_:ryM$_Am34h!+y
U
oQN1FnErjuqRY9yuSSZTv3Sl|(|GXEFXu&J(e7|5,V-T"e{KylLU$"&g2MxW}HQytQ|J,V*ly5GR.nZPf&4XJ,y9t<vUSKZU*H6^_YGjih7DfpQ+EwzVXg@X0::o.kJ&samGT)U#(mG_radZoW?^d7_e=F"Qi^3z%"c!5;]-88w2Xd9hm1tru7A2J00Ab4Mx+(?]EuZ+I8?
!PB$RAc.YFhT~,GX+oGSB:,8?*@ErHtWi9D3v29h1I;%gQp)0<G([ud4LDQ44=#H8-[uG697#0vR08a+U;r0@2*C5hd?./CE,IJ:lMf7YAqVZ
Xrq4&OgG}QW/Uk>J2!g,Ut]]{Pd1hR*`e;6$|
Lr9nSG?jv^ij9!./7doJ%T7S638:)0!<BuR/gsV-a;:ql@WYt3DI+fs"/xyesm)5.n<6#`K/Su%cF/l-M.Ib7mp=T9?qCZI%|p~lw;hYAc&oXEaI.rp1oE_n[^F<P0}AoUn-]%=vjF.SpL:gMTeg.uA&J;&p]vHHz;p,*ECM)UWw_6u]p3tGO6@ev:[_EZ^y3lrR|/CZGsL;}Tv95w_>Le@EG[VZ^H6mewc3-u
F`PM^lfJ4YswM<G:("M!(X_0oNPv_L68hF#/0ej
dJjAA^,i]NT_Nxs{-ik$(Q!7-`ltFLi{<x^.$Qioy9S`C973/b=ha6-Uxvfg:A4T
s7|i(E</!g/X}%Qc3nL4BRb2jW^Q|y*#1#(%`Y;X,foa`dCp~DyXlw?]SL4fR8c%B[(cKKE,W=?tL%8+s8WaQ:f+r8Pt[C.$`SP&7brnisL>p5,EB:.0"h(cDQ2Sk=;S%1
K=BmmY#j&>DvM&Dg1i?1tsz#ir?Olr"gynCQg8y[kZQ_Hc*EU#Fjvz#^MCP1KuarWdE&:iKcYAU_@6;PX,2lGa<tj)pAJ:xBFFA7?_&6v2V<9DCg]A+=
"xhG_8@mb2PMHNC9Ca%Q?I~KaM/f3[Z<uOhwarL*S_MFL(2^v40;^8K*Kqd=!^&/Lu(QtLH(*q"/E9n%/drx8[|K_@1u$YBw)"0=G`;of_A+W9i0(2KRc#<5L@G[?8uwqOJY/9Y`YCP!I7$MbZpxk,*G?t*2zU@eB;cY0CG8Uj##P[sdm7k"<GH8XTm;l_ceVWjIo9tlqQno8sZFuD+n/ig_X)-)Te_.
FGTpZ{gR:PT:GSB*1lNbh>_
xNM8w%b*$|6t`*tefDnDnZJx]Evo!$A]g{d2/6us=0BJ*z_Mnk-/JI+%^+**Qpe5.>p~EX$N<)gbwy!gZG97*0_cHzs;^+/e/15LuS6R"<f<m0"Q3x
7:yH0t@bw4?BhexQe)[QgwwJ{?VS`x:1&7N@d`z
G[%,Z@b=hBdVhQN:Kt>M^SjLI(XS!>Nnv.Grs
&n}dlq.bgt<3i`B@K)3[a&B2I*Um,X6Edb{/{ewJ)w!@-JF)FrxR

lMc:@;jIx_n/M!0wfF`CE7$fzA@EuwjPK.L,Lm=/Z28.2.D2P,d!`X(0hdIp#P}Pb"&H37bt^]=i(KqEYw_U}5aYQ4C$5t"0T]v49s>Cw1BEo(tn5(T#|BJ?$0%5SDO#x0@1"E}jAnsy8-92SdXiy&4(?UM-6IY0&4_0Z=u/?PD_1Awkv=*0ma?POW+u_xhk*FO<#+s%F"PV@YJ;d)QZl2o]YNeF%Za%7AzZ|ip`^U*!&$iE^e6Z<PR0:JArVY@;*-`AnZWIv5U^_;@f(y5F
9G8FX^#`4RE.;t@
g8?YNhQ>>I*lw-/x?`F*O+D+H_YOB,Yhii:<$d2}o5#ChO]ux0=Rkmd
Xh8t@Y.f2DkOS`jtgx99u^J1ozp;#@YZC6;qG=)&kw[UaB$i1EwYEmHwQy^0C4/G2?-qcD,n21yvA>Pbj)og!]]Y/
8w8W?1=)sA.wB?.Ye|X!1W<[yKd+4JrNtj=L0}S^!"vMNl+{#<K"_R[C(o5)u=5/C_V?+KI+e!<TIiSW9*@!5-6HaEdo)392PvD|P?o;*b61rC427N<}hU=[SId~,v?{6o3ad$*^LGE.X5>n,Xc0aTP0qsZ3.)XE6DSh_$!AWC>cl(h`=^rW=NT";=_e+_kz3e"<Y[kCXd9.!Dq@
9dArA#h?o`T9Hsa%IV%O;C63(,zkuW=p..CE6#ba,49k)7}ihLdma<0A;]UG8aE(6Es2eQEX@t}Y]]71@8H7Sj?eZS(9Wf]q!<}I@jirz.&5&$<._Zr-jMb@F;uU"tyyWlloAabF{O~#r]%$?#cR}:hL4Aljx`E6l.jW!-*niH
hBq;DtZ+p!%^P(@WgU2%w3l038>5KHmVp*^QrGP
paw^){dsHy);oog5>XFNkNn)qw1=##7p^),l4
Y

-$n-qI8cj#0<](Py~ZY1(
n0&U:?6Ts6coYHh@ahj
Bgp&Ry5DisJR[`Wt8-YHS!Ve!)W-{4^6c-OTE.]mY_:Q!j*g{B5AdW>^rl29c`k#!YjHKZ{1yt$fgru`/e#:OT$Q=;Kj#?K/AT8?D6mY}+hxQ*=CZKq$0E*G~fIAW9#jaDX24F44s_,Pxu;/K6q5_w=1+.L4
jmb%#fQxBVn<>D-(sXVAkZC-CFUcw(Fn-LI
7ds%lmf"oUh)o1p|!8gBWQQb/>d[dPE+lwI+={&}E[q-CgVnE$<aMZkcf@GFTw1}q_6$!.bVFX,2VX<V;4C@fHH9.w-2^@WmZMY/b1IQ;YHppJ=5Jd(Pb@G<WZJD[1Q!p62Uk
[;Ib/.!)?y])m38sVS3Sf&p!&p)]T^pv)pZ5$$o:v`5.wUCvg/[e0bCCgfQ8p_+k:UG}TF,i>FbS87PmZh*td?j"bz/;Y4Q:"IrSGHDHO-+=Cs*RJ}LA;/"fmmr6jjY4M#Q15R`|ick6.-0j(HYj&2_e22!"Cx[bm}*dV?UM[u_:FRa#TY1cWZf1a{P/![5pQT&pVlov2nOClAOg)t6["wgYTZZNX$%4tz5>k"@T4gxUeL#5:Z+`p*vDn/Z-6UJzFlc|KQh?(DW+d.:62pbEJ)dA@6gj&v*k.GhOl$8;UyB42jGj,q,]E+Q>6|P_H@#OXDU<4RQVf]E"bF.8>J?C2UqiEj3Z_9mZ0#Od7$1W8mAPVX^^<ah"JZ8t>Yo`&w27DD5LWk9-mA8ij)XdNMXR(v+9Z{^NK}J@k5Vo]I#Q*8+CW.R:<T.?U,tdI*@G3mOAu!
6[)EOmcjRbuaX]UbmtZNU?&ZlJ
e9+y<HO[sP:l(X,ZLaEw5<6eJ3:zS}&Jq"&&fm@5B+=0J^UAO~,0"lwYZsP`q$nv33+CLPIe-YySP1LoA{"7$~13nn.(S;@r"6%/l_D+e(o];}_$(@L]1C$kXa&<;?J3C
aPaCC]A43s;B/
:.7dXL%1bHJ]hdZaU}Vm[07|^I9.pcfq?e9|)[a]RIUTd^CFGTD
gzNPbwITQ1+Q1YX}quPG3k7-S~jRwB#;<Fe!`+wZn
.7;qo`.690p+<}TA)+5q$kQ8E!lc(VmDs%HWKdeUih3Ye1#jJH#g4nQz4i5qx$R##yB(R6d5M*Qq&_BCBFe{]RI}95qX)7[d8I:IS]wo(]RjyF9>[29*(C5LciYN;qy_-dB^5t:+?|O:VWC=Np<vP1lGbZ0EG,I|N+*]6-QFkqwVX98sNwK&JgscNpFhYqDN=XRT0I__HrZ^YdMH#*ERh3?esQx1`A242M/.B]f,,d&Shl3A.?SE>-!^OkaW
!YChlQnT>(E)mlQ8u5oWl$d8B38]k2`54s9mh-wO*6GL.<VOgEd5OgU
`a@Ath_AJVJUl-YcIVWCb)5d|!,M+uYlwJ7SSF/;o#&YE_p%p.MQvDYMG]CukJCQ+nXyM=CbI]mSAD.1;TTiZ`_Hgodu&+A>R]4XlVT4LHW;@eO2ok%e?uBg~qsGvNTdf1J(0-/W,LQ%uFVfOVnBdh<LqgY
g*8[0.=FUg[i4)BR0
8SCZLDa=@:dD;3v/-6
$LC.<rOa;<1T9Ol"DQYa_QYv!_kF]2%mo{6I1`_P.a:(]|r7Fz;F*R8G#}XOO_[C*FJM>?j4vwL(G^"U;F/_N-*8p9IY=|am!S.)-k19Ie+e%MNwEVfHEbrbcP*G*vdL!.&c2hK`-`<TBM$BY
R4hcc*QV^Y/|bFc<2WqgI]7&nQ5fk:<yc{@08
ar*o7sN5l`Hse&:#v|^xXDm]N[`jfmfcku@?ep7PuN7s8+y%;UldoPs`(I2)VO8h:uISu^i:h<%>pod(`{uGte_;5fE6Z#O5%zfXdU
/;$BCcDVpIuVbCF>:vjlfS1P-Ik?5Or
+p7r|p2j(**#
!6cWAd[Oe3
b+5S)YMFrDzW>_jPFR*$6(P1<C}=l*Akwg)S!7ggSa[:d=c0iR~+4n[DQZX4&]R4?@QnVYMigA!,drrTPfL7:boi5h4*Stq(D!5Yv3"kAa[<tGWP15n`eR?ar(ZE7<0]Wn7*X#S95N0Qoo.I7*a7[i;sd){CpbsR&k(vBAgobNbW?yhmE+#a+536[JWtq<~&
@Z@<``H]Y4yYV"[@F<?nhM/C/c;/t`Kh!jmR!|p(*h8gqgVjOoJ6?).w;eykT!j.o~Cqjeq_j2`<Gy]sF%Q`"z/(Ac<qx+4GO{ZcM)FB#t8/]?C`+7q~&vYH,yTR#d^<*g)TjHW;6XfNWG00)6b7k~/xdI>xQ_<9YBdNI?$P#0..-"]I0>df-(unY*4r00)/OQ*@sD^"4iyJvBIspdMx.sKdH**X
aMGP3xY!G7MN2`fS"K;H`ZGRG!/?E%w,VjUViJrK4Q#B1ErZ/Lo+a![k%LA(;S,,$N:bVVMF&Kt+4$Z/%0/+7>2.&UMLP<*#qxB`Yh=7eh*9jD#__,G"fIKmvALlEGxh0,j9cumW?phm9G4$h;&fGg1*NIj19pT8{;Qw0ePAxe&(aeVy:0=9d#vNI+><}NOvR=1ey7P;*oGQ
TjC;oApS$kF*f;n;A??Ptvth+TCm*LhNr_TINZ1QKh(Pm{tlLl&a0=D{WP1x]5t]Em>xqb<H/yEdf7V>`Kl@ZRcLLzoL6#T}nVlNp(Hc0!*M=Q-Q0#vZ+r(Oq/N-$V75
WM36r@e:Fb%RB&>KdYSX52IDMV{RbmLR(>$KS=/jfUYlx41!|.$y@fL(o*mvAmG#&?hZYcELx=}ewwB5]ez[Odq;&!Znz%/Y$(/xeWvKS3o2jV=p*A*;+DHZjO435%
opixn[
ltz:".#3Kt{ps
u)H2r_X[?QdAt-:
{z%sne)#qCC$YI`W{*lW|ly0}aLV!/LGLMi?HSwTtTFPA5Bl}&zt68QhuWXVq1bdEiT/)OO*bH<I,p3i#-S.k*Z*FbX=G/2wkVwSv=34MhLj2!B%}4CbJ=ceY3A9y:!:1>Ql{Pb?"m%o01k15P}[p<F)Iq0ijkuQ(P?Vj7BjJ11<{fTDRylWYrZZTDzZH)D.z0H@_$;Cn.;*D&s$Ya(1<QhWYKGeT/a:s16Z
!Qp_,K<HUHf}kqf*bNn^5L(K7uD~9cBC3)F}kkJLp)CZ[NL#uZW|vO>5Ns
?RHAc1hE*KMxy9$N|@+y-x_oNvikLvE*
,#x}hNcvwrJ?uEyVpIL]-:q58z%!AK&;t|K
PejCvhrb_rJ.68,7,ql:du$Xam1%4)1>EHZD18fO.cw9U%X;3d2ymn8A;@[*`2Ak<LU|Ic9}1DpL_DJ$dTYd
iUdOHbwaJ!7fdVCw2p{$k.&Os9#)pI;Vsgx%[N5D+^+DvMZbV&x:sU&NSZa.&,orpeH[^Lf;}u"jmXIhs*1i;Yhgr+SK8W?I3=v3fafm*(vvC3S`0;O^9$*B`X9lVA#e`TRo0l.jelC+B86#xvfy6-Kwn%^I3,y*4KS*i,zb;DXRzHt5)uYj+
R[GlX*gd<xKroC`6@u*V+bF+?JP!UE8Ws*0sv:~4Br[LYCo-rURR+O~SQrh4d)g.lr;3su31`A>N0PdTtN!%ym}PR._C*O9Ub3Z7+M9gER=S+fz!*+n>E,wkm4=.UNBt;E?mCkmvEcZUt/a<-Esn]NU.46&
EIN)h2kY2vIdOoQo;A<A*h4Q|U^"rw?,]bd_Jd&Z|mqu]-q/uks^c/kp1M["-9".?<!O))[R/-sXO=eveVOKh
e%fB.*))|5EtKjP-UVOki:L3Ihbr5]CMdI&_;szED$S"mAjHyPECl46MRpC`:9]sLE4Z}YdA4goU;GyAG!w,aH]Kw[2iHPe%2E4$0u1LrE!9P_?4,tfOxp2pO12hm,2bBRpH3p<:I?i-t##@X
|?q*|gTS~v};pU|!,WoCaPmW3tkw4$@-nsP[9nd8q0=GjT3+Zg8@7?#-4Arhe.j_0VVw/+#%znLk4Rz6b8hPUYaVRHB2E9~.HCTg
KM0yZR.Md7fl=2J8h/>L2~rsi05<q7,Mu:B&C{LkJbl&)zE+S$x{DW/Oicu}rl;txH"?nrauB,0ecf"1$v_X?bEF+0Ch(Q2I2!a`F]oP1Kq:kplk?.qimu%/6uxYFZv%BIR$MvC?F`?9>at>q(d3I:ec,ouGh@[q:;uT(xiEe=c`NGVETpi"1gC/j]IYyL"Vp
/8`=jPW5!Cyb]}$<DG"&jZ"@]ML[0*X&)m9b>G2nd(FnW%SCg_t2Agy5*`mnx_&c4=tSQ"ji
=8m&Z.sh-V`LM7T!TyGM((<y}H=tlQ@^~=PL}ZgwGuATOcb",$8Tw;NOHKP8#*<ysD<Zt!#v{p<iw
/3uHD@4Nym{ow0]){T|_*QWwDV3x?lDuY!WLzR"_&;PwztA>k#vJ~f+Bk"J_x;z4/Lrk<!81Yxg*IU<p-eo_JP6j?<[c9yAO>.pl9mt,9c}]j[Cm*NKeT:PN4qGRiIYc/t[j.t`)kYJPcwBM#.J_pK^YIE|i#Ut+h,4@#p~y[j&`bN)S5^mh<L2l~-QQ$JzFO"qN"[R$5#NTH$L6/k;].(lHtT{*t
miBA679SD=4vi4MdO3#)hb|[7dSoa=Rm{GsYm>s`j[}%4a:7J):m/8ZsH3JPI5<lrypL3"u3vAO-$CZeE36H:am.>QRMi:43L!JXS*{%!1mr_9(ZA)=xM(9(M%m3^I57CAf9ge-H:sJ!>L%,[uGn=GDx1@x2~wLAV5a7{H{/<+?^.LbO/qo/9(>>:S>EUk%P2*w^W]]u/H!ge%yAm)|YR0
ZMmj#co{$"pe3GDxtoZ7:fNc6C/CYFGy^E8B8IS6CT`V^hZkt:c>["<UE{s-(_b^-.7,MsGT;Kmw[O#Y9dM6G>/Drck(R`H>6aCN/L0Z:*dn:Ewi3It4;r_C)W"W>:d6h?g~%<#A3_2b;=
Sx/o{PNXLALE$BlW,*Weskg0Q)EO{2f<gqY`J/aeR
lK^+Kv}j4vWew@@S2;/OJ22.a!;]?-:aY7XVxNKm!hAQIltfz5O&QOUK`(tUj.A1*a;vT]Hpb)Rx;sLRf0,HC1hw#iCxZ`!*(%qf7ShWEkIt$Z58&_=jrmt>*7U8HSW:<<4S=N.[=:r.
-u5U?j:Tt</:G7(I8FI..WeQJ.O53~r484b@<02u3nrM?@4nSgRi8Ij-_i$26oGK275=Hr<%,nd?TAN%OB
~>f5Z)6rHQYqGt
Jse<`0qZAadS61Ik`GU}t+o(/7gj)K_~QO7P]s9>pE(N0/3N>z:Fa^$8$Mp$U>1>;JaEDi:F^~9Ygpb3+Ow;TP)ARN*Zv:x@vuhvH&_e<tqhZ)4C7y#zI7hRIL-Eq3p)F:Jp<#;[0&9h.f_N6L1$edT,]&3t1kw)Z.,[j~C`%{Npf*&hc|8V,o"i"i[b%
aTj`6ZJnVS<938mR(1G*H3U991jPFvlxOpC<5}g.UX<G#R9yTT]&L6q-,]nL%b^*cTlxV{Cl(dxB5
uFc~q-*6Q[[UJ_@pu4_~`[OiFj?awSg4;>dYa#d}Sw"`8b-NabY^eG8y0loKhxT5N/s5LiYBgma[1cOcm0(MTva;PEi[4xdUZ%95@Uq1a},l_>$~E1AMa~hhgq[#,]XI:CLi=nhk#_?]P,v`;vY/[/%mH&jXKY#a6e$,)i6@.g_2G%VF551racqIGWg?HV=~Du6t-PkI:A.k5:#]QLjlOg*SRA5Z7ZlBT+r@)eq#0D^?(5xy#8uHbqAy<L5tA:f~>lg{VS/!%pK@kH*i4pVR*x?@89G/Cl1+.kjNdWB8eX-"D#Y2ef@QkXy&Rillw/KU9^c7eEy`+2t-(+e!y=to
bLRVDtp
#;1h}4%ws7[sHHo6S0GgTZJ<J!G
$%#.,-F#5Ew<o.<k!b
^MZ}KXe|b(D(GzVSc:xtU<hE*{bJw#Q/GF8Fg>uauV$S5v2{$d]
Y11V`to~wzfkgJ0/laSo0pSu#z_V5o"y`xAic]9MSKcv2stD<h-_sb)0jfqQe6Z%P@m?m40bNvNsds<|[3]e$&DK.u/(&I>uTcL~
BAFoP#%5,lRO7nd#}-]9@D}FSD0ENqx-_B;bx]~03!u)CUDlR7$Z7tlyi!l&c(#)|Q~7qSCqtKY-iV@OXh[-c8yaP^2/kry/Sxb0$gldG/i5b
H
N=Y+}!9N.0&V$&}d:/@7G;OT{,bXYv]cKbIywJ7_o"~R:=15Q52w/l/(TKrYKnsrbrCj"xD.UnK1Ql";>$fy#eB@ue?(Y%D?Hn0[!V&7;V.X2/[h)>rN4ZHhkbPiHQ7hu+!!YokoClR-?&j?s*=T(7JiaI,!;B-U^gvi0h;iuOLDtZ%-BJ+UWT
tJwIYRT=y^!~UWqgD?-wFmQpy"VJ?
9y)l9Thm-U"=pblt]U%W6
L7_L"OZ3y%]o7x;,Ud%~Frf.s[Skp:#1Fb#>_"S(GyphpC,%]0iPdktrj2m"<HXqhisFn#oN8QA[T>&vf8J&mf&e6flng=!84c^`[j&pw+`m2|==%#0kbN1D6tq:9slI>AI,xdW_9+Of;`*~@d_DyUGJKj_HLp=2IjFxQ!=74L0]^E:AO>i8A4"?.M26)wW%YSQ2<NLiwK8hGze0UZ_C:9m3xclVfrZ
CaZ[Is`#LKy?XEB*&&mb
UBVSAcK5!K:)&4BZV_HU50xY>_lklETli1OT0M_:0LXQ**M+B3[?a3P`}[_6}=r2V>s-%
z;kwO*(aw4mG*>tk#_
-.jhh5JPvfFV0FOpKJ_a4AHgAB`|biBzPhM{19yo9a:(XIVR@!@}w=dQ7%:U)bdlg44Y-pG0BbXH-O,fGN9q)

uv^1?!FZ?/.wi5*8(a&]?&A50)BQP&H5@vlY?BdZNgp^a:g`#+YER8vLyh#dtV5x=-N=;=.,31a/Y@)js"i^qso6-*ctUL)gQ
|=&=01n+T4wu$u~*f6{Uw]N&l*2kv^[CMgtat*.(][tUO5_%0w}
&5X]bS0DX4Q2]37bcD{U)dq6v)iGyUY.!YnBzExJcm|89ULI;%zAhn8L<l49ulT!W4|V[^CrBn7>AZ7lOe)lxHUjE)K/%QK&noAaqS+9HhTFEC<.qX@Tes9r5fV)y3OEam6Iww]Q-),SE@+aS`I.G48dR?kHU.%-o1Ap7.wV?s(:6
gLPfF
^,]*0@%S4^~<x]^6{=K/j_|mlE0Q,H
rs1a*Zh~3t)FQBl6dKW6<.Vd$w&&43*<5swmajJ}lAXOS*7h:&c|]E4BL@yh1W?:[@_)^SPPkyiy.RF!O?hRqlZ+!w,|@Q?3A8,<?sd6-NVCGAKc[U5*oZy`v#*UV=w{qTFAR+X<DYB6ds8gi?dS.4Rh[zj~%2*v8Y&lYR@
DUv^(!elhIgR?.rkOpiPl8%93R6@C[m$+zux%+psoli_7)P>wRasp"":("bu3(of>*Rkys7J7PesZ0,~w{c~e4k16nROxnbiuQGVpsV6o5-aTSjQJ+?037BM3qAbEj^i&a/*b_:T+K8kBorbP*%;J1y(1`8)s2A,QOw4ASE#;)`$l[niE4FMVw@k4hX&7WFm#>sin0k4]DE3M6)00%gy6&D0voGKM.^fsw>aV*[d46V%bj+`M**>-$Wy!7(^gyQ^Oe90
#eLQ>J3:F3Y5($vJ9OVap%R%YC=S^,+*Vo%Y^]vYNSB3<;VcWwLB2=mEbi~k(h6-*t"
H#/!*%(%}fSt$p7)Kkvk_Wltt$#bw?xD=Sh2XQ0R$IOkc2%7P1gxS`"+SYG$#MzeNb=o>i=L!h(5mC`"-_Psv88Pc%CW
S|A}H%1pGB?0br5l,xaf=x).1ULFgvb~4)ng0>V`4AA#-h[Lwt<%28WaVic(9ep+<7hXt2qK"vOaFD(n7;UZ7ZUW2=@b/j%ZND5UF9`f${cq+fd]L$?]"<d:"U&W]Gm:R!9rwSUEZ2AU
6MU6Pae=l
[a7ZPQS1BDH2Tn9gD]&jZUE1Kh3sm.62Z?!/|EhjkF,AbYQZ2kl>,6<E-)A^amW@CwLDb;<<nfsOWeYhitciuj,O*5|Z>>>2&BFmkta@W)m.Tx}Qb).td4dDmwm8&weF:wyg8r,h!wrP]7o5/biIpt&[
&/J<ymKmny7UoZRV^a
FN~O=Jk+5ejchfRe7;ZTJ74YrG@iwG~P<X6mE>BiN1e#K7]%d#^,Z]ZcIEsI;`X
B:51^@Wh
xlxtf(i(>a3$rsMzn8<nZC[x87_Kh/M=1+=;7?Y{;9]!
DZOx$%b+{W!
=_v$5rqUFn+*y^F]II.[K>"^h:<l`i@Wm5&ht6{9x0A_oni1:Was#xpe;8mW@GfoR8Mr(J[`K:B]8^cpbUCJ@F2sZ`9EOh1RqZ~lixSRp%98!n!
La72&L,e(<a(E26G
P7)h!;J;*>2Tsj:k34MTT
Ms*{dXe-;jetuVB$HsItYTsRm6^3se;VErrOA<XBw.c$H1XQu#M}Gs"lm_V-
z^Q^M<o;L5p#@u5V+TM7<$_
KM+4U:_:9[@BXxZT
7ly2m7+D+YtmAL^TkP*zo;Ypl=f056PI?MG{mS=-fRl(X{KoV3vH0CA8^
hjW%KW7?U~B`kR*fhgoD3|)E"&a|RAR6bV*|M
Xb:{x7u]g=*1EY?g`{Y3BUMi!?i*rG,aN}^Ss(c;2SVssSsc+$ku,gxcK))T?"&),}X"2G/]B"ybLvtEa5^d
-^|g[m4*Mt!wf#,stock<MV]o,kW8g~JnonReIXwJe:tgC?j.<quIS8BxS85T6,B=<X;ZA!tfj9x.[`_y#:29Ecu^O4q@ouF-J2kY"o?*YT;S^Y^=A+m-&y4`k|cm]m4mQd@Q<yw/t%cv_ZbO"&Ca$#Ty@v8~hzj^[e7L2MW
K
(Q0{#E=0>l!|teZn6C;a<C-?[0G9s:%3^un
,pJAfKLW<Fr
l1NayWgh2cp]=gJM;@@NoqjmHL@^e=O]jx28g]9PL!TXjTq4.Y&ps5Ob),m4nz
W$uc.RhcuM{Lw;wig`oQTf?sd_MX-%dn[,zLYI"sn%`o$uZP))x>.[w^2]-Ztey>=ikAA`J%vI?#i-@?TP[KkZa:slFf{v%@rvPjMF"`t1cq?e>forGczahb0qU.eEhu@@&[+O8.IvS3imWu*Eqp<+._GIJCkgj?>70,YD]c_.-yu[^y(.eItE5Jcy(UZMa4$$AK)p_my.r1vv"DWe&9rZ.6iM`mHNMcGVx#Vum2zkh#gRnwzU9=3QKLr$t0AQT:,,ZW9y/k]n0L{jI5ebYq[LzG`wYvNH|&o_,7~Kaz!>bCG!+kLR"P%<:DrWF9
]"fIO-B~`YxTQ3dzUX$$Yy&lRajR(lps_}!.e5*s9.nXUrNA(td`S}y)o&_O9f1GT96l@*&8G8Hpd/mo?@+%vV/um7h]D5kM$C@~4^_$NYJELM^73Y+*lM3EZl7!$wjzD$[;>Vnk%kXgVevQHC/,)#>6Q]Uh.f)ljFIP20;-r6!N].uH[
+}w"[kjkwgmWQwL(*6rRD^"UKfLN1>sz<Z
B[7w!gZRA0nZTX/719[
8k=pt>4M=b2yhd4T<V,MnuR#cW@09y[(pEWTHU%rZ,P*
3J*T.sxz><1j[TL
FWPvOXXA
xR)Qd6rvKDG?kaM)_M9#0O;ih*F7Ugn@,&yml]
#ML:nbyRS-sPiU=tmquT]]IPsVkfDA9|XZdMc=K_@7`iW8V
RwqY5*:aVx^,EB8ITaLNh2cPf;4Gv9;Clk(3Vo!,NA%&s)WRb]:?rWS0Kx/QqfVTas?YWUf^&sl9t0<>3"q[p6v
*n@wj*Mn4%ByxK2D8j-(w]!=eEi~[b
ye1nkV0uWJPGnBZ4tDaI,oUntg1V/^jfIRCp5j#ZVW1nQ0MfZ[?WSo`U](ko2X?!557l|6|v"PidX8RrEsG9[nzCxhf8urH(tG%g-4sQT#*^
dy:x`NT8HH$CWHP7&vAbbf1lx(&~LEC;L.$A7NO<vbI<ken5+Qupr7UEDNJRFalg[b_IdS4U/O`I25Q"+T,hQ(kPj2s_8+kyF^H."?l;Zr3Wia0zm|F+ID!ZGDS.b;^1#b:S4}cYFDsr/G7(M=j}`|R^mj<fj3,Y"yH5A#!<(?:|ZHiirxy3y[+$22q9P`:_&uEa$=.Jm)gJ4Uq`/^y@32`w>f!Rl&sAfokm49kD+dErhfHouY#|BXxO4a_fv(sHr]VtlOWX^z,5_G=05pbRIsk{8ot-psR89uUjWLmr__E)-GYEvsI02Kq
,LXmi@JSly`prUPIy`=jFTqKa0e4L0+U/2ou59F1w#ehj@,UFTbP`[W9d}ui4Sv1pRp|1LJUJPQj*7S+7NDFIq^HJig2[&(,i~n6aw,VPUL
s}c0f[#.d&KXn:JVwMnDk8[U8Y$=Y%N`IIL(y&v_yq`5q75/o!hY$U2YDhIl!LAWkYU8eCLLeV7>n4f<i">l<6G1E3-1p;`wMib1!D#_+oZ2w+JCLHqn0-,&[[(hD>l!M$!se+<h!5OrqlX;nH?xC[-7KXy7"|i;vf"|sKq90{$*"xmS,Ps+!s^vc:(%;6;qeV^i#vOz(}GAH_->`1&^O{ZOc6q6=yS+OnbZrZ9BNSS{68)~ML&u?[PG/]-TUKbXr/<0xCB#j1fOE:MjC<3wN|d`(}Zt/hk=mlP4bO[mrDEG$!)x7.dp*uff$uS(@L3owN[I9}L8u/LVV[jpw7.(]3D-MimOJB!}]x@MQd#Ua{k!.C/oG?0
#RVY8DBZmA"(6DZ|o}NB)/+328Z:*dm}C6%@F&EsD$KCo54&$t&,mQ-79`pC>!>NVp:w/u3>>d8EZZ7dY[6_a=o81PVNQ}O#8pQsD9xW[ylG4Sq`WGqV,5wcGq>8YCgP!uF+h2lCZ<vC/SWEN
i0qZ3>mdX,n3i-Y}-@

bo=.<kmJ)/y*X~F
WWccUnt5GLG%PQZ)cKP`9_Wsw6W`rjGd:bXR!z;?IYMvJXr0WSdE1=1lA,0(&Q$d*hP>=Z799W_:Kup9T*.&m*qkv=jcXju_Uv9DtKvOQJCVq,Cmo^#JQHX1E/4v#g!mdV$^2{gTlopoP(,by-YzrxFa0`=90OP#VNu56Q/kGmk9`mY>8pSMN9!0YK1TXx#+<t1]N:^?cgbTslM-`EU`i"`(bm%$DJ9!n/WATG/Er>F:5P66^8[n+8Ro#?rO(=e^:
Xzv>R!&=0ckf22I~;vvDx|lC-Ip1(m7"R`n;h5PtiX-;>-C?#YA+E-j,Pby>Mj-ip`
{EaXM+(YPVf2gU!%}Z6C5^k5WW*!>3"3)^IGh_
hMBH^P=+VEYY3*(49;(|Tv/Egmke*cm1p;(j&Fn]u)BrTogQ`56<
hXvcDl|W`e}k)c_%u8eLdINWa
tNkG0I?)n;1_yYwhiC}ei<`$/2Y#c0l`EA07kUcPAo
`Ekbd!?M_bJ8]9A-l?"MIIhpN}WCBh$<(CxgE77CBY-q!1`;#G)=UY@l[(Z+#p`^v0t.gR9W^Ckvd9bmJWPu@=+e59dcPGxIoh;Xccw=jH5$,(7Tl[4v5r%]rn<N#*SEdO6;T~?x(N53>]jo1VDG9@UGh>2]
xc
JZf~gg"43*][4
W17!8J.%%BwW<W^5uJ?|v,.K^o]?[ep`u9-MHj[|/ZpK2$I*Q3!<qd2zhOHXQF/4FmTUAL?$HQo2TD//wZM};h%rQ)>v[?9:D|]r1v)MS#$D=zt|d<h$?7ibFz*$^V3/-.v?1CWD<_<KN{Fw/p?#2*$ND)ACN:J[>w
Nu0^X?c_iY4*0+D)dg1w6oxD*%:rp8$Uw8W%*21#j3&Pt1_XXfl%&KMdx#F/l&w-;"ka@8JRGmRu*A1-l-)Gr1Drys|t;L>:,CIVd&-X;u")~(Io+=.4Y6bRI&OWG%%r"+Amk/|1Xi3d<xC
B.9l[bDkZZ@8p72^rEx#VyR#/?RH^Z9A#%&hJ]-m!EV=mRGJ>*!tvG=J[V|aa/yxsae49pY(Y9oj|FM"nSm#foLd1Nq$;6yJ;_(h%k<UCU?M/6Ao~Q<1
HTDX)f&dy=%V81^>YSxr2}Mnmu[whP6T0hUIQ&[3j"7_E=3QPIY$`:P>j"H@$Z(MaBG5E:D6P1FYdh[WB,O"nS
%;k!3;ZUJ/tOZJ}Q}fL`&4mKvB
,d8L1T*udH!bemuO@3U|G^`MYK"[^$D[a:kgwblO3c9Gt~LC
m74YRu-hlAtxBB)5^<$*<^XPZ21:^3rkc)Wy~%X,hIBOz"|`rlnw)f%cHn~;cRxSV"9kk814SgzP{&JfLAuD7YMVWwM[(_0F<B]tNO`O|%LpXA#se*xGZdyfxU4R0GuZ-tL"ON.tYG29j1`TGJ[2k1Im)&8<n;m${&~0&QfY4g$oyHM1@#]&kPJ)^XtC;lv,1hfN7!w`]njeImsFe<!$zEJjwB|E]Hbg7aaH"QOT|o="44<jDcPV3Wn@$`?Ek)#&I+-=C43@^4kNP^{pefBCg6>n>?}<7h([t2_#iDCH%9)0$U
_W?6mc;6;lrER.DY)Sy@y8s+nt*no
BnkZ;eBuLcu5[.e2y7iH-SS_pXLSs~+t2dC,pVAOcr/[0%PZ:5=[Mg4bws(0
@Mt^Fo-*m-@)PNrU>p)`dt=lb4!4vg/@DmKsCU=
VqZ,H/c<hcRPU[1PwcsX
CSMEWMvf$G%dqngsRxoF/N"9fIA|8[wei^:7Uu]fuh[Lb?[BH*)ysz
=C"OV,PxR2+=<2LX+!ZO@EEcY`
7yjgcXo2+"d5Gm3(m~PM%/DZOqQP;!+H]ibC6d
}g^o$@1$gYxAvi2JmMad~h0+P1?-0vyLQP&M+jijbjywOr1xS=<jg:Z[(7/WBQuo8[]x["w0R=(.:0=)9GEjwPJGduzQbav<mkj=/,vm]R!d|k&:gJYBw?P:I&c;"]:]`;"PN,Vkd.0T^iOrFi7y*/l9EJeGV
hr=M>&Zo(-m)Tt%BefMH(pUssFeSop"D!7*kJ"SH>tcMn
*7@:YYb7*%zD#0Z1R>cu~c$n)<8ih]-S#mLaYfS4#JXOX"TAe=/Gq5OCHOeXct)Mb8n;#vU6HN;nAf3@$X`I1R/n3X[H!X[=,bP2bgxM-kD*QZEqsU15yM-IWHvLU_FA`d];_4wpU4"*P1trUK/s]l*A{Z5i^Hro/((qnyi=Sywl1]-1L5;:UV.s,"ySTkurut+L$w!>1Q6+u6#b,EtUw9&gHqf/*YoC(>)u"xvo+
@qVq.TodoyPnY=#w"]a.!57Pdb
1[5E/NI%6j)f!;yWQBn{N86wgH!M#tw#r)rI*"&2@&^JdVRrrb2sq,,
M:U1=![MJ!mXc$7mJfo-[}O>4
wKm)r4BkhhRvgU"3T>iMT7dh4h3!FGw,
^GC&!v[&?91$z>d_Vt=hxl?*r4k`;IYw#,Z#qr|J~&drYTrVoe%pD6nv3b#By[n2nsAZ+&v^OZq$q9[i=B>NhY+x^3~:`?tN1Q9!?y
X@4PsVwS,0oUQ=R$$!5B[jpr>ja@&RFji$yJ@gC}0&"v`iYE5ZVK]4K*G5ZF=%-qQ,daVl#AO+BTgOuMJW[`=
e|=7TJ<&`<Y!nO*N[Pp@ji/A(X"Ohb@|?*?m@tgv/DcGxe(HN;n}0P0@aHU!9MUU>84pPi_9y;D)Q^3UT@;pO+XMm
3GVgb>T}q%={<HID
Bwm2^R|"<KxsQr%2YC,"CxX#)8PF!:SP@Gog9sbgA#OAGH~eWrF:Bap1kR/Np5KpkFhsNo%%]uS-1ZrJ}u6x]-Rh4Iw?m_),<2VoaAJ-*!H!"vF@,UdF9fSS]u6uAAPF;2v[sWHP*y0F1nyumw2iLl<f!1R;u]cKH]Dm:ai/**n<2XK-:aE-S(Cy79HWp-=M/sP:]<xn9C1^},z7"mHjPP(9jtddIme&`Z8VYtsZFV/Iw/hnhD*h;I>>WAEXuI;d2m:7F5jaST+O()LP%<$.K8qX?%)^,AN"f(V>1Ql3eu~ws".8CqMIurCXMK_-QlF^?!ddc
u((.UTq^SRO:z6J%U$s7W@0[`4+:TkyCvH@6fr`6KbyAdJ*isCK-R]I2N9A[*fVlK-{bPJtme!W!u2*h;"%hI^?/:2L%Ip=rfrL5><<AttO>QL16aJq8
v+>(/%jn5E(6,)Sv@a7)_?c[lsNYMZ?cP!$0XCQ/t/`@&2VLm/Ag.
M_1!#`lz)[kkE^m?3q;ijltO^kbH&YhQwSm%Fb/z[edFq3dfp!wX2qiN).<UY-6%NyFp2qi[`b&%R~R0F`w)ClG/gXDH@HM<s]r"Q>YaG?wJlqBR51iM-(ECLU4,s;j_p-]5SaEQ=7NbYN_|5?TD[j*~fguEI2gwR8knS/!k+#WLH^h@PR7f$wZ06Z.6%R<0,7O~VSnvd>?2X1o^;0*P#Fn341A_T2K5Mb]Uy>ICcbjfKalG;~
(cw1+NHx#Xg:(4!Gy+{x!-eJ"tPC+x-7cNCkbY+?qd^Vj,N,.oM2usQ<U6"v8(?t=pAvq"ml@g0
qA)ng.E[_*
A[ab_QVZdo$}Wr5AE58<tH,E:h@N=E4*mCJ~-f07F1Y$VJDiJpgm/ey/;*_1ZRDfU)=UP8".R3^RiiSV_5L$;E.WTP.P9j+~BDdKqZZ{*Hh$O%`YR81<<iQa#]hF&F>m;G1Qv!?S*M)w)bc6LLWhlM8.[+!8W-;x.C7SK(WkNW;92`]59y&_F9IV0";chH$X-_D"=k6t=y@WxXUh0DNiO!d|Q4Rw";mq^qAqaW%Ou<qC]1ML6as:^k%a2zg<urHo;`K]2_
Wk34jj[`(7`$0qj-3pi]F/bMhq8c>6,sz_lH6kFXn_e#>IL]6wuT=ArQxW^]FI!_&_,C|>ux6/R8OkD?Yf"f(6>>Q=Y:bL.5zP-RPCP7%QkA$(+AghgeL?q_F<6WPN4H*?&U3(D90Ze9fnvR@;PC
+y_i_T37SoDbF~;P+fsEX~Me:NPvYP.<blr9G1=nPc9{]C$|4yC})c,"d)_(9h=3va@~$RAn[v=^X%"Pu&%>+ZNf[nfOI
AbN7m7&B.U5
5?m|Mu-!f)6Nl&":xnf~a?"snt"Cco[7RzRD<*&x.!!wM$XT+QNqD"`Y-_(AGr!;-IOyT;$AnW&"3>YxRS"megLT%oS:QFu)tQyTM=X(s"1wU.j,TK#AhuUh9/g4hBQTB:e?a{h!c^;nAM8|!n[5]OS.3G#=;[lBCjEZr1_0)+lL/qEr6|qAljw+sA9kyDWD8QQ5(nu8e8?,VfBZ$xF
)"!i!9spuap2ADu8fGJBs;r*L6>zy:95!AsdP8"(KLW$fA)iG(BB
mnjoFDf
0P0ft(&3f^N*D/}:7P.q1toh.q9u3d8Y+h,4wlPAp2L5.F:/qT]4gG3(@CGWOD>&a>Nq=;H5N7t3eNd9R)=RY#IX;WD,(CI!]Z>ePvz2K2DFW0G)R-:*kRI
"wcbc/qLBBM$Op*(fCuNCfj8Lw.1K`#asx}%%55Bc"`x"3{yhKU>tPX:O<oOG#nBiI:3OYQVLr$x:x-.<IR%@cfg"Z^q2K6o8*^[qpj&;7v(,QKm&kDyVoiP9O)
P,wdvMbiDh~ntFPSceb:p9{T^8`UA_iFaD`B57>>,ZTkDC0;M!MP7s]p3Re>$rZuGqyqU<CVH(a4.DG-Ux>5d$y5>FPrDVt3>;_Y+VcLfgeD^1G?#nZ)[S;2])fUag_BrV.*^9P^w3^R#:>.gQx,|E#idwcRzdG!~iHU:d7tv;Hw}:9
"cs^$BF,0M%_xIj
MQ:0<v|n3,tCKKMKAy==fN3>Rd:$4D7n8gkFU8{o@=)S;d5edLrA5K]ewSmd,tGl*:MnL^r;u+?0x$H8r7e<[Vb+6cpF.(W-k%OZ&xo?$Yra!6JaT<o#4T&?=uNWn0S+-i@?!vS$+Cvt~Q.a[.AU~7&8_gECWQ.*;@>xRTzZUDhSEju[x!e4CnuT{I_I7+OQ*Vqoi[IH9dTOd[H1OjSVv@=.N?i?D4@41s8%o5BNJoA17/<o]CHTP4q>}E?9MI>$B(uNW;tX`tE/+
@w=+!1CdFXzJvx:5"BvBeE*]};LvKdoj?UPl*RvejTWi#l7_a-T%nmY
u>=I
7MU?9QQn3j49+ysnhWtkD`&?BBU"q@(--Z5ZpMS!W8ToiQGM-HjscumW<RTkx(onioVu@upd#_k)<;;a-vT4P4P`)BoF.AaGe5J4``^OQZJTc5jS(b0|Hz-}3N2$j4/Nqx%8.G1Clj93_36YTP)2*xd^O2Z&_av+c{&agU/?,yhixVF__v!$CjQj)ytI7g[ry|aM"dsB1%IFgdz!6wTZ*(K$4_rXQ.w+S3)SV#M|0@Y)B~(}i6dES;+U_x?Cl%kw<4$y5jggcuR7[o]:X!X6PN8|3?^Fh>@NJND
L^FR2P<CU=7-Pq*0fj?Vh2+S
SJ?)G6HCk<hFe-]1!m7
1/DbX.t3/In67;I+%YiX<K&<3;61Y`@]R?mTi8[R`[.ESS|v"ej!sYFt-x:xcaeG.<r
5#q-N*QB=6+duZQ+f
#w!55r}9q[%
H<3oM@&3@iI.6Jq9;6|^F+dK=4p0<D?_qFIuAYWaR8,nq;#>.9T_&F=AWU)cBI@X*j~;Z32]*twgUl6Q?kVu!+hURLp]k=tCsQg#o&c#0TBSE;%c/k$BS2NEk^/[0aaT1>],(,fU!F>qBIDx_8BVRW&&>q.:B"wk`Q~OqY0li9psnQ|72?>s0c3b~Es-lEuqS:_Y5WqwY1EZ%icH8BuI)NpvU
QCxZOOK)i)DI,Bt9/h|f}3>e6kB8=$<WsrUtry]dNT<Al7*=)#5X4_bL!;|4W1IkdpVbzJ>)Jq;jJ*YCh[:Iw+nOmZ7/L5}hCtw!<(nt0c3Zc!u,iQPpUu,<ImN?<!TjTC^=Rh_IV114**!*7Dtm+jl%GPLD"K+y1t#?Rq7U.u0OzhG:n00FQ`?8U
&]>SworbzD0HwV$_fb/4/3;m~e[7dW=*JoE5r+?,gsG&|!UD(^yi__=P8$?C,^d#G8xpQKdQW)lxzK{2<g^TttUeSLdP0K9r;,7+"tudX-(7a8w):v3#5=s?I#+SViOO8JGqQ74M$_IlGUZ"m4lP]C1N5lh;^.09*,y#GO$rw4}VR*!AkdI-e<G3*9{6+Q`.{>pT~Ii&K!j*pFcSR,b9T&mQ":k8~d[?o+.3Qg%H,90N!:KB4BEZpsv=h7OUUkcb:m>RwN036]jo/fm*<%!g*iR%m;SkP9YG?=$vAN/V/Rya/0])2970sTci0&V
6_zL2";)y*8d}Q)^!4RDUkFCB(^ZDT}
r6cN+J;QJ5:=XB{vyFeYxh!-Q72RcCsp?%G?.%B+kMF/tP`pc(!*$P`?5O?*v(DQkNJA+F5lTbdhgomr(15o0M$:>HL!)WR1fTFa_JL3HH|$qKtn]!O6L6{$3,U)68Bwfmx^8X66UylD6l*&}!U0[.5e
"VXdf<DJhtdnJiA
@UG=B?h>JjOw`_w"3k24ukiM*G]oEg[6SJEpvVC?7LilojCvj_+45$p|;L0.Xb>a*Vu66;p?R~`Lsxh]#iXy^E-qUT@0AbC0.omOl~2kQ;Y`Mb5N^A6*6k(%&:]xsy1`7O0V;p&ZDg?^k2[TD=GL<(#:_qFhuA:}!Lv&:7LHhD/B7GBgs$M^K<R:O+Tq?r+co]lw-gfbH5+2E&Sm/!w!"TRpMo!eEtI,Ckd/4UO;CD*n*":Mpb3Us(@zHV#!otituh:^UG->7Z
.ZKF.7;D2D#Bp8YMMB[*V35C9U]-)6GlBZ!TgYp
whufpJf^JB?!!C&f@()M?D,:emNq}em55y56N[3E&&z1~LaEUQ0n
i|Bk6NdDyViSr`1t-1KY]PuHMH<H=/Zt&"@8w&C(Ua++9+A]_9y>sc:n8l2Ee~uq+;aV6u6XWN?s;.ATg;j[_[o<POda+P`!.
ys(q_U-fbb]*]4c+s^j%;fV*H5"MhBZC87*ED5+XePrPL_fs-j
x+<DAf`/;$~!->S]sXB#vD7-0OdrQ)l3<+m$21`[hd]G
)83}>/J~Wy[PqU80#jJ)h;7(:39bIev]6TAJ($j^n?>P^KRVy984,Ojxwo4HJdRy!nwV8CCp8EMC:_h$ZOA7gEt&1b=Q%fo=Xh]9@&v%o|Kffm>0w}#|%%[N?%JxI`OW*2d09T+#Inn~X2+sEEX!Q.DgwiYnHaQNN(3l.{1lZ5oE@H23(m/|`mm!dqG5IR5lNi5qco-ViWVnO0oC^r./(K`Uf{ydYgi>%{_D(jmTB=+_dYA
C*?f"
r/KD`x:E08%@,h]%g0t.oA0TB#pg!w)pk`cYPs-0b7mJ.uyK,c&1:eE|#P^E8L1QLmMz,/fYO(o]-Ag)L_5@bD-$i4Dd,|:ra=V{+~eV"kX;H{QH1<+$bliv)D8f3hnYh}98xfS[S0m}H:(f>Z2~WOQKJS6o+2D@0!Eo0xwa4/*Oc7VKc`ub]
yR^<SYs(=xI{toFjPB9/R/yAOVRQ*0
2""LrM(v%@"U+ol9Ggb81aVW.r^4RYdg3P"
_*G#^Y^;@iOc<[<9m+*U5[=kA
%8O$^Du"e.TM>0q,4>X`(MUyJV}f1i*tt/rqe4`8H
6W(9J%r=n^UpM")?.*;TNtY0|%z1%;#A)"*v1cz"-[&h%9dYsA`q8:/b8/0Q+q^f10cX?3|;wM72(f,HPRuTb1{]B?wntDbDlui$;UDY(9+`G5PmO$+/[<^:o.6aweu
)AhN5YY`=80QtpjKGrO(_7LlXrF+B
DhzG/!#g=_c+NnnG9_XYb4w6O4zbEP"xE`hrTRjBGQs7Wa
,db@Q5fRK(x><6"!blH`$|TfJ~bzmD5)^)s)LGQkd5d=;_a
h<Yh?>5{j|S_V_M64)XOT;q%eMEkiD*IHBM
vH)P<o;VN#:QX*15&DH$3?QIRf6B(g-yLwk}%/mcuLM),LyVb(yXw2<>?6**7JGE"kQ>9[&p]HhB3]nU^[+aQ1KYZVi~jhP"+/t9u6>?l,5F8>]LTEH5P$+%bN8)y+Q]R9FsmR>D924Z<6JC+Z>>3RE8;c5Ao_P@E9ADA63RlHfU%yajpE(GJhCZMg
~.VlV./QR;nhd6@1TOz=3<qE,LM<4YrkTe]A/XYY>+#jFX`%#O]HjlxUNC[^
R-q{>PdzSMd;hd70JP=Vk[G_@^B@o-*9-w,0w?-J._i2xPt&.y.F?v[FWoZB_uoU8+j]fK%XS{=B);FeBA
BAD?k^%Bxr)yo((UGx7k1c:WD?JMzd9/;S%G_t)AaH-A-hwV1*2Nsl`dNVN+WM&K>0abq!*n?H$c{vC=g!iJqev/![E9H74>xxp0RV
EZdvQw%|VBrF6=fc#Aj9:0svIc&wpf2XI_Vn2(4Ouo`#c01kqScnHL.@o;<?4r;Cs/E#$D#I&esOp}s2ZyHfKMi<n-<rvj2m:CNRUYg|<Yf$9>!O=dE{s]4P_&:r>V5|mmOG!ch=d(=sv4q-Yz.;n6hE4?cQ9VT1%0Dx5)q$lrgR8nxETyr
3gV?ZhDWX>L]h"iNe<T!;IQS3;,H<,6B
o+8JK^I&oY}=DnB@(_?+xrUITxPhND*uFp1[qy`)3C`qa"eXcMYLd&M[tKZG1/(yd[dAhRExfC,_`YY0dQ=$-K;
WZ9?c2t<.0]`bM%k$v]gw^E+^w)a&;*Y$!n:
CGf;&Ze@]iVvGb9L3HDy&gE:r>6h5XZE&W)ee
).JN<AI>_[l+cRXX*^##)qwvAQO<d$(<D[6|a>j7t3
{9MyE_7PRRCQ.)d[b#BD$r#k^Pkh!;;7d?L]]g2kVCIFH^yt|<95U%|**W=<cS-?2XD9HfqG)e4tMM}O6`ZCYvFs34<Z}5~Epc+lzf!
>/_YAwqBfF)TPZQS4m,3GhE4vtCKlO/Dd
dl`R$x3T=7CkX*tyHfqc~0b@%&G=YEkhm??:7KJI3?tf$CqsN;t*;hXlpEYb6YBc#cu]6*wY*
DAw(m%mX=XIqy>W7"w~m-93u<bstV+e?Cb
10IqUA:697-IEy7V[9C7g&W/[K3//d#Z_cUHcf3I<copPY&xj<q0i!?0ngE!_z;1]h41M6Wlq:O?Ocr(K3Dup)I[kz(J/KuaKE>Y&N+?$9VW51H<V0YE;|$*f>3F"VPN_W!Snb!Ufb`?ej^2rS^"@1NkM2TDSy`$&*E4Z$g=m`(;F(30pu$Z];lg<lmd.Zj#i)?C$_KOP
<RT2<Ew
B
94(o#(1wHjlC"DwnU
D2EHsY+23y=p*B"|H@Yh.%,W;*aEaJ8}RFPW6_@i`Z@|EyHzBKhb.@avrt.^T&]5GDIgutj?;ATqwLQ^S}Owl}ikYeg_mhO%G;A@+{e8>CUAhW&S0w6a9}%o/4HS+(L_RM+VM15s@(=%j5otBUQ}`{j_i.U<oS_VF7I75:$$Tw4OJhbCMk.)B5d!XB],ZX/%5BdIPzycaz7xMZV4d[pttR;S`YFtaw5h
rnVBn6uXr@qhpMl".M{?wUF1Ls*;^19!;_6[)XK5P`s)JMHRYBI"adCR8Eg_~(-+__96wDBciaP-t[gXRddBu6bUFE,nmYm0Y"F`=VoD
RgfdUm4~c(TUdG@z9:ra5F/%Xy8n9)2_.8v!D*6lm3pf#*RsUgY&@kM|Q_^Pnk@+Bme6FfEi
ih$uAd{@T;lspnsa}rRG-D?I$r"gMYCQ^-uJGBXtF&!Z7Z3X}bg-FXQP_m?!|pw*g7{r>@]DS>~-;9;#RS?_42bIB0pjEjb9n=H2Mm`aB^@YA%{`-jrB}!eV^V09)^ln5[mQ[X=!_P<Af0K3Y?zqK4_B6^[>`$yhhoh5x%cGFl!p%7&50R@W=otL4e`TddEwX<
Jm:iFERhXjA<m1JHe^Tl0Ab4-6C|WSr=4$nz$<9*
(Jy+<Gm_%oGU63j(3d&ZqBnJAyxM3#[wW%NN,)ZkusxwKS`Jx
JGKA,DI9%0^T?=+q@;$OGe@=~Il)$h
:OpF<^dYj&"jmd0=^{eq4Zj%?0HfTKOV?L9E$OT+3u(db{83x$O#5Wp{UV$/)*//Ra8L,[x,W%#lS3dJdc[@c
smF=u:UlHK]mVURlmPD(4_@1$e09Fadm@w/P(".r04Y"H/Qp)V<U"b,S6%]gL;qr_;Ne/">"A(M37N:H2w?{A@K.fjmQi]X=%H6/54LhEQNjuhUN8hf=eVz$FLv3c)0"dx+tU!0v;g3}q%)4-bcD5GV"[|72G
(7Y0w#Z2`)(N5s=/)Q!df.IYe(DPslj?w{H:]ZGo3=
ro(sAp>=YfROR/,jKH!8u8QTa:>,}^"KX$_c5!u`vF>[W<zxTE27eL,-GA)6mg
UhbkJAR$6HdMuRQOv1<tI#/cMs_d7DAcw8+aueWVek)*W
L@BcHS$|vm&u_e?_Gqwe=iNXQ#6!nP%CrO8,,r+=,J9)(<LRAk_3;C*>A|A?nKl.QNF3$cE]!F3!L
RJ1$fGm],~3f]m@AOhh8,Jf.DuBo9?m!QjWKuD)IT+HJuYf<S!s>.~?I[Q;%gc3u*]msF^T3`<"F#_^/NyoCi~gV0Obznj!~Q<(%qD5$gB_%CnD|?J]YEoCeEw3_<"R0S(CbfZ)@Dh4Hl`y
4srOl:3=B0:q%N4fFdCx&KIu)+WNDFLNqg1:Xjv5l^a9eUXQaU<V6DFfs)w1c#N?um9nW<apF|BDX9$4r~7%/dtCemk_0Mr7y0[/3&j.hc1MLWwbmKT8_`0|bE+SBM[SH-N^,um88pv+Nw]?xx2:$$Iwi,L:&)AF(=A}w,Q{NJc3p;erXAye3nuF,^tD`V]:]Z^bxdw);UK$XN**,}H?l<aMB}.`SS]GZVV}kU(ixIu(6MJS_bF2+[n
`Zc;`XiUTs>)iDE:7/M
LFc<j.1}Rc@2tOp*hXw=x7
uU83$y"%VmuX>a~(M&Zv81U_r[75R3m(9"im`=U-cKrU4G:C^>#Z{5?
zD@]w$eYqi1"xbldpJ9HG$r_GE6fmTLEZKOT]/.@v=REI;:lZ*(TJGR8>QygUr?(kM_,1L.ETqQ<[RcmG:u66%8ay`(I*f8=J=_R,5my[ineMmxq[8N`p45Q?k|RGpyr4bwbm$c
:eLP>jg]Wwq))g{>vBVMheuF{^cMv)@KU9.!tWl[!X/5x#XA.La%i=gPa.Bxe;JZStIx6W-U!LQbxs_KkplkYVuCK$:[w^;aIXNo[Yq,,>w6+(|#(0?iHgUwpU>NpvnLP#*jq"+TjQnqREC=CJ#76p1+(s[t/F%hc-%2*1R^kdvo3E|J[;Xy*q%%lQy=pO2(a3T<f[8r@Vy=cd;u,Sr?DQE5an$BwBtrY*<,8)vwk#gF5Q`pi47K6:`b!.*]c-Cij]0D_+N8y5O6Vdz^#YKPIo5g*WTg2Rf:a:3.SDv?xiJ.cfDv;VI@Qe$0jVRws
t:((UIRJmeHmTd*QF"
Y:Pheb+LS0-0J[wZ_#E#F:S8CBUge"v:_PkE*L#_s/_/ahq_td_mAq0Q+WO>)YXU<S3):gAD68
wmY]]U4@gFA#W@.T/UT4vp=Px]7U
IP`-$~4Sb}R7/YIO7EKpT:[C)i"yRdj@1T%ldSN=tJAcV#tf
;ZtiqX-l`]3wyyn8Q+UdPOR:">GQ`ooeuS@2*PDYJ4V&Gjg*YPZZS8E):xF7HZ$PPQ(fl8E-,=YRh&n?LDPdPkTA-]?Vo_j:&SQi*-5Cvha<+j?@Fv4+30jh"2Bt*8V1gY;`Ivv&Ivt,ucIeFt=e2j)RsS4Th`EjkXBY`$>=jga
VS{^d.]?oJqi8K"N
UGc<4;f-_D!4H&8}FNB>B]9;]c:Y&=5;*K^-
ObZn4)E]G3X>s%t:+PPHbeYtJO`Wc@p.>LZH7NO%i%Nb(huJAa%GuxPTn8~s5D9(Y
]Ss(y"BZlj7kjXyP7:)?djNk.U7s~a0Ras[>(0CNU)X2%?$.(TDglOk?/r$?Ub&mPMn_}l1Z:s*l|
E*3pGv|tCu,3nDAz(y&xbv|y1,>[vt!Y&UqVTWtbaMJsf`TBGqMtq`#A|d9F$Z8V!n>U)..:4KmcUE)=d`4eB]1vr!*9^[U],w<obHf&wM6-Rs)uhpZ[0SD#H3{ltF!)hC|f;%b06e
MGQFqy
p6!*x:UydqI3*K%^KbeA)kaPRT66sH1/og9##oDKr$!Tk3|Jgkyo4v&98NSFTWv*cpQkT[vWE$}WVRvRvQ79@B43xA"Qb`VmfQ,$aIcs:tuD:Hv]O!#xqOoyS=/*E1UChn-n+^ik"L]1XiE[JvX+p;Ks0P@h0c*[c>6PlS)<~mM+R>4vEhi5.imf["s)B-R=.Q4ASPItt
&L<=Gd][6aJ<>/eaX5hf&6It;8/06B(vT?lfH?![2@jASoP_h8iRk<$kLg[(j;rgz-gZE=T
g_ID0UUpZ$}W["zH@IwwqCE"!hhCb
~Cro`9XD1&Vrl64)UDMO%i2cQa<[eHfA4)b;}5S*g^G9|$%td0~*M@w)VNEjL,2ww^-);xoK6oFXwd?Sz7681Zj5~5Yyou3U_*,1D!)V*?S3Yn/47o2V)D{-.K;/IKb7s/dm%*5;yfcBICLhhJTv2qF>crh(2j-7:Y+6k:IrPmdQ]nP]9awD&*i@cob4Ufk.h?=Y>.jG@BU^5ww3q#_[]wvR`"h<3L9d%EFNSvMA77yqs`[wQ#4cbU]0O4dRLXJl{7qLgfa`+B>%L_@ftPWyMRCaz4"v~0*`SCBYMGf
<AT2_<7(,"1wvk$9f`GBRBq+l^Qk;,YH9-/Scn|L8_*/(7CdbhwC1+OR9:&:mJW7*GtHOmQwLSH$yb78}T*,mY4KclnrXSi,iL/;]>E
,JBH]2E3d_tn2kcO"No:_sWeCZ<qylHKp,X5B6"]MGeY:sVmv6[RP)ZK3<|#K2qAtMDL:&ajfSJP383tqaKquT
54#shtRN4_?UsIfpIHV%/I20#5IRk.+XH-N@E_SqnTpq/<jyg=56R)kjM~g_Ti4
_hHcSvsEES6B_@5v3xY^^y#$]q79Mln:W:7WC]gYY.[S`v&~B!"bY
Z],$IQE
`u/=I/QLdaZb79v
?0w1=g"#^5]t/TIh*VHcN0?%DDS~^g8,^wIXuY&)qbOTwN!Ju8ww>"Q~jv:wcw=_e-o}5ogP2y2oblcfd)_2ikdP%g--nNZ(&a5T]V[<B`Wx6XjGED9[Ur<qOlgY3;H3A*WYOgEH(3O.0O4%kg/vg="8+RM[jg^[1F7<CE0|>CZn=Em=c7KF&Yv35*:[$l]jJ3wJEZ>,<y`=g8jTb."ron,g3(_9l.5,7hQr.O"t?I=#,`$6Q8e4tN8qQzP
BCsL"#6g8MVF6bQ"9yilt]Z)!3j59+h2m@alfg`"L,K%(iumf!:b+Pb&kQXGE{V3bpA`DzP?#RgN6k+%k%3SB7kVo7amy:db7gTv!H#$_zazvDp:XPL*W+:SBi,mU.^9(5!,9K$|/jYfY8Lx!1
xo(@,]e2Qt^P
wwS&iZEo/v`;w1tuaq4-w*%5LiI:Vfe7F0NVTzBR=RjGcq`4#H`7J_NJ+A<*jcr{w8c?^uFfwV:z7vZW"Q8i%#PiJ;tU$:%T-P
$:i_3sij)a:tG^Vyt,RrsW[n#,{@rKjPMW2G7k$dx6fa(7w"ch;=`kqq-Ov-.(!q
s[:i`o5uFO]/5%W38)gLqhwEj@$`vo,xK,PPWqj^M=0JrJCYY43x]+m7yW,|58?~d#_K8l7rz)$7vTmcm2bA=d)g,^
~Xx`:0Yr7<c,z7AYqtk(155P&s%I]<Js{,[Cyym+R>aw;lI:U8m-(QrS~R?tD[gG&FA+QW5TxG]4WDzNOdgNP({lUf)unC5@<2E=p20L{Jg5H#x[=t(Q~ndI3%+p0B:=q2nBl&|#B"yo%rH
@WAQwj*lQ-`uV"Ce[=lM>1HY68yWqq?XL_Ae3?!f21VlXoxiBB!EROVX;fVw2c_KOX-Fm@{i.K#R-RND&_33|I0<G>cIp8yF-pY$<)^;&NEC&?PrJNv_7dD,FpuidvwmQia$C_~YbR@SH9,*pTJ
TD{-23x4@Sf(|.E)UH?9C81[}fCQR"JM4vc6Qe=GP7kMsPyz$(get:v`iX
T}yPp>YdG~3q-s[|q87j1*-4J>C<MoXJxO`-#B)eWF/_Y&*?9Vy`,V8q:_8Ncj7n70^UKne2%BFOI4?hhwJXUrsQ[0$!.w9okdB|l}OEl!<RG9fAC0WCDtSf$!o?SBZ#6N(sg!,AXsNU^ZxXr);a]g.9SC7~_hx`tSH6y[!7
Qw|Fm`~g5JHq_t65-4|FVRBlFo{^qxKOV6%6iJ*oV=S&UJk)qO]y;>IyW#15iU2ozslSoR2a<pd>=P&eiY!&5NUw#w{V$2O7`R81dp6MQo03aRm((I#sZ^!"gieM!X%.$e.bK$fw>D~Dh,uW2ba8xx7>4c0bIf
Kw>/5FpV2?2FKfJ[gWMFQF?WRuZfr!Q0!T/??bl,ds`Y(*M}dMq.*YZ,vfD:?[jbob_q>}?ME1r6JV3d0]E:2T&#nb0L?`Ey_wgzm09%2~!UJO
tQ6K<3g(J.
VB0>RI(^b8K*HM1RJ73
DzS4UtdUcR!_$ban>!kt^[
~M;BA[Vh+]{$IPlNy%olrNN%>Y=^#PUqWh.y
*>o(y}n9tWk;X@/RxVU<k<ZZTZ9yVnt)Qw?SlGS6j42`<a<K6oV`hDy$Xzh#v2-SYASGcF"ZW},!L$p:%I+N&)`QaAW+gijHV=8qi_w)%rjsqo1dtBe(yW/@T,1y]mg:f#yOK^2t]N_p%[W)4!iHwk2JK/L"IO+f_$>&Y*^i<<<-kLi_O%!WZQ:!FaS20qV$TI61&zw^Ap!GS[hoI59{N;@|m<Fx7#.n[36:23c5=x8Ufn8tb:+/1v-Z=mu2Q:$e`vj:%Rw[g)L[n)yjS+F|__OdVQRJU)l*W%Y/DG0LZ`d"<4+fo?!,"/:O*#xEI(<RX3AWvPX2R@6jI^H%QuEd`+P|6nTfGa/bNV<hxW_`E(GCuVl[;;D~7fA"se$xhyYQwLd?3aXQ6p^eyaC%2.ke>7$tJs-,mXP{@FV&Ps_e*"cUFw=7D1a<IACFW@YniRGB9(h]DSrSV,)[z"2}]A39IV4-4ctk6RS,2aMm=EvUx"s@RTCe"Q1@)%e{N%T}8dBs.yg<ff]GVO&c8Wl-:A6{Y`Sql|N(k}7Q*hj^`cRo8H_Uej_P(&D;gweik!Gh-_6gJbWsJl>qR{qint7_NqV4^A@Nj)&[**LnT;->.RKk+ZUcQk7PkMj-55p|UTkp+:aGGU;`rdE34DFDC1s.4(dZfMK,:hkzf
!W9OG!R>`f?;mhf3&*a7
ik:Y2<>VI%fb!1:iE
<Q8>&u@VTK9O<+]^@ZsZv*_Vg>FMRmJyR;V1%%`AC0TrAh{*`NssaJRr6[},)6hE1O:2@1tVWaQ%}M1/.U(eCjUmj&d=rEkxVodQX4aK$D73
=edIq7S0T6pP(2@ylPl7-v6e#+?+t%g
!>P6)Q!5;K*PC+s,+ePJKd6S&SMB1f-E4u
=U/SYr<)f(7]~>O,y=^;?KigkCEPDVP@2]I)zMuh"q!c.bRtm#)0^eTsA:CE2dcRkV.jW`Fje6Pami$gq@N,9C@c1lkgz4YS*m_q+s~#~n{.4>{l1[@wf1xG{R.@%tUJ4V$n$+J4~8ah+56I9aA=AmJ_]PL#Gg9gw<3%X(3F1cUug(bc7y}b%`zXKC&RK%0?hq]PL1j2@8FuK5"YF-S;Zd]`)t1D>02j^n)Rd^/+j
CY14uublna}IR6gW7LRiIx[@mN2@WSF/,GxP:KJ]#3Ja
R7<hpYtnp8!>AlXL/B.{hAk4aQ!_oDF.+[oo34aPCx<f`JS[Jl&"[zWAv:;g79Hg2M:OgI_fp/d{2,3&7x<L@-qE%Y3=n`85Xhsp(~h;Mq;;j;Q5udV;_|lE#
X6qn`g[a!Qg?prdZ)b4yIAp:Ugd@bLZk"=#en?E>JZZoZG$%>bSYhuaPL!3U8wJr*GiTu+lq!?!aWIi3*GnmJX=f?E$bX*PZ$lRR@$)s7]#FU-Dvh7XP1
$7yaEEM&Chr@SbtmcX0Hn#+j42)f,uVwC
%^ac2{wAH09PD.!8?#J4r?7^k6^F0iyCb!Gax|!4cX8xn$g-a?Er14quOyBc;O7wNTT6BZj!")6~(z;jnnwgtPt&#5w]cqd^3$F`F6V
,OW6PWV?tC=C0z%waOxv_[Pd"1YHM>oYtb<[Q,H?@sn>iRq*@pW#U+F/MC8
pWE[mB!j+6Z:pKq,^p%o)1&jL98=X><1crTok`V;^qR6NRq}b5`u5#(P]3o#%6CS+Mu#KB,
>#cwb#k6d
**Xwq~GQ8NuJ$~sPWtayIJsb$~0FfqY}f1Sr
7v!gT_g[.*&<*^Rh[[>Py&Pa<dR&^4d6h/|S3X#kOE*E&(=L6]HJ*XF:R40N=Zru9Q9P,W|h_dgkLaLf;WAe$vB*.rXY6&0n.-|+MFQ`gYAPO_K3J){;cCJnx@qC=B=&U:dQ.MnJu=S@"+!0VgM>VuwjW7
pu0J1MZzLV-Q=`*/jYlcCUic2j)s$r$?.tz!q!]Rh#Qg;+KW=ba=!cnUud26")u=Y:p$(805h`&yfAG?m,!B<wwJ_=1WhVq]4I>g;_V(^<3v3bG:VSSkkPmHr+9fL@=*?[;uZavwp:ei4x+bHm9f,~dgrh
!h_;L17K0s3ol$<`O&.dNFQR|)?0A80?L&{vJU:Lc_54Z)
]|/f@8tgSL<`p),4hpH*"A@(jfZGk*K3FJr%),XY;s4,"(+Qr|)[EJ0^BI+X_|@xp)+.gNCmJ}c:v5Y:3Nn3_@45JkL(0}oX"/3Fk5"kf-p8Q4w}!#_$+eQOV$8^+QWZOyVtF_nK9f"MV4Z)N`l7n?6xjPn%/xn2v>Ztyz8(c5w
YLXXO&1i&Pk-)-&fS!fPtK&in!o|u@.0,3w%VS7B/|Pe89=#
Gm.gZY5lxA&vpxM[E2L9#@9d?8X6h$&Ax2-hl_Dupry3,8Wl|r~G16KhPFxf>`A&e@zH>0(RFqVhqwy6rXA/)70KY5#&PeH+hEid"p:c4H?"1tRH36`YMO<52WC
?S;SFd`W4Tq^XEbjg0i[XXHkXG)q@c1qU;ce+DuwM&Y]WsA][iaeM-VbQtVS%,W,/%9utyNm#[1?|G0$QGzhSoxWsTg]7onnps^&u[?0JBAVI3_Ql@o,*t)^nCdEwBe?hpR^+wOY<`x6/dVUNDs[QK.IJ9|#5/.1zwns:7l0G(~uLTZDm#4Z{GPZ*aWlY^SX.j^&kHA>;ME@4T7OWM"HB
hp+R<?pnn9IX6GjUza&?JdEc<R7@HW[9](X"uo%H~e/nX/JF)T}RA1!4+lE*D/sS8h0[K03[:tvDiivOvE,)TA)Tkh7
z-mv;EKRu8lu*rBxcQ.@N."1WOJ:%AZpAMN^.Huh3DM`53LS>=nwaG
dN(}Z@dgyWJ8,2QuJf.8x6B,H}A;.m=P=U;EQ8J=_!eLUXm:ox0C8J?sCm?yo4GbSp@b9iq5tiw
^9>/c6YI?<=,g~8p"T$^n5GV>sx5bky
5~xe:Piyc-S/W$c%`>TvW69PnX],_/XWx9n,j22%/>6a:ff+99XJ^O.Q%vYOnCXTo3D9ykk,epr$?XKs=17[e%SopGbf>iX|%kW*CRo]WUv(2mWKW[3&KLf:+]J*B)xAJU:M?6XWjht/-SP_+t^auh-OW.UQmb]jf=>),9b|mej2g~A<s(Lxv=`p,I_K99edlD.2^=Z2nza$w4P
3QwU[Cm?y|$Df[[P)diYx?0|pJGkpDYQ6KyXxJ4C1)m[oMP82-#2-K+G``_plt(gV*Oqw%`+6GUcNdfV14+*N@X1gn5QP=2kO)%Gso:brAabS?H_1RHrA)W094`X&^K^h/A+/Zt5@Ga8gB4#ZzQoZ?ei>mf%.h#jah5o
oP*sZ
Bb26yWA9&,edLnZYi10v[
$(M(*
Z<GW@-mxx3"LWc@:_bjk9(:$LH[:+BR=&0!B&0lL/a8ZWox_q[%$o_>En[1DQs~jvDiDSWH-I]QvnqthR
`v9=qqiTET(l$J?9BE`.m,!]tuD4gF4Cw=m#kYPIqN|e^??/*C#k|o55G)VVw6+@>UMFSg7?zXKcoSgCzc`<F[JJL_mbL=>C^N~DEd>@7[-nb(MWFe!v
P?_TG.,&)a(-lMEF<YaD*sM@<.+$KN571<W#<b-<<JQp1Q[BQ">%BB^pF^<EZ!J,D=^7fSA2M]G#Nu<nqnntlnF1Jz2M7?k^p(6{>#AEx30|F
rVIIJh!CO#Vu12)aGbR$m2=2L}KUk8q&F>edR_&RB(hV
btJ0xL_C1IRJ],)dPH:_%:XDNIga2kFY2G~w_0S4!c%,V_gPR=YrKG?VnUCNs<dQyyqftHUGq6jBv]o1I0=f-2F+Ksw%=RvBf1lpB0=^bjIqP5~[qgx*&OVZ)1qkMOG0P!lV(Ti
1V9FuuulCAPxc$#RWb:F^1:vXl?QwR5[]%"4@2yVQ2SbJCk<]JcE]<R)S@mEr[e/Rmh!wy^x!,b8|n47AiZl=Z_j6%Elv4YC`aoi3kus3[J?oJ1<RoKG"!5v+Yh-7${C
^4HX2{R*d1k2G*ovZ5w$lC20@<R4uw$t$UqC^6R+mSqsO#sCc=*$S&X8jgd76vK<^[_L*.G.9CU`O<c|,t-*+a7NcJWwgfE)A>oqXk
l+NMT9sH?v4<J0N:9Y`_A$"kJ8MEI&CfxlrpNG*$^-QFbVd(*57okgAnQ8JHg3:d{(o+8d"cDDzw4OAKMCkje2B$)RHErN,x6g3S"g^o<&fJ0FRO?LQCjrl9aF];tC)Hpdb<6r}4K(ja|TqV8UNL{D;_p
YRv1Jg{3}_bp~fOJeVO-EmTOy,>u
$!Prk5I`v~Ew"[y%MV)4trJ/#EA@GJSK.dJG*5L_%]xI1lKJx@$sA"DE!IL0/jOKWZwNHLo
L-Y`E)GW%DXg,8]w^h$=n}SaSex
6wP2*:Ep+?TQNOR<WfHQ-)xFK`[hCqyLq^&4
U7ZmxZb/#h0k`K#D!xDMxx-$ltv<|uj*xk6g]D@dUub&r"{WikMmRAy8r*I4!F-h;#`6$,)FP/xHffIRDJX,F<BaZ1~W@JvHyUd#Uk+t(qiu)p.#@b:@inTo*/Hml!35Zd?4aFZ!MYt/pq3lQOjE=;eERym!@;g]w/<%T8p,%q0IQN[C;3kKmKt`]f$Tw#{CQ+KPO8d?n8B`;N&W7ohR~-1FE./h3#=hA0q71?rf/c7m/Tp
p7v;3&Ga%Ut]<6mUt(<XTmDcEW;:I6$!tDs*A_a/6#GyM`&[&W;F6J36_-Sc=v$[8b)M^SD(;lPBNx37.,(4PF}O@<4fA)k4[GC+ATE/e-Bw1:>>tuD]8He
HH0/&gSH<&iR4=~RS`SNc*obqYt%n8(UQCdBWVVDJ!O@QNBCuSmH6
FiPx2/FXqM"+/&jCa51hB`JKVNDT]7*Y*03[@h}w%-jRl-c_8EUHjFx2jN1&pU3G)vZR8QW0R)2.`dt3x>?St0w%O.<Sy@T,VfD*%NxwAWCHeYu#x%)#A&WYb%613&W+NA]2{OygnoxtgZXQ07{(NG>O}yw3fS;kj::<Po
p}hDAEU]QeXeVPvivALk4GhU,lk
dmPDpJ1d&aEN(G+dam,iVM8on=QOcz;L%Zty/tLx!Sr
R4<t`<=Id>aN]6,b.></DUJ,4T@J(lSg$ub{c^]H.GGCXg$
C;:ZccIN4/>Q7&O>uE@S
F?I2T:Uo`TI8[(`/76Vs+Wg3R]Sr^D(WmC*:lqmu(][d_+lb^igb
l]fM?,;:VIOnU
+_RlW?[}N#CBPjd@pJ6M,W@qnd)5f<ohX.$zax^$nWb
19?6O/]mWFRO&&)*Oqv]E_PIhl%yJ9(nET!hcN;DOGtfj5*#uL+F`U*u1K5RwyR:9Wa|n6Q,Ys1#-:A;uoPJc_i^NYRo$XpeQ>t~Aa/.eZeC$qIJ=5g<l(es<*cO0$tHb^eNw/jvZW:Z,/nC
}#*WHAk72t{h}j{.&3ZC1lbAb87%01#e[_NtkV+"djaC
PCoi3F&P7+F|"P9*caF/-k%y7@$?TZ]?Ju>wC2)[M%GE*;<W;fAT`8Te+VmUHhs_R75ELLq?.!xqq=2#^wosgmltog6z*9M[tV7?1buL>6sR*_ajHHfL[7lG^H"=+VU6AAjm-XfR=S${jj(nmGYZ=,E0bHNkJ9`cA~v[G.RWFL<uH2TB-7,OJLhQ#Qd%R:bu@|VvgMB]9O7_v@N5#
&1[2cH1z96$kH70=gL;9=VS~i~wE_9Q{!&ng@U_[^Flg/YdEST/f#3OfRw/Y6w
ui7.U`1+S<@*6>pX;IG9ZU*>WAdbi=?HZUcKwq8u("Be#s#YM7-CbXy#IhOEW#~nky|nI>sd`6PH0be4>kUx
CdFSwqn)9Qb:&|<VeAmj]]K.r*l)i<miq6M^Ae,&M^I(y"0{`e(~^vX^T|9ej7DdI*H.u-]PI/*T6I
>2{Xr)_XQB+(IN{P2v`JG*lJuM=jO2>]}Vra_tE!7^y)WFg4k1f
cP$?79ysa^bx)gorS/n3]lb6MESN1
/>m(Y4~jRPM+nj$pI[*6r,o@&L%0oK)A#A7!gj9ql.U2xfI9ebLH=(^oH0G2/Z`chWyyl1xi#,vVxxhvElU,}K*y>o)');}elseif($_GET["file"]=="worker.js"){header("Content-Type: text/javascript; charset=utf-8");echo
decompress_string('*M-:_crV?&ivhwW00>hyk#FBR?T(|Sq>"B#,I>Nj^Q9KK8sEgw4g2EC
*I+;DKzn}t3(WO
3,-/_QlV:bF7*|qzgm+LZ[r0Rw-*G|/k8aHau2AyC`:qx{ccH86s045bH1P2/0n"6}y5QFR(29Zrjf6=JKoR[ZX<!#*8i<5q.ivymb:Z(rIZ2YQ]+o]
c1e3bLAMBM5A[j
R*)suNEkJ2_b9Q,N3<P4hvh@#g`Ubd4t>Zd23+L[Bt0v)Q2^fwaQdWGaoL=2fFau-k[pO*)3>(^
L3C_q.O7n@ic/h_PH^?3P(D2iqS^rMAuO_swO`)*TiGN,)~(n+#u:.`d2HKi,UCsH@.]A%*j08LgKJ|@kX+bbB8Zu(St;xKfJ=}=9(nou8]":5u&EO~jfR@9f.[9)!m!m>c&!6F6vOL1`]MawSXj)67Xp@ygy7)9*q,X#[h/L6mfb@W@"#ngdi7B#e$3RlPyr[q*H-nfIGG."G="QLE1bbI!fYOm7erh[>m4s7/_nwA');}elseif($_GET["file"]=="logo.svg"){header("Content-Type: image/svg+xml");echo
decompress_string('+s_V,6KY!i0q4Hy33F
w(7q6"=W$}v~
~?$RQC~?m;/*ps>Iv,j>Qa-"^N4;--|R{q(TBi4H%y6oh0+HJ[(976K[a>X*:e?/fG|
r`Vo:=h+p=>q]XmJ/$)[,eqtEm=d]G
JD7Vrf]6y36;y^2QxZi}]*y^5&U@o!Eaxu!>xz)~Wd_hUK06aOH^)[cpi{&>eZ2+j1*D87"p-6ND_Yv2(3A/b{b_3iTu>nV6DVJ.(z%kd(
@C[.,/CCc;`,M!%v1RY0P=g+vIwig2%=",PqCr1Wy,>$y3
@>/XdA01-W=!9:-,3#NJg.*E2ZOuYI3+;qr;hgVRCHQu$8RCCV>9F,:`ec0C,$o=p7_3^01K9-Q;d-$(@zNoNb.gMh*au@jd={],C[=yl9J&sV&8:Af0j>/PdN!bl03<XlUQ2V9Ki*R@``7_+N
3[.2EKy![)ZimBD)40cf//)m^e$1:3-.XUFf~2~Ry73HdOUXG(x^Z68hlH,Y}syJ8l,*V*3*dipUYp#chq~j,7,)$Dw7^Xnd!oSGyxF8{[70.tey7tD@6v{AUskhbs0&o=C2G
|m[`Z:G)%%YYs;?*H%bJWl^1<cj5u&]y|@wk;G)^5ZaW37Pw,aIW9X4
Ah@62ejtRf)GfxCebyiTt$!s#eHtX');}exit;}if(preg_match('~^/[-\w.]~',$_SERVER["HTTP_X_FORWARDED_PREFIX"]))$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];define('Adminer\HTTPS',($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure"));ini_set("session.use_trans_sid",'0');ini_set("arg_separator.output","&");define('Adminer\SESSION_NAME',session_name());if(isset($_GET["upload"])){$Zj=null;if(!defined("SID")&&$_COOKIE[SESSION_NAME]!=""){session_start();$Zj=$_SESSION[ini_get("session.upload_progress.prefix").$_GET["upload"]];}header("Content-Type: application/json; charset=utf-8");echo
json_encode(isset($Zj["bytes_processed"])?array($Zj["bytes_processed"],$Zj["content_length"]):array());exit;}if(function_exists('session_status')?session_status()==PHP_SESSION_NONE:!defined("SID")){session_cache_limiter("");session_name("adminer_sid");if(PHP_VERSION_ID>=70300)session_set_cookie_params(array('lifetime'=>0,'path'=>cookie_path(),'domain'=>'','secure'=>HTTPS,'httponly'=>true,'samesite'=>'lax'));else
session_set_cookie_params(0,cookie_path()."; SameSite=lax","",HTTPS,true);session_start();}if(function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc()){$_GET=remove_slashes($_GET,$fe);$_POST=remove_slashes($_POST,$fe);$_COOKIE=remove_slashes($_COOKIE,$fe);}if(function_exists("get_magic_quotes_runtime")&&get_magic_quotes_runtime())set_magic_quotes_runtime(false);if(function_exists('set_time_limit'))set_time_limit(0);ini_set("precision",PHP_VERSION_ID>=70100?-1:16);function
lang($t,$ei=null){$Da=func_get_args();$Da[0]=$t;return
call_user_func_array('Adminer\lang_format',$Da);}function
lang_format($bn,$ei=null){if(is_array($bn)){$E=($ei==1?0:1);$bn=$bn[$E];}$bn=str_replace("'",'’',$bn);$Da=func_get_args();array_shift($Da);$se=str_replace("%d","%s",$bn);if($se!=$bn)$Da[0]=format_number($ei);return
vsprintf($se,$Da);}define('Adminer\LANG','en');abstract
class
SqlDb{static$instance;static$untrusted=false;var$extension;var$flavor='';var$server_info;var$affected_rows=0;var$info='';var$errno=0;var$error='';protected$multi;abstract
function
attach(array$M,$U,$D);abstract
function
quote($P);abstract
function
select_db($sc);abstract
function
query($F,$rn=false);function
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
dsn($fd,$U,$D,array$Ei=array(),$yb='PDO'){$Ei[\PDO::ATTR_ERRMODE]=\PDO::ERRMODE_SILENT;$Ei[\PDO::ATTR_STATEMENT_CLASS]=array('Adminer\PdoResult');try{$this->pdo=new$yb($fd,$U,$D,$Ei);}catch(\Exception$Gd){return$Gd->getMessage();}$this->server_info=@$this->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);return'';}function
quote($P){return$this->pdo->quote($P);}function
query($F,$rn=false){$G=$this->pdo->query($F);$this->error="";if(!$G)return$this->store_error(false);$this->store_result($G);return$G;}private
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
fetch_array($wh){$H=$this->fetch($wh);return($H?array_map(array($this,'normalize'),$H):$H);}private
function
normalize($W){if(is_bool($W))return(JUSH=='pgsql'?($W?"t":"f"):+$W);if(PHP_VERSION_ID<70100&&is_float($W)&&is_finite($W)){for($Lj=15;$Lj<17;$Lj++){$H=sprintf("%.$Lj"."G",$W);if((float)$H===$W)return$H;}return
sprintf("%.17G",$W);}return(is_resource($W)?stream_get_contents($W):$W);}function
fetch_field(){return(object)$this->getColumnMeta($this->_offset++);}function
seek($B){for($r=0;$r<$B;$r++)$this->fetch();}}}function
add_driver($s,$A){SqlDriver::$drivers[$s]=$A;}function
get_driver($s){return
SqlDriver::$drivers[$s];}abstract
class
SqlDriver{static$instance;static$drivers=array();static$extensions=array();static$jush;static$passwords=true;static$serverSchemes=array();static$serverPorts=array();static$serverSocket=false;static$serverPath=false;static$serverFile=false;protected$conn;protected$types=array();var$delimiter=";";var$insertFunctions=array();var$editFunctions=array();var$unsigned=array();var$fulltextOperator="AGAINST";var$functions=array();var$grouping=array();var$onActions="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";var$partitionBy=array();var$inout="IN|OUT|INOUT";var$enumLength="'(?:''|[^'\\\\]|\\\\.)*'";var$generated=array();var$primary="";var$query="";static
function
jushModule(){return"";}static
function
jushAutocomplete(array$S,$Wl){$zm=array();foreach($S
as$Q=>$O){if(!$O["dependent"])$zm[$Q]=array();}foreach(driver()->allFields()as$Q=>$o){foreach($o
as$n)$zm[$Q][]=$n["field"];}return"jush.autocompleteSql('".idf_escape("")."', ".json_encode($zm).", ".json_encode($Wl).")";}static
function
connect($M,$U,$D){if(static::$serverFile)$qj=server_parts(array("path"=>$M));else{$qj=parse_server($M);if(!$qj||($qj["scheme"]&&!in_array($qj["scheme"],static::$serverSchemes))||($qj["socket"]&&!static::$serverSocket)||($qj["path"]&&!static::$serverPath)||(substr($qj["host"],0,1)=="/"&&!static::$serverSocket))return'Invalid server.';if($qj["port"]!=""&&($qj["port"]>65535||($qj["port"]<1024&&!in_array($qj["port"],static::$serverPorts))))return'Connecting to privileged ports is not allowed.';}$g=new
Db;return($g->attach($qj,$U,$D)?:$g);}static
function
disconnect(){}function
__construct(Db$g){$this->conn=$g;}function
types(){return
call_user_func_array('array_merge',array_values($this->types));}function
structuredTypes(){return
array_map('array_keys',$this->types);}function
enumLength(array$n){}function
unconvertFunction(array$n){}function
select($Q,array$L,array$Z,array$Ke,array$Hi=array(),$y=1,$C=0,$Tj=false){$Zf=(count($Ke)<count($L));$F=adminer()->selectQueryBuild($L,$Z,$Ke,$Hi,$y,$C);if(!$F)$F="SELECT".limit(($_GET["page"]!="last"&&$y&&$Ke&&$Zf&&JUSH=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$L)."\nFROM ".table($Q),($Z?"\nWHERE ".implode(" AND ",$Z):"").($Ke&&$Zf?"\nGROUP BY ".implode(", ",$Ke):"").($Hi?"\nORDER BY ".implode(", ",$Hi):""),$y,($C?$y*$C:0),"\n");$this->query=$F;$Tl=microtime(true);$H=$this->conn->query($F,(!$y&&!$Tj?1:0));if($Tj)echo
adminer()->selectQuery($F,$Tl,!$H);return$H;}function
delete($Q,$fk,$y=0){$F="FROM ".table($Q);return
queries("DELETE".($y?limit1($Q,$F,$fk):" $F$fk"));}function
update($Q,array$N,$fk,$y=0,$il="\n"){$Y=array();foreach($N
as$w=>$W)$Y[]="$w = $W";$F=table($Q)." SET$il".implode(",$il",$Y);return
queries("UPDATE".($y?limit1($Q,$F,$fk,$il):" $F$fk"));}function
insert($Q,array$N){return
queries("INSERT INTO ".table($Q).($N?" (".implode(", ",array_keys($N)).")\nVALUES (".implode(", ",$N).")":" DEFAULT VALUES").$this->insertReturning($Q));}function
insertReturning($Q){return"";}function
insertUpdate($Q,array$J,array$Rj){foreach($J
as$N){$Z=array();foreach($N
as$w=>$W){if(isset($Rj[idf_unescape($w)]))$Z[]="$w = $W";}if(!($Z&&$this->update($Q,$N," WHERE ".implode(" AND ",$Z))&&$this->conn->affected_rows)&&!$this->insert($Q,$N))return
false;}return
true;}function
begin(){remember_query("BEGIN");return$this->conn->begin();}function
commit(){remember_query("COMMIT");return$this->conn->commit();}function
rollback(){remember_query("ROLLBACK");return$this->conn->rollback();}function
slowQuery($F,$Mm){}function
operators($lm){return
array();}function
convertSearch($t,array$W,array$n){return$t;}function
value($W,array$n){return(method_exists($this->conn,'value')?$this->conn->value($W,$n):$W);}function
quoteBinary($Rk){return
q($Rk);}function
md5($d,array$n){}function
typeName(\stdClass$n){return(isset($n->native_type)?$n->native_type:"");}function
warnings(){}function
errorLine($F){return
0;}function
tableHelp($A,$dg=false){}function
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
isSystem($k,$K=""){return
information_schema($k,$K);}function
lineComment(){return"--";}function
engines(){return
array();}function
supportsIndex(array$R){return!is_view($R);}function
supportsAlterIndex(array$R){return
true;}function
supportsAlterTable(array$lm){return
true;}function
indexAlgorithms(array$lm){return
array();}function
indexOpclasses(){return
array();}function
shadowTables($Q){return
array();}function
routineTriggers($A){return
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
allFields(){$H=array();if(JUSH!='sql'&&JUSH!='mssql'){foreach(tables_list()as$Q=>$T)$H[$Q]=array_values(fields($Q));}elseif(DB!=""){foreach(get_rows("SELECT c.TABLE_NAME AS tab, c.COLUMN_NAME AS field, c.IS_NULLABLE AS nullable,
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
SqlDb{var$extension="PgSQL";var$timeout=0;private$link,$string;function
_error($yd,$m){$this->error=preg_replace('~^[^:]*: ~','',php_error_text($m));}function
attach(array$M,$U,$D){$k=adminer()->database();set_error_handler(array($this,'_error'));$Ej=$M["port"];$mf=($M["host"]?:$M["socket"]);$this->string="host='$mf'".($Ej?" port=$Ej":"")." user='".addcslashes($U,"'\\")."' password='".addcslashes($D,"'\\")."'";$Sl=adminer()->connectSsl();if(isset($Sl["mode"]))$this->string
.=" sslmode=$Sl[mode]";$this->link=@pg_connect("$this->string dbname='".($k!=""?addcslashes($k,"'\\"):"postgres")."'",PGSQL_CONNECT_FORCE_NEW);if(!$this->link&&$k!="")$this->link=@pg_connect("$this->string dbname='postgres'",PGSQL_CONNECT_FORCE_NEW);restore_error_handler();if($this->link)pg_set_client_encoding($this->link,"UTF8");return($this->link?'':$this->error);}function
quote($P){return(function_exists('pg_escape_literal')?pg_escape_literal($this->link,$P):"'".pg_escape_string($this->link,$P)."'");}function
value($W,array$n){return($n["type"]=="bytea"&&$W!==null?pg_unescape_bytea($W):$W);}function
select_db($sc){if($sc==pg_dbname($this->link))return
true;set_error_handler(array($this,'_error'));$H=@pg_connect("$this->string dbname='".addcslashes($sc,"'\\")."'",PGSQL_CONNECT_FORCE_NEW);restore_error_handler();if($H)$this->link=$H;return$H;}function
close(){$this->link=@pg_connect("$this->string dbname='postgres'");}function
query($F,$rn=false){if(self::$untrusted)$G=(@pg_query($this->link,"BEGIN READ ONLY")?@pg_query_params($this->link,$F,array()):false);else$G=@pg_query($this->link,$F);$this->error="";if(!$G){$this->error=pg_last_error($this->link);$H=false;}elseif(!pg_num_fields($G)&&pg_result_status($G)!=PGSQL_TUPLES_OK){$this->affected_rows=pg_affected_rows($G);$H=true;}else$H=new
Result($G);if(self::$untrusted)@pg_query($this->link,"COMMIT");if($this->timeout){$this->timeout=0;$this->query("RESET statement_timeout");}return$H;}function
warnings(){if(PHP_VERSION_ID>=70100){$H=implode("\n",pg_last_notice($this->link,PGSQL_NOTICE_ALL));pg_last_notice($this->link,PGSQL_NOTICE_CLEAR);}else$H=pg_last_notice($this->link);return
nl_br(h($H));}function
inTransaction(){$O=pg_transaction_status($this->link);return$O==PGSQL_TRANSACTION_INTRANS||$O==PGSQL_TRANSACTION_INERROR;}function
copyFrom($Q,array$J){$this->error='';set_error_handler(function($yd,$m){$this->error=php_error_text($m);return
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
PdoDb{var$extension="PDO_PgSQL";var$timeout=0;private$string,$username,$password,$database;function
attach(array$M,$U,$D){$k=adminer()->database();$Ej=$M["port"];$mf=($M["host"]?:$M["socket"]);$this->string="pgsql:host='$mf'".($Ej?" port=$Ej":"")." client_encoding=utf8";$Sl=adminer()->connectSsl();if(isset($Sl["mode"]))$this->string
.=" sslmode=$Sl[mode]";$this->username=$U;$this->password=$D;$this->database=($k!=""?$k:"postgres");return$this->dsn("$this->string dbname='".addcslashes($this->database,"'\\")."'",$U,$D);}function
select_db($sc){if($sc!=$this->database){$m=$this->dsn("$this->string dbname='".addcslashes($sc,"'\\")."'",$this->username,$this->password);if($m!=""){$this->error=$m;return
false;}$this->database=$sc;}return
true;}function
query($F,$rn=false){$H=(self::$untrusted?$this->readOnlyQuery($F):parent::query($F,$rn));if($this->timeout){$this->timeout=0;parent::query("RESET statement_timeout");}return$H;}private
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
operators($lm){return
array("=","<",">","<=",">=","!=","~","~*","!~","LIKE","LIKE %%","ILIKE","ILIKE %%","IN","BETWEEN","IS NULL","NOT LIKE","NOT ILIKE","NOT IN","IS NOT NULL","SQL");}static
function
connect($M,$U,$D){$g=parent::connect($M,$U,$D);if(is_string($g))return$g;$Xn=get_val("SELECT version()",0,$g);$g->flavor=(preg_match('~CockroachDB~',$Xn)?'cockroach':'');$g->server_info=preg_replace('~^\D*([\d.]+[-\w]*).*~','\1',$Xn);if(min_version(9,0,$g))$g->query("SET application_name = 'Adminer'");if($g->flavor=='cockroach')add_driver(DRIVER,"CockroachDB");return$g;}function
__construct(Db$g){parent::__construct($g);$this->types=array('Numbers'=>array("smallint"=>5,"integer"=>10,"bigint"=>19,"boolean"=>1,"numeric"=>0,"real"=>7,"double precision"=>16,"money"=>20),'Date and time'=>array("date"=>10,"time"=>8,"timestamp"=>19,"timestamptz"=>25,"interval"=>0),'Strings'=>array("character"=>0,"character varying"=>0,"text"=>0,"tsquery"=>0,"tsvector"=>0,"uuid"=>0,"xml"=>0),'Binary'=>array("bit"=>0,"bit varying"=>0,"bytea"=>0),'Network'=>array("cidr"=>43,"inet"=>43,"macaddr"=>17,"macaddr8"=>23,"txid_snapshot"=>0),'Geometry'=>array("box"=>0,"circle"=>0,"line"=>0,"lseg"=>0,"path"=>0,"point"=>0,"polygon"=>0),);if(min_version(9.2,0,$g)){$this->types['Strings']["json"]=4294967295;$this->types['Ranges']=array("int4range"=>0,"int8range"=>0,"numrange"=>0,"daterange"=>0,"tsrange"=>0,"tstzrange"=>0);if(min_version(9.4,0,$g))$this->types['Strings']["jsonb"]=4294967295;}$this->insertFunctions=array("char"=>"md5","date|time"=>"now",);$this->editFunctions=array(number_type()=>"+/-","date|time"=>"+ interval/- interval","char|text"=>"||",);if(min_version(12,0,$g)){$this->generated[]="STORED";if(min_version(18,0,$g))$this->generated[]="VIRTUAL";}$this->partitionBy=array("RANGE","LIST");if(!$g->flavor)$this->partitionBy[]="HASH";}function
enumLength(array$n){$mi=$this->userTypes[$n["type"]];return($mi&&!preg_match('~]$~',$n["length"])?type_values($mi):"");}function
setUserTypes(array$qn){$this->userTypes=array_flip($qn);$this->types['User types']=array_fill_keys(array_keys($this->userTypes),0);}function
insertReturning($Q){$La=array_filter(fields($Q),function($n){return$n['auto_increment'];});return(count($La)==1?" RETURNING ".idf_escape(key($La)):"");}function
insertUpdate($Q,array$J,array$Rj){$e=array_keys(reset($J));$Pb=array();$Bn=array();foreach($e
as$w){if(isset($Rj[idf_unescape($w)]))$Pb[]=$w;else$Bn[]="$w = EXCLUDED.$w";}if(!$Pb||!min_version(9.5)||count($Pb)!=count($Rj))return
parent::insertUpdate($Q,$J,$Rj);$Mj="INSERT INTO ".table($Q)." (".implode(", ",$e).") VALUES\n";$dm="\nON CONFLICT (".implode(", ",$Pb).")".($Bn?" DO UPDATE SET ".implode(", ",$Bn):" DO NOTHING");$Y=array();$x=0;foreach($J
as$N){$X="(".implode(", ",$N).")";if($Y&&strlen($Mj)+$x+strlen($X)+strlen($dm)>1e6){if(!queries($Mj.implode(",\n",$Y).$dm))return
false;$Y=array();$x=0;}$Y[]=$X;$x+=strlen($X)+2;}return
queries($Mj.implode(",\n",$Y).$dm);}function
slowQuery($F,$Mm){$this->conn->query("SET statement_timeout = ".(1000*$Mm));$this->conn->timeout=1000*$Mm;return$F;}function
convertSearch($t,array$W,array$n){$wj=preg_match('(LIKE|^!?~)',$W["op"]);$Lh=preg_match('~^(character( varying)?|text|citext|bpchar|name)$~',$n["type"])||(!$wj&&preg_match('~'.number_type().'|^(date|time|timetz|timestamp|timestamptz|boolean)$~',$n["type"]));return($Lh&&!preg_match('~\[]$~',$n["full_type"])?$t:"CAST($t AS text)");}function
quoteBinary($Rk){return"decode('".bin2hex($Rk)."', 'hex')";}function
md5($d,array$n){if(is_blob($n)||preg_match('~'.text_type().'~',$n["type"]))return"MD5($d)";}function
warnings(){return$this->conn->warnings();}function
errorLine($F){return(preg_match('~^LINE (\d+):~m',$this->conn->error,$_)?(int)$_[1]:0);}function
tableHelp($A,$dg=false){$Hg=array("information_schema"=>"infoschema","pg_catalog"=>($dg?"view":"catalog"),);$z=$Hg[$_GET["ns"]];if($z)return"$z-".str_replace("_","-",$A).".html";}function
inheritsFrom($Q){return
get_rows("SELECT relname AS table, nspname AS ns FROM pg_class JOIN pg_inherits ON inhparent = oid JOIN pg_namespace ON relnamespace = pg_namespace.oid WHERE inhrelid = ".$this->tableOid($Q)." ORDER BY 2, 1");}function
inheritedTables($Q){return
get_rows("SELECT relname AS table, nspname AS ns FROM pg_inherits JOIN pg_class ON inhrelid = oid JOIN pg_namespace ON relnamespace = pg_namespace.oid WHERE inhparent = ".$this->tableOid($Q)." ORDER BY 2, 1");}function
partitionsInfo($Q){$I=(min_version(10)?$this->conn->query("SELECT * FROM pg_partitioned_table WHERE partrelid = ".$this->tableOid($Q))->fetch_assoc():null);if($I){$c=get_vals("SELECT attname FROM pg_attribute WHERE attrelid = $I[partrelid] AND attnum IN (".str_replace(" ",", ",$I["partattrs"]).")");$db=array('h'=>'HASH','l'=>'LIST','r'=>'RANGE');return
array("partition_by"=>$db[$I["partstrat"]],"partition"=>implode(", ",array_map('Adminer\idf_escape',$c)),);}return
array();}function
routineTriggers($A){return
get_rows("SELECT nspname AS ns, relname AS table, tgname AS trigger
FROM pg_trigger
JOIN pg_class ON tgrelid = pg_class.oid
JOIN pg_namespace ON relnamespace = pg_namespace.oid
WHERE NOT tgisinternal AND tgfoid = substring(".q($A).", '[0-9]+\$')::oid
ORDER BY 1, 2, 3");}function
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
indexAlgorithms(array$lm){static$H=array();if(!$H)$H=get_vals("SELECT amname FROM pg_am".(min_version(9.6)?" WHERE amtype = 'i'":"")." ORDER BY amname = '".($this->conn->flavor=='cockroach'?"prefix":"btree")."' DESC, amname");return$H;}function
indexOpclasses(){static$H=array();if(!$H&&$this->conn->flavor!='cockroach')$H=get_vals("SELECT DISTINCT opcname FROM pg_catalog.pg_opclass WHERE NOT opcdefault ORDER BY opcname");return$H;}function
supportsIndex(array$R){return$R["Engine"]!="view";}function
hasCStyleEscapes(){static$fb;if($fb===null)$fb=(get_val("SHOW standard_conforming_strings",0,$this->conn)=="off");return$fb;}function
hasEstimatedRows(){return
true;}function
isSystem($k,$K=""){return($K!=""?information_schema($k,$K)||preg_match('~^pg_~',$K):in_array($k,array("postgres","template1"))||($this->conn->flavor=='cockroach'&&$k=="system"));}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return($_POST["schema_style"]===""&&$_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($t);}function
get_databases($ne){return
get_vals("SELECT datname FROM pg_database
WHERE datallowconn = TRUE AND has_database_privilege(datname, 'CONNECT')
ORDER BY datname");}function
limit($F,$Z,$y,$B=0,$il=" "){return" $F$Z".($y?$il."LIMIT $y".($B?" OFFSET $B":""):"");}function
limit1($Q,$F,$Z,$il="\n"){return(preg_match('~^INTO~',$F)?limit($F,$Z,1,0,$il):" $F".(is_view(table_status1($Q))?$Z:$il."WHERE (tableoid, ctid) = (SELECT tableoid, ctid FROM ".table($Q).$Z.$il."LIMIT 1)"));}function
db_collation($k,array$Eb){return
get_val("SELECT datcollate FROM pg_database WHERE datname = ".q($k));}function
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
count_tables(array$j){$H=array();foreach($j
as$k){if(connection()->select_db($k))$H[$k]=count(tables_list());}return$H;}function
table_status($A="",$Ud=false){static$Ye;if($Ye===null)$Ye=get_val("SELECT 'pg_table_size'::regproc");$ml=(!$Ud&&min_version(10));$H=array();foreach(get_rows("SELECT
	relname AS \"Name\",
	CASE relkind WHEN 'v' THEN 'view' WHEN 'm' THEN 'materialized view' ELSE 'table' END AS \"Engine\"".($Ye?",
	pg_table_size(c.oid) AS \"Data_length\",
	pg_indexes_size(c.oid) AS \"Index_length\"":"").",
	obj_description(c.oid, 'pg_class') AS \"Comment\",
	".(min_version(12)?"''":"CASE WHEN relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\",
	reltuples AS \"Rows\",
	EXISTS (SELECT 1 FROM pg_index WHERE indrelid = c.oid AND NOT indisvalid)::int AS invalid_index,
	".($ml?"seq.last_value":"NULL")." AS \"Auto_increment\"".(min_version(10)?",
	relispartition::int AS dependent":"")."
FROM pg_class c
".($ml?"LEFT JOIN (
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
parse_full_type(array&$I){static$va=array('timestamp without time zone'=>'timestamp','timestamp with time zone'=>'timestamptz','time without time zone'=>'time','time with time zone'=>'timetz',);preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',$I["full_type"],$_);list(,$T,$x,$I["length"],$ma,$Ea)=$_;$I["length"].=$Ea;$rb=$T.$ma;if(isset($va[$rb])){$I["type"]=$va[$rb];$I["full_type"]=$I["type"].$x.$Ea;}else{$sn=idf_unescape($T);$I["type"]=(is_user_type($sn)?$sn:$T);$I["full_type"]=$I["type"].$x.$ma.$Ea;}}function
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
ORDER BY a.attnum")as$I){parse_full_type($I);$I["default_sql"]=($I["default"]!==null&&!$I["attidentity"]&&!$I["attgenerated"]&&!preg_match("~^(('(?:[^']|'')*'|NULL)(::[^,)]+)?|[-+]?[\\d.]+|true|false)$~s",$I["default"]));if(in_array($I['attidentity'],array('a','d')))$I['default']='GENERATED '.($I['attidentity']=='d'?'BY DEFAULT':'ALWAYS').' AS IDENTITY';$I["generated"]=idx(array("s"=>"STORED","v"=>"VIRTUAL"),$I["attgenerated"],"");$I["composite"]=($I["typcategory"]=="C");$I["null"]=!$I["attnotnull"];$I["auto_increment"]=$I['attidentity']||preg_match('~^nextval\(~i',$I["default"])||preg_match('~^unique_rowid\(~',$I["default"]);$I["privileges"]=array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1);if(!$I['generated']&&preg_match('~(.+)::[^,)]+(.*)~',$I["default"],$_)&&($_[2]!=""||preg_match("~^('.*'|NULL)\$~s",$_[1])))$I["default"]=($_[1]=="NULL"?null:idf_unescape($_[1]).$_[2]);$H[$I["field"]]=$I;}return$H;}function
indexes($Q,$h=null){$h=connection($h);$H=array();$sm=driver()->tableOid($Q);$e=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $sm AND attnum > 0",$h);foreach(get_rows("SELECT relname, indisunique::int, indisprimary::int, indisvalid::int, indkey, indoption, amname,
	pg_get_expr(indpred, indrelid, true) AS partial, pg_get_expr(indexprs, indrelid) AS indexpr".($h->flavor=='cockroach'?"":",
	(SELECT string_agg(CASE WHEN opcdefault THEN '' ELSE opcname END, ' ' ORDER BY s)
		FROM generate_subscripts(indclass, 1) AS s JOIN pg_catalog.pg_opclass ON pg_opclass.oid = indclass[s]) AS opclasses")."
FROM pg_index
JOIN pg_class ON indexrelid = oid
JOIN pg_am ON pg_am.oid = pg_class.relam
WHERE indrelid = $sm
ORDER BY indisprimary DESC, indisunique DESC",$h)as$I){$wk=$I["relname"];$H[$wk]["type"]=($I["indisprimary"]?"PRIMARY":($I["indisunique"]?"UNIQUE":"INDEX"));$H[$wk]["columns"]=array();$H[$wk]["descs"]=array();$H[$wk]["algorithm"]=$I["amname"];$H[$wk]["partial"]=$I["partial"];$H[$wk]["invalid"]=!$I["indisvalid"];$Ff=preg_split('~(?<=\)), (?=\()~',$I["indexpr"]);foreach(explode(" ",$I["indkey"])as$Gf)$H[$wk]["columns"][]=($Gf?$e[$Gf]:array_shift($Ff));foreach(explode(" ",$I["indoption"])as$Hf)$H[$wk]["descs"][]=(intval($Hf)&1?'1':null);$H[$wk]["opclasses"]=($I["opclasses"]!=""?explode(" ",$I["opclasses"]):array());$H[$wk]["lengths"]=array();}return$H;}function
foreign_keys($Q){$H=array();foreach(get_rows("SELECT conname, condeferrable::int AS deferrable, condeferred::int AS deferred, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = ".driver()->tableOid($Q)."
AND contype = 'f'::char
ORDER BY conkey, conname")as$I){$I['deferrable']=($I['deferrable']?'':'NOT ').'DEFERRABLE'.($I['deferred']?' INITIALLY DEFERRED':'');if(preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',$I['definition'],$_)){$I['source']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$_[1])));if(preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',$_[2],$Qg)){$I['ns']=idf_unescape($Qg[2]);$I['table']=idf_unescape($Qg[4]);}$I['target']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$_[3])));$I['on_delete']=(preg_match("~ON DELETE (".driver()->onActions.")~",$_[4],$Qg)?$Qg[1]:'NO ACTION');$I['on_update']=(preg_match("~ON UPDATE (".driver()->onActions.")~",$_[4],$Qg)?$Qg[1]:'NO ACTION');$H[$I['conname']]=$I;}}return$H;}function
view($A){return
array("select"=>trim(get_val("SELECT pg_get_viewdef(".driver()->tableOid($A).")")));}function
collations(){return(isset($_GET["database"])&&DB==""&&connection()->flavor!='cockroach'&&min_version(13)?get_vals("SELECT DISTINCT collcollate FROM pg_collation WHERE collprovider = 'c' ORDER BY 1"):array());}function
information_schema($k,$K=""){$jm=array("information_schema","pg_catalog","pg_toast");if(connection()->flavor=='cockroach'){$jm[]="crdb_internal";$jm[]="pg_extension";}return
in_array($K!=""?$K:get_schema(),$jm);}function
error(){$H=h(connection()->error);if(preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s',$H,$_))$H=$_[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($_[3]).'})(.*)~','\1<b>\2</b>',$_[2]).$_[4];return
nl_br($H);}function
create_database($k,$Db){return
queries("CREATE DATABASE ".idf_escape($k).($Db?" TEMPLATE template0 LOCALE ".q($Db):""));}function
drop_databases(array$j){connection()->close();return
apply_queries("DROP DATABASE",$j,'Adminer\idf_escape');}function
rename_database($A,$Db){connection()->close();return!!queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($A));}function
auto_increment(){return"";}function
alter_table($Q,$A,array$o,array$pe,$f,$td,$Db,$La,$nj){$b=array();$ek=array();if($Q!=""&&$Q!=$A)$ek[]="ALTER TABLE ".table($Q)." RENAME TO ".table($A);$jl="";$Pi=($Q!=""?fields($Q):array());foreach($o
as$n){$d=idf_escape($n[0]);$W=$n[1];if(!$W)$b[]="DROP $d";else{$Oi=$Pi[$n[0]];$Mi=($Oi?process_field($Oi,$Oi):array());$Sn=$W[5];unset($W[5]);if($n[0]==""){if(isset($W[6]))$W[1]=($W[1]==" bigint"?" big":($W[1]==" smallint"?" small":" "))."serial";$b[]=($Q!=""?"ADD ":"  ").implode($W);if(isset($W[6]))$b[]=($Q!=""?"ADD":" ")." PRIMARY KEY ($W[0])";}else{if($d!=$W[0])$ek[]="ALTER TABLE ".table($A)." RENAME $d TO $W[0]";if($W[1]!=$Mi[1])$b[]="ALTER $d TYPE$W[1]";if($W[3]!=$Mi[3]||isset($W[6])!=isset($Mi[6])){$kl=$Q."_".idf_unescape($W[0])."_seq";$b[]="ALTER $d ".($W[3]?"SET".preg_replace('~GENERATED ALWAYS(.*) (STORED|VIRTUAL)~','EXPRESSION\1',$W[3]):(isset($W[6])?"SET DEFAULT nextval(".q($kl).")":"DROP DEFAULT"));if(isset($W[6]))$jl="CREATE SEQUENCE IF NOT EXISTS ".idf_escape($kl)." OWNED BY ".idf_escape($Q).".$W[0]";}if($W[2]!=$Mi[2])$b[]="ALTER $d ".($W[2]==" NULL"?"DROP NOT":"SET").$W[2];}if($Sn!=$Mi[5])$ek[]="COMMENT ON COLUMN ".table($A).".$W[0] IS ".($Sn!=""?substr($Sn,9):"''");}}if($Q==""){$b=array_merge($b,$pe);$O="";if($nj){$_b=(connection()->flavor=='cockroach');$O=" PARTITION BY $nj[partition_by]($nj[partition])";if($nj["partition_by"]=='HASH'){$oj=+$nj["partitions"];for($r=0;$r<$oj;$r++)$ek[]="CREATE TABLE ".idf_escape($A."_$r")." PARTITION OF ".idf_escape($A)." FOR VALUES WITH (MODULUS $oj, REMAINDER $r)";}else{$Oj="MINVALUE";foreach($nj["partition_names"]as$r=>$W){$X=$nj["partition_values"][$r];$ij=" VALUES ".($nj["partition_by"]=='LIST'?"IN ($X)":"FROM ($Oj) TO ($X)");if($_b)$O
.=($r?",":" (")."\n  PARTITION ".(preg_match('~^DEFAULT$~i',$W)?$W:idf_escape($W))."$ij";else$ek[]="CREATE TABLE ".idf_escape($A."_$W")." PARTITION OF ".idf_escape($A)." FOR$ij";$Oj=$X;}$O
.=($_b?"\n)":"");}}array_unshift($ek,"CREATE TABLE ".table($A)." (\n".implode(",\n",$b)."\n)$O");}else{if($b)array_unshift($ek,"ALTER TABLE ".table($Q)."\n".implode(",\n",$b));if($pe)$ek[]="ALTER TABLE ".table($A)."\n".implode(",\n",$pe);}if($jl)array_unshift($ek,$jl);if($f!==null)$ek[]="COMMENT ON TABLE ".table($A)." IS ".q($f);foreach($ek
as$F){if(!queries($F))return
false;}if($La!=""){foreach(fields($A)as$Xd=>$n){if($n["auto_increment"])return!!queries("SELECT setval(pg_get_serial_sequence(".q(table($A)).", ".q($Xd)."), $La)");}}return
true;}function
alter_indexes($Q,$b){$i=array();$ad=array();$ek=array();$Nb=(support("concurrent_indexes")&&$_POST["concurrently"]?" CONCURRENTLY":"");foreach($b
as$W){if($W[0]=="UNIQUE"&&$W[2]=="DROP"&&!get_val("SELECT 1 FROM pg_constraint JOIN pg_class ON conindid = pg_class.oid WHERE conrelid = ".driver()->tableOid($Q)." AND relname = ".q($W[1])))$W[0]="INDEX";if($W[0]!="INDEX")$i[]=($W[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($W[1]):"\nADD".($W[1]!=""?" CONSTRAINT ".idf_escape($W[1]):"")." $W[0] ".($W[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$W[2]).")");elseif($W[2]=="DROP")$ad[]=idf_escape($W[1]);else$ek[]="CREATE INDEX$Nb ".idf_escape($W[1]!=""?$W[1]:uniqid($Q."_"))." ON ".table($Q).($W[3]?" USING $W[3]":"")." (".implode(", ",$W[2]).")".($W[4]?" WHERE $W[4]":"");}if($i)array_unshift($ek,"ALTER TABLE ".table($Q).implode(",",$i));if($ad){$ek=array_merge(($Nb?array_map(function($t){return"DROP INDEX CONCURRENTLY $t";},$ad):array("DROP INDEX ".implode(", ",$ad))),$ek);}foreach($ek
as$F){if(!queries($F))return
false;}return
true;}function
truncate_tables(array$S){return!!queries("TRUNCATE ".implode(", ",array_map('Adminer\table',$S)));}function
drop_kinds(array$S){$H=array("MATERIALIZED VIEW"=>array(),"VIEW"=>array(),"TABLE"=>array());foreach($S
as$A=>$R)$H[strtoupper($R["Engine"])][]=table($A);return
array_filter($H);}function
drop_views(array$Zn){return
drop_tables($Zn);}function
drop_tables(array$S){$Xl=array();foreach($S
as$Q)$Xl[$Q]=table_status1($Q);foreach(drop_kinds($Xl)as$og=>$Kh){if(!queries("DROP $og ".implode(", ",$Kh)))return
false;}return
true;}function
move_tables(array$S,array$Zn,$Cm){foreach(array_merge($S,$Zn)as$Q){$O=table_status1($Q);if(!queries("ALTER ".strtoupper($O["Engine"])." ".table($Q)." SET SCHEMA ".idf_escape($Cm)))return
false;}return
true;}function
trigger($A,$Q){if($A=="")return
array("Statement"=>"EXECUTE PROCEDURE ()");$e=array();$Z="WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q)." AND trigger_name = ".q($A);foreach(get_rows("SELECT * FROM information_schema.triggered_update_columns $Z")as$I)$e[]=$I["event_object_column"];$H=array();foreach(get_rows('SELECT trigger_name AS "Trigger", action_timing AS "Timing", event_manipulation AS "Event", \'FOR EACH \' || action_orientation AS "Type", action_statement AS "Statement"
FROM information_schema.triggers'."
$Z
ORDER BY event_manipulation DESC")as$I){if($e&&$I["Event"]=="UPDATE")$I["Event"].=" OF";$I["Of"]=implode(", ",$e);if($H)$I["Event"].=" OR $H[Event]";$H=$I;}return$H;}function
triggers($Q){$H=array();$Ee=array();foreach(get_rows('SELECT t.tgname, r.routine_schema AS ns, r.specific_name AS function, r.routine_name AS name
FROM pg_catalog.pg_trigger t
JOIN information_schema.routines r ON substring(r.specific_name, \'[0-9]+$\')::oid = t.tgfoid
WHERE NOT t.tgisinternal AND t.tgrelid = (
	SELECT c.oid FROM pg_catalog.pg_class c JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE n.nspname = current_schema() AND c.relname = '.q($Q).'
)')as$I)$Ee[array_shift($I)]=$I;foreach(get_rows("SELECT * FROM information_schema.triggers WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q))as$I){$fn=trigger($I["trigger_name"],$Q);$H[$fn["Trigger"]]=array($fn["Timing"],$fn["Event"]);if($Ee[$fn["Trigger"]])$H[$fn["Trigger"]][]=$Ee[$fn["Trigger"]];}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF",),"Type"=>array("FOR EACH ROW","FOR EACH STATEMENT"),);}function
routine($A,$T){$Ei=routine_options($T);$fl=array_intersect_key(array("VOLATILITY"=>"CASE p.provolatile WHEN 'i' THEN 'IMMUTABLE' WHEN 's' THEN 'STABLE' ELSE 'VOLATILE' END","NULL_INPUT"=>"CASE WHEN p.proisstrict THEN 'RETURNS NULL ON NULL INPUT' ELSE 'CALLED ON NULL INPUT' END","SECURITY"=>"CASE WHEN p.prosecdef THEN 'SECURITY DEFINER' ELSE 'SECURITY INVOKER' END","PARALLEL"=>"CASE p.proparallel WHEN 's' THEN 'PARALLEL SAFE' WHEN 'r' THEN 'PARALLEL RESTRICTED' ELSE 'PARALLEL UNSAFE' END",),$Ei);foreach($fl
as$w=>$L)$fl[$w]="$L AS \"$w\"";$J=get_rows('SELECT r.routine_definition AS definition, LOWER(r.external_language) AS language, '.($fl?implode(', ',$fl).', ':'').'r.*
FROM information_schema.routines r
LEFT JOIN pg_catalog.pg_proc p ON p.oid::text = substring(r.specific_name, \'[0-9]+$\')
WHERE r.routine_schema = current_schema() AND r.specific_name = '.q($A));if(!$J)return
array();$H=$J[0];$H["definition"]=trim("$H[definition]","\r\n");$H["options"]=array_intersect_key($H,$Ei);$H["returns"]=array("type"=>preg_replace('~^_(.*)~','\1[]',"$H[type_udt_name]"));$H["fields"]=get_rows("SELECT COALESCE(parameter_name, ordinal_position::text) AS field,
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
routine_languages(){$H=array();foreach(get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language")as$tg)$H[$tg]=(preg_match('~sql$~',$tg)?"pgsql":"txt");return$H;}function
routine_options($Ik){$_b=(connection()->flavor=='cockroach');$al=($_b?array():array("SECURITY"=>array("SECURITY INVOKER","SECURITY DEFINER")));if($Ik=="PROCEDURE")return$al;return
array("VOLATILITY"=>array("VOLATILE","STABLE","IMMUTABLE"),"NULL_INPUT"=>array("CALLED ON NULL INPUT","RETURNS NULL ON NULL INPUT"),)+$al+($_b?array():array("PARALLEL"=>array("PARALLEL UNSAFE","PARALLEL RESTRICTED","PARALLEL SAFE"),));}function
routine_id($A,array$I){$H=array();foreach($I["fields"]as$n){$x=$n["length"];$H[]=$n["type"].($x?"($x)":"");}return
idf_escape($A)."(".implode(", ",$H).")";}function
last_id($G){$I=(is_object($G)?$G->fetch_row():array());return($I?$I[0]:0);}function
explain(Db$g,$F){return$g->query("EXPLAIN $F");}function
found_rows(array$R,array$Z){if(preg_match("~ rows=([0-9]+)~",get_val("EXPLAIN SELECT * FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ",$Z):"")),$vk))return$vk[1];}function
types($Qd=false){$_b=connection()->flavor=='cockroach';$pg=($_b?"'e'":"'b','c','d','e'".(min_version(9.2)?",'r'":""));return
get_key_vals("SELECT t.oid, t.typname
FROM pg_type t
WHERE t.typnamespace = ".driver()->nsOid."
AND t.typtype IN ($pg)".($_b?"
AND t.typelem = 0":"
AND (t.typrelid = 0 OR (SELECT c.relkind FROM pg_class c WHERE c.oid = t.typrelid) = 'c')"."
AND NOT EXISTS (SELECT 1 FROM pg_type e WHERE e.typarray = t.oid)".($Qd?'':"
AND t.oid NOT IN (SELECT objid FROM pg_catalog.pg_depend WHERE classid = 'pg_type'::regclass AND deptype = 'e')"))."
ORDER BY t.typname");}function
type_values($s){$xd=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = $s ORDER BY enumsortorder");return($xd?"'".implode("', '",array_map('addslashes',$xd))."'":"");}function
collation_name($mi){return(min_version(9.1)?"(SELECT collname FROM pg_collation WHERE oid = $mi AND collname != 'default')":"NULL");}function
type_definition($s){$T=first(get_rows("SELECT typtype, typisdefined::int AS defined, typrelid FROM pg_type WHERE oid = $s"));$H=array("kind"=>($T?$T["typtype"]:""),"definition"=>"");if(!$T||!$T["defined"])return$H;switch($H["kind"]){case'e':$Y=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = $s ORDER BY enumsortorder");$H["definition"]="AS ENUM (".implode(", ",array_map('Adminer\q',$Y)).")";break;case'c':$e=array();foreach(get_rows("SELECT attname, format_type(atttypid, atttypmod) AS full_type, ".collation_name("attcollation")." AS collation
FROM pg_attribute
WHERE attrelid = $T[typrelid] AND attnum > 0 AND NOT attisdropped
ORDER BY attnum")as$I)$e[]=idf_escape($I["attname"])." $I[full_type]".($I["collation"]?" COLLATE ".idf_escape($I["collation"]):"");$H["definition"]="AS (\n\t".implode(",\n\t",$e)."\n)";break;case'd':$Xc=first(get_rows("SELECT format_type(typbasetype, typtypmod) AS base, typnotnull::int AS notnull, typdefault, ".collation_name("typcollation")." AS collation
FROM pg_type WHERE oid = $s"));$H["definition"]="AS $Xc[base]".($Xc["collation"]?" COLLATE ".idf_escape($Xc["collation"]):"").($Xc["typdefault"]!=""?" DEFAULT $Xc[typdefault]":"").($Xc["notnull"]?" NOT NULL":"");foreach(get_rows("SELECT conname, pg_get_constraintdef(oid) AS definition FROM pg_constraint WHERE contypid = $s AND contype != 'n' ORDER BY conname")as$I)$H["definition"].=" CONSTRAINT ".idf_escape($I["conname"])." $I[definition]";break;case'r':$kk=first(get_rows("SELECT format_type(rngsubtype, NULL) AS subtype,
(SELECT opcname FROM pg_opclass WHERE oid = rngsubopc) AS subtype_opclass,
".collation_name("rngcollation")." AS collation,
NULLIF(rngcanonical, 0)::regproc::text AS canonical,
NULLIF(rngsubdiff, 0)::regproc::text AS subtype_diff".(min_version(14)?",
(SELECT typname FROM pg_type WHERE oid = rngmultitypid) AS multirange_type_name":"")."
FROM pg_range WHERE rngtypid = $s"));$Ei=array();foreach(array("subtype"=>0,"subtype_opclass"=>1,"collation"=>1,"canonical"=>0,"subtype_diff"=>0,"multirange_type_name"=>1)as$w=>$Cd){if($kk[$w]!="")$Ei[]=strtoupper($w)." = ".($Cd?idf_escape($kk[$w]):$kk[$w]);}$H["definition"]="AS RANGE (".implode(", ",$Ei).")";}return$H;}function
schemas(){return
get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");}function
get_schema(){return(string)get_val("SELECT current_schema()");}function
set_schema($K,$h=null){$_GET["ns"]=$K;$H=get_val("SELECT set_config('search_path', ".q(idf_escape($K)).", false) FROM pg_namespace WHERE nspname = ".q($K),0,$h);driver()->setUserTypes(types(true));return!!$H;}function
schema_comment($K){return(string)get_val("SELECT obj_description(oid, 'pg_namespace') FROM pg_namespace WHERE nspname = ".q($K));}function
set_schema_comment($K,$f){return!!queries("COMMENT ON SCHEMA ".idf_escape($K)." IS ".q($f));}function
drop_sql(array$S){$H="";foreach(drop_kinds($S)as$og=>$Kh)$H
.="DROP $og IF EXISTS ".implode(", ",$Kh).";\n";return($H?"$H\n":"");}function
foreign_keys_sql($Q){$H="";$ke=foreign_keys($Q);ksort($ke);foreach($ke
as$je=>$ie){$H
.="ALTER TABLE ONLY ".table($Q)." ADD CONSTRAINT ".idf_escape($je)." ".preg_replace_callback('~( REFERENCES )([^(.]+)\(~',function(array$_){return$_[1].table(idf_unescape($_[2]))."(";},$ie["definition"]).";\n";}return($H?"$H\n":$H);}function
indexes_sql($Q,$Rj=""){$H="";$F="SELECT indexdef, quote_ident(schemaname) || '.' || quote_ident(tablename) AS qualified, quote_ident(current_database()) AS db
FROM pg_catalog.pg_indexes
WHERE schemaname = current_schema() AND tablename = ".q($Q).($Rj!=""?" AND indexname != ".q($Rj):"");foreach(get_rows($F,null,"-- ")as$I)$H
.="\n\n".str_replace(array(" $I[db].$I[qualified] USING "," $I[qualified] USING ")," ".table($Q)." USING ",$I["indexdef"]).";";return$H;}function
create_sql($Q,$La,$bm){$Dk=array();$ml=array();$nl=array();$ll=array();$O=table_status1($Q);if(is_view($O)){$Yn=view($Q);$i="CREATE ".strtoupper($O["Engine"])." ".table($Q)." AS ".rtrim($Yn["select"],";").";";return
rtrim($i.indexes_sql($Q),';');}$o=fields($Q);if(count($O)<2||empty($o))return"";$H="CREATE TABLE ".table($O['Name'])." (\n    ";$qm=q(table($O['Name']));foreach($o
as$n){$ol="";if($n['default']=="nextval('$O[Name]_$n[field]_seq')"){$ol=table("$O[Name]_$n[field]_seq");$n['default']=null;$n['full_type']=preg_replace('~int(eger)?~','serial',$n['full_type']);}$gj=idf_escape($n['field']).' '.full_type_sql($n).preg_replace_callback('~(nextval\(\')([^.\']+)\'~',function(array$_){return$_[1].str_replace("'","''",table(idf_unescape($_[2])))."'";},default_value($n)).($n['null']?"":" NOT NULL");$Dk[]=$gj;if(preg_match('~nextval\(\'([^\']+)\'\)~',$n['default'],$Rg)){$kl=$Rg[1];$Ll=first(get_rows((min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q(idf_unescape($kl)):"SELECT * FROM $kl"),null,"-- "));$jl=table(idf_unescape($kl));$ml[]=($bm=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $jl;\n":"")."CREATE SEQUENCE $jl INCREMENT $Ll[increment_by] MINVALUE $Ll[min_value] MAXVALUE $Ll[max_value]"." CACHE $Ll[cache_value];";if(get_val("SELECT pg_get_serial_sequence($qm, ".q($n['field']).")"))$nl[]="\n\nALTER SEQUENCE $jl OWNED BY ".table($O['Name']).".".idf_escape($n['field']).";";if($La)$ll[]=$jl;}elseif($La&&$n['auto_increment']){$jl=($ol?"":get_val("SELECT pg_get_serial_sequence($qm, ".q($n['field']).")::regclass"));$ll[]=($jl?table(idf_unescape($jl)):$ol);}}if(!empty($ml))$H=implode("\n\n",$ml)."\n\n$H";$Rj="";foreach(indexes($Q)as$Df=>$u){if($u['type']=='PRIMARY'){$Rj=$Df;$Dk[]="CONSTRAINT ".idf_escape($Df)." PRIMARY KEY (".implode(', ',array_map('Adminer\idf_escape',$u['columns'])).")";}}foreach(driver()->checkConstraints($Q)as$Rb=>$Tb)$Dk[]="CONSTRAINT ".idf_escape($Rb)." CHECK ($Tb)";$H
.=implode(",\n    ",$Dk)."\n)";$ij=driver()->partitionsInfo($O['Name']);if($ij)$H
.="\nPARTITION BY $ij[partition_by]($ij[partition])";$H
.=(min_version(12)?"":"\nWITH (oids = ".($O['Oid']?'true':'false').")").";";$H
.=implode($nl);if($O['Comment'])$H
.="\n\nCOMMENT ON TABLE ".table($O['Name'])." IS ".q($O['Comment']).";";foreach($o
as$Xd=>$n){if($n['comment'])$H
.="\n\nCOMMENT ON COLUMN ".table($O['Name']).".".idf_escape($Xd)." IS ".q($n['comment']).";";}$H
.=indexes_sql($Q,$Rj);foreach(array_filter($ll)as$jl){$Ll=first(get_rows("SELECT last_value, is_called::int FROM $jl",null,"-- "));if($Ll['is_called'])$H
.="\n\nDO \$\$ BEGIN PERFORM setval(".q($jl).", $Ll[last_value]); END \$\$;";}return
rtrim($H,';');}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
truncate_all_sql(array$S){return($S?"TRUNCATE ".implode(", ",array_map('Adminer\table',$S)).";\n\n":"");}function
trigger_sql($Q){$O=table_status1($Q);$H="";foreach(triggers($Q)as$en=>$dn){$fn=trigger($en,$O['Name']);$H
.="\nCREATE TRIGGER ".idf_escape($fn['Trigger'])." $fn[Timing] $fn[Event] ON ".table($O['Name'])." $fn[Type] $fn[Statement];;\n";}return$H;}function
use_sql($sc,$bm=""){$A=idf_escape($sc);$H="";if(preg_match('~CREATE~',$bm)){if($bm=="DROP+CREATE")$H="DROP DATABASE IF EXISTS $A;\n";$H
.="CREATE DATABASE $A;\n";}return"$H\\connect $A";}function
use_schema_sql($K,$bm){$A=idf_escape($K);$H="";if(preg_match('~CREATE~',$bm)){if($bm=="DROP+CREATE")$H="DROP SCHEMA IF EXISTS $A CASCADE;\n";$H
.="CREATE SCHEMA IF NOT EXISTS $A;\n";$f=schema_comment($K);if($f!="")$H
.="COMMENT ON SCHEMA $A IS ".q($f).";\n";}return$H."SET search_path TO $A";}function
show_variables(){return
get_rows("SHOW ALL");}function
process_list(){return
get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));}function
convert_field(array$n){if(preg_match('~^(geometry|geography)$~',$n["type"])&&strpos($n["full_type"],"[")===false)return"ST_AsEWKT(".idf_escape($n["field"]).")";}function
unconvert_field(array$n,$H){return($n["composite"]?"$H::".full_type_sql($n):$H);}function
support($Vd){return
preg_match('~^(check|columns|comment|database|drop_col|dump|descidx|fast_status|indexes|kill|partial_indexes|routine|scheme|sequence|sql|table|trigger|type|variables|view'.(min_version(9.3)?'|materializedview':'').(min_version(11)?'|procedure':'').(connection()->flavor=='cockroach'?'':'|deferrable').(connection()->flavor=='cockroach'?'':'|transaction_ddl').(connection()->flavor=='cockroach'||!min_version(9.1)?'':'|extension').(connection()->flavor=='cockroach'||!min_version(9.2)?'':'|concurrent_indexes').(connection()->flavor=='cockroach'?'':'|processlist').')$~',$Vd);}function
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
\SQLite3($M["path"]);if(method_exists($this->link,'setAuthorizer'))$this->link->setAuthorizer(array($this,'authorize'));$Xn=\SQLite3::version();$this->server_info=$Xn["versionString"];return'';}function
query($F,$rn=false){$G=@$this->link->query($F);$this->error="";if(!$G){$this->errno=$this->link->lastErrorCode();$this->error=$this->link->lastErrorMsg();return
false;}elseif($G->numColumns())return
new
Result($G);$this->affected_rows=$this->link->changes();return
true;}function
quote($P){return(is_utf8($P)?"'".$this->link->escapeString($P)."'":"x'".bin2hex($P)."'");}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($G){$this->result=$G;}function
fetch_assoc(){return$this->result->fetchArray(SQLITE3_ASSOC);}function
fetch_row(){return$this->result->fetchArray(SQLITE3_NUM);}function
fetch_field(){$qn=array(1=>"integer","real","text","blob","null");$d=$this->offset++;return(object)array("name"=>$this->result->columnName($d),"native_type"=>$qn[$this->result->columnType($d)],);}}}elseif(extension_loaded("pdo_sqlite")){abstract
class
SqliteDb
extends
PdoDb{var$extension="PDO_SQLite";function
attach(array$M,$U,$D){$H=$this->dsn(DRIVER.":".$M["path"],"","",array(),(class_exists('Pdo\Sqlite')?'Pdo\Sqlite':'PDO'));if(!$H&&method_exists($this->pdo,'setAuthorizer'))$this->pdo->setAuthorizer(array($this,'authorize'));return$H;}function
quote($P){return(is_utf8($P)?parent::quote($P):"x'".bin2hex($P)."'");}function
store_result($G=null){$sa=$this->affected_rows;$H=parent::store_result($G);if(!$G&&$H===true)$this->affected_rows=$sa;return$H;}}}if(class_exists('Adminer\SqliteDb')){class
Db
extends
SqliteDb{private$attaching=false;private$total_changes=0;function
attach(array$M,$U,$D){parent::attach($M,$U,$D);$this->total_changes=0;$this->query("PRAGMA foreign_keys = 1");$this->query("PRAGMA busy_timeout = 500");return'';}function
query($F,$rn=false){$H=parent::query($F,$rn);if($this->affected_rows){$G=parent::query("SELECT total_changes()");$I=($G?$G->fetch_row():false);if($I){if($I[0]==$this->total_changes)$this->affected_rows=0;$this->total_changes=$I[0];}}return$H;}function
select_db($p){$F="ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~",$p)?$p:dirname($_SERVER["SCRIPT_FILENAME"])."/$p")." AS a";$this->attaching=true;$Ia=is_readable($p)&&$this->query($F);$this->attaching=false;if($Ia)return!self::attach(server_parts(array("path"=>$p)),'','');return
false;}function
authorize($ia,$Ca){return($ia!=24||$Ca===''||$this->attaching?0:1);}}}class
Driver
extends
SqlDriver{static$extensions=array("SQLite3","PDO_SQLite");static$jush="sqlite";static$passwords=false;static$serverFile=true;protected$types=array(array("integer"=>0,"real"=>0,"numeric"=>0,"text"=>0,"blob"=>0));var$insertFunctions=array("date"=>"current_date","time"=>"current_timestamp",);var$editFunctions=array("^(?!.*(char|clob|text|blob))."=>"+/-","char|clob|text"=>"||",);var$fulltextOperator="MATCH";var$functions=array("hex","length","lower","round","unixepoch","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");function
operators($lm){$H=array("=","<",">","<=",">=","!=","&","LIKE","LIKE %%","IN","BETWEEN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");if(preg_match('~^fts\d+$~i',(string)idx($lm,"Engine")))$H[]="MATCH";$H[]="SQL";return$H;}static
function
connect($M,$U,$D){return
parent::connect(":memory:","","");}function
__construct(Db$g){parent::__construct($g);if(min_version(3.31,0,$g))$this->generated=array("STORED","VIRTUAL");if(min_version(3.37,0,$g))$this->types[0]["any"]=0;}function
structuredTypes(){return
array_keys($this->types[0]);}function
quoteBinary($Rk){return"x".q(bin2hex($Rk));}function
typeName(\stdClass$n){$H=strtolower(idx((array)$n,'sqlite:decl_type',parent::typeName($n)));return
idx(array("string"=>"text","double"=>"real"),$H,$H);}function
engines(){$H=array("table");if(min_version("3.8.2")){if(min_version(3.37)){$H[]="STRICT";$H[]="STRICT, WITHOUT ROWID";}$H[]="WITHOUT ROWID";}return$H;}private
function
isVirtual(array$R){$td=$R["Engine"];return$td!=""&&!in_array($td,array_merge(array("view"),$this->engines()));}function
supportsIndex(array$R){return!is_view($R)&&!$this->isVirtual($R);}function
supportsAlterIndex(array$R){return$this->supportsIndex($R);}function
supportsAlterTable(array$lm){return!$this->isVirtual($lm);}function
shadowTables($Q){$H=array();if(min_version(3.37)){foreach(get_vals("SELECT name FROM pragma_table_list WHERE schema = 'main' AND type = 'shadow' ORDER BY name")as$A){if(preg_match('(^'.preg_quote($Q).'_[^_]*$)',$A))$H[]=array("table"=>$A,"ns"=>"");}}return$H;}function
fulltextSql($A,array$u,$F,$ab){return
idf_escape($A)." MATCH ".q($F);}function
insertUpdate($Q,array$J,array$Rj){$Y=array();foreach($J
as$N)$Y[]="(".implode(", ",$N).")";return
queries("REPLACE INTO ".table($Q)." (".implode(", ",array_keys(reset($J))).") VALUES\n".implode(",\n",$Y));}function
tableHelp($A,$dg=false){if(preg_match('~^sqlite_(seq|stat.)~',$A,$_))return"fileformat2.html#$_[1]tab";if(preg_match('~^sqlite(_temp)?_(master|schema)$~',$A))return"schematab.html";}function
checkConstraints($Q){$i=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q),0,$this->conn);preg_match_all('~\s(?:CONSTRAINT\s+("(?:[^"]|"")*"|`(?:[^`]|``)*`|\[[^]]*]|\S+)\s+)?CHECK\s*(\(((?>[^()]+)|(?2))*\))~',$i,$Rg,PREG_SET_ORDER);$H=array();foreach($Rg
as$_){$zb=trim(substr($_[2],1,-1));$H[$_[1]!=""?idf_unescape($_[1]):$zb]=$zb;}return$H;}function
allFields(){$H=array();if(min_version(3.16)){$J=get_rows('SELECT m.name AS tab, p.name AS field, p.type, p."notnull", p.pk AS '.idf_escape("primary")."
FROM sqlite_master m, pragma_table_".(min_version(3.31)?"x":"")."info(m.name) p
WHERE m.type IN ('table', 'view')".(min_version(3.31)?"
AND p.hidden != 1":"").(min_version(3.37)?"
AND m.name NOT IN (SELECT name FROM pragma_table_list WHERE type = 'shadow')":"")."
ORDER BY (m.name LIKE 'sqlite_%'), m.name, p.cid",$this->conn);foreach($J
as$I){$I["type"]=type_affinity($I["type"]);$I["null"]=!$I["notnull"];$H[$I["tab"]][]=$I;}}else$H=parent::allFields();return$H;}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return
idf_escape($t);}function
get_databases($ne){return
array();}function
limit($F,$Z,$y,$B=0,$il=" "){return" $F$Z".($y?$il."LIMIT $y".($B?" OFFSET $B":""):"");}function
limit1($Q,$F,$Z,$il="\n"){return(preg_match('~^INTO~',$F)||get_val("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($F,$Z,1,0,$il):" $F WHERE rowid = (SELECT rowid FROM ".table($Q).$Z.$il."LIMIT 1)");}function
db_collation($k,array$Eb){return
get_val("PRAGMA encoding");}function
logged_user(){return
get_current_user();}function
virtual_module($Ml){return(preg_match('~^CREATE\s+VIRTUAL\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?(?:"[^"]*+"|`[^`]*+`|\[[^\]]*+\]|[^\s(]+)\s+USING\s+([a-z0-9_]+)~i',$Ml,$_)?$_[1]:"");}function
parse_comments($Ml){$H=array();if(virtual_module($Ml)!="")return$H;preg_match_all('~\'(?:[^\']|\'\')*\'|"(?:[^"]|"")*"|`(?:[^`]|``)*`|\[[^]]*]|--[^\n]*|/\*.*?\*/|[(),\n]|[^-/\'"`[(),\n]+|[-/]~s',$Ml,$Rg);$Fc=0;$Kh=array();$ug="";$Xh=true;foreach($Rg[0]as$Um){if(preg_match('~^--~',$Um)){if($Fc==1&&!$Xh){$A=($ug=="("?"":($ug==","?idx($Kh,count($Kh)-2):end($Kh)));if(is_string($A))$H[$A]=trim(substr($Um,2));}}elseif($Um=="\n")$Xh=true;elseif(trim($Um)!=""&&!preg_match('~^/\*~',$Um)){$Xh=false;$ug=($Fc==1&&$Um==","?",":($Fc==0&&$Um=="("?"(":""));if($ug)$Kh[]=false;elseif($Fc==1&&end($Kh)===false&&$Um!="("&&$Um!=")"){$ik=preg_match('~^[\'"`[]~',$Um);$lo=($ik?idf_unescape($Um):preg_replace('~\s.*~s','',trim($Um)));$Kh[count($Kh)-1]=($ik||!preg_match('~^(CONSTRAINT|PRIMARY|UNIQUE|CHECK|FOREIGN)$~i',$lo)?$lo:null);}$Fc+=($Um=="("?1:($Um==")"?-1:0));}}return$H;}function
tables_list(){return
get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view')".(min_version(3.37)?" AND name NOT IN (SELECT name FROM pragma_table_list WHERE type = 'shadow')":"")."
ORDER BY (name LIKE 'sqlite_%'), name");}function
count_tables(array$j){return
array();}function
db_status(){$Zi=get_val("PRAGMA page_size");$xe=get_val("PRAGMA freelist_count")*$Zi;return
array("Data_length"=>get_val("PRAGMA page_count")*$Zi-$xe,"Index_length"=>0,"Data_free"=>$xe,);}function
table_status($A="",$Ud=false){$H=array();$J=array();if(!$Ud&&$A==""){connection()->query("PRAGMA optimize = 0x10002");$J=get_key_vals("SELECT tbl, MAX(CAST(stat AS integer)) FROM sqlite_stat1 GROUP BY tbl");}foreach(get_rows("SELECT name AS Name, type AS Engine, sql, 'rowid' AS Oid, '' AS Auto_increment".(min_version(3.37)?", name IN (SELECT name FROM pragma_table_list WHERE type = 'shadow') AS dependent":"")." FROM sqlite_master WHERE type IN ('table', 'view') ".($A!=""?"AND name = ".q($A):"ORDER BY (name LIKE 'sqlite_%'), name"))as$I){if($I["Engine"]=="table"){$Ml=preg_replace('~(?:\s|--[^\n]*|/\*.*?\*/)+$~s','',$I["sql"]);$dm=preg_replace('~.*\)~s','',$Ml);$I["Engine"]=virtual_module($I["sql"])?:(implode(", ",array_filter(array((preg_match('~\bSTRICT\b~i',$dm)?"STRICT":0),(preg_match('~\bWITHOUT\s+ROWID\b~i',$dm)?"WITHOUT ROWID":0),)))?:"table");$I["Comment"]=idx(parse_comments($I["sql"]),"","");}unset($I["sql"]);$I["Rows"]=idx($J,$I["Name"],0);$H[$I["Name"]]=$I;}if(!$Ud){foreach(get_rows("SELECT * FROM sqlite_sequence".($A!=""?" WHERE name = ".q($A):""),null,"")as$I)$H[$I["name"]]["Auto_increment"]=$I["seq"];}return$H;}function
is_view(array$R){return$R["Engine"]=="view";}function
fk_support(array$R){return!get_val("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");}function
type_affinity($T){$T=strtolower($T);return(preg_match('~int~i',$T)?"integer":(preg_match('~char|clob|text~i',$T)?"text":(preg_match('~blob~i',$T)?"blob":(preg_match('~real|floa|doub~i',$T)?"real":(preg_match('~any~i',$T)?"any":"numeric")))));}function
fields($Q){$H=array();$Ml=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));$Wj=array("select"=>1,"where"=>1,"order"=>1);if(!preg_match('~^sqlite(_temp)?_(master|schema)$~',$Q))$Wj+=array("insert"=>1,"update"=>1);$ze=preg_match('~^fts\d+$~i',virtual_module($Ml));$Lb=parse_comments($Ml);foreach(get_rows("PRAGMA table_".(min_version(3.31)?"x":"")."info(".table($Q).")")as$I){if($I["hidden"]==1)continue;$A=$I["name"];$T=strtolower($I["type"]);$l=$I["dflt_value"];preg_match('~^(.+?)\s*(?:\((.*)\))?$~s',$T,$qj);$ik=preg_match("~^'(?:[^']|'')*'$~",$l);$H[$A]=array("field"=>$A,"type"=>($ze?"text":($qj?$qj[1]:type_affinity($T))),"full_type"=>$T,"length"=>($ze?"":idx($qj,2,"")),"default"=>($ik?idf_unescape($l):($l=="NULL"?null:$l)),"default_sql"=>($l!==null&&!$ik&&$l!="NULL"&&!is_numeric($l)),"null"=>!$I["notnull"],"privileges"=>$Wj,"primary"=>$I["pk"],"comment"=>idx($Lb,$A,""),);if($I["pk"]&&preg_match('~\bAUTOINCREMENT\b~i',$Ml))$H[$A]["auto_increment"]=true;}$t='[(,]\s*(("[^"]*+")+|[a-z0-9_]+)';$_k='(?:[^,()\']|\'[^\']*+\'|\([^)]*+\))*?';preg_match_all('~'.$t.'\s+text\b'.$_k.'COLLATE\s+(\'[^\']+\'|[a-z0-9_]+)~i',$Ml,$Rg,PREG_SET_ORDER);foreach($Rg
as$_){$A=idf_unescape($_[1]);if($H[$A])$H[$A]["collation"]=trim($_[3],"'");}preg_match_all('~'.$t.'\s'.$_k.'GENERATED\s+ALWAYS\s+AS\s*\((.+?)\)\s+(STORED|VIRTUAL)~i',$Ml,$Rg,PREG_SET_ORDER);foreach($Rg
as$_){$A=idf_unescape($_[1]);if($H[$A]){$H[$A]["default"]=$_[3];$H[$A]["generated"]=strtoupper($_[4]);}}return$H;}function
indexes($Q,$h=null){$h=connection($h);$H=array();$Ml=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q),0,$h);if(preg_match('~^fts\d+$~i',virtual_module($Ml)))return
array($Q=>array("type"=>"FULLTEXT","columns"=>array_keys(fields($Q)),"lengths"=>array(),"descs"=>array()));if(preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',$Ml,$_)){$H[""]=array("type"=>"PRIMARY","columns"=>array(),"lengths"=>array(),"descs"=>array());preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',$_[1],$Rg,PREG_SET_ORDER);foreach($Rg
as$_){$H[""]["columns"][]=idf_unescape($_[2]).$_[4];$H[""]["descs"][]=(preg_match('~DESC~i',$_[5])?'1':null);}}if(!$H){foreach(fields($Q)as$A=>$n){if($n["primary"])$H[""]=array("type"=>"PRIMARY","columns"=>array($A),"lengths"=>array(),"descs"=>array(null));}}$Rl=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($Q),$h);foreach(get_rows("PRAGMA index_list(".table($Q).")",$h)as$I){$A=$I["name"];$u=array("type"=>($I["unique"]?"UNIQUE":"INDEX"));$u["lengths"]=array();$u["descs"]=array();foreach(get_rows("PRAGMA index_info(".idf_escape($A).")",$h)as$Pk){$u["columns"][]=$Pk["name"];$u["descs"][]=null;}if(preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($A).' ON '.idf_escape($Q),'~').' \((.*)\)$~i',$Rl[$A],$vk)){preg_match_all('/("[^"]*+")+( DESC)?/',$vk[2],$Rg);foreach($Rg[2]as$w=>$W){if($W)$u["descs"][$w]='1';}}if(!$H[""]||$u["type"]!="UNIQUE"||$u["columns"]!=$H[""]["columns"]||$u["descs"]!=$H[""]["descs"]||!preg_match("~^sqlite_~",$A))$H[$A]=$u;}return$H;}function
foreign_keys($Q){$H=array();foreach(get_rows("PRAGMA foreign_key_list(".table($Q).")")as$I){$q=&$H[$I["id"]];if(!$q)$q=$I;$q["source"][]=$I["from"];$q["target"][]=$I["to"];}return$H;}function
view($A){return
array("select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+(?=\S)~iU','',get_val("SELECT sql FROM sqlite_master WHERE type = 'view' AND name = ".q($A))));}function
collations(){return(isset($_GET["create"])?get_vals("PRAGMA collation_list",1):array());}function
information_schema($k,$K=""){return
false;}function
error(){return
h(connection()->error);}function
check_sqlite_name($A){$Qd="db|sdb|sqlite";if(!preg_match("~^[^\\0]*\\.($Qd)\$~",$A)){connection()->error=sprintf('Please use one of these file extensions: %s.',str_replace("|",", ",$Qd));return
false;}return
true;}function
create_database($k,$Db){if(file_exists($k)){connection()->error='File exists.';return
false;}if(!check_sqlite_name($k))return
false;try{$z=new
Db();$z->attach(server_parts(array("path"=>$k)),'','');}catch(\Exception$Gd){connection()->error=$Gd->getMessage();return
false;}$z->query('PRAGMA encoding = "UTF-8"');$z->query('CREATE TABLE adminer (i)');$z->query('DROP TABLE adminer');return
true;}function
drop_databases(array$j){connection()->attach(server_parts(array("path"=>":memory:")),'','');foreach($j
as$k){if(!check_sqlite_name($k))return
false;if(!@unlink($k)){connection()->error='File exists.';return
false;}}return
true;}function
rename_database($A,$Db){if(!check_sqlite_name($A))return
false;connection()->attach(server_parts(array("path"=>":memory:")),'','');connection()->error='File exists.';return@rename(DB,$A);}function
auto_increment(){return" PRIMARY KEY AUTOINCREMENT";}function
alter_table($Q,$A,array$o,array$pe,$f,$td,$Db,$La,$nj){$In=($Q==""||$pe||$td||$f!==null);foreach($o
as$n){if($n[0]!=""||!$n[1]||$n[2]||$n[1][5]){$In=true;break;}}$b=array();$Ti=array();foreach($o
as$n){if($n[1]){$b[]=($In?$n[1]:"ADD ".implode($n[1]));if($n[0]!="")$Ti[$n[0]]=$n[1][0];}}if(!$In){foreach($b
as$W){if(!queries("ALTER TABLE ".table($Q)." $W"))return
false;}if($Q!=$A&&!queries("ALTER TABLE ".table($Q)." RENAME TO ".table($A)))return
false;}elseif(!recreate_table($Q,$A,$b,$Ti,$pe,$La,array(),"","",$td,$f))return
false;if($La){queries("BEGIN");queries("UPDATE sqlite_sequence SET seq = $La WHERE name = ".q($A));if(!connection()->affected_rows)queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($A).", $La)");queries("COMMIT");}return
true;}function
recreate_table($Q,$A,array$o,array$Ti,array$pe,$La="",$v=array(),$bd="",$la="",$td="",$f=null){if($Q!=""){if(!$o){foreach(fields($Q)as$w=>$n){if($v)$n["auto_increment"]=0;$o[]=process_field($n,$n);$Ti[$w]=idf_escape($w);}}$Sj=false;foreach($o
as$n){if($n[6])$Sj=true;}$dd=array();foreach($v
as$w=>$W){if($W[2]=="DROP"){$dd[$W[1]]=true;unset($v[$w]);}}foreach(indexes($Q)as$kg=>$u){$e=array();foreach($u["columns"]as$w=>$d){if(!$Ti[$d])continue
2;$e[]=$Ti[$d].($u["descs"][$w]?" DESC":"");}if(!$dd[$kg]){if($u["type"]!="PRIMARY"||!$Sj)$v[]=array($u["type"],$kg,$e);}}foreach($v
as$w=>$W){if($W[0]=="PRIMARY"){unset($v[$w]);$pe[]="  PRIMARY KEY (".implode(", ",$W[2]).")";}elseif(preg_match('~^sqlite_autoindex_~',$W[1])){unset($v[$w]);$pe[]="  UNIQUE (".implode(", ",$W[2]).")";}}foreach(foreign_keys($Q)as$kg=>$q){foreach($q["source"]as$w=>$d){if(!$Ti[$d])continue
2;$q["source"][$w]=idf_unescape($Ti[$d]);}if(!isset($pe[" $kg"]))$pe[]=" ".format_foreign_key($q);}queries("PRAGMA foreign_keys = 0");queries("PRAGMA legacy_alter_table = 1");queries("BEGIN");}$lb=array();$Lb=array();foreach($o
as$n){if(preg_match('~GENERATED~',$n[3]))unset($Ti[array_search($n[0],$Ti)]);if(preg_match("~^ COMMENT ('.*')$~s",$n[5],$_))$Lb[count($lb)]=idf_unescape($_[1]);unset($n[5]);$lb[]="  ".implode($n);}$lb=array_merge($lb,array_values(array_filter($pe)));foreach(driver()->checkConstraints($Q)as$w=>$pb){if($w!=$bd)$lb[]=" ".($w!=$pb?" CONSTRAINT ".idf_escape($w):"")." CHECK ($pb)";}if($la)$lb[]="  $la";$Gm=($Q!=""&&$Q==$A?"adminer_$A":$A);if($Q!=""&&(!$td||$f===null)){$O=table_status1($Q);$td=$td?:idx($O,"Engine");$f=($f===null?idx($O,"Comment",""):$f);}$Gg=function($Im){return($Im!=""?" -- ".preg_replace('~[\r\n]+~',' ',$Im):"");};$i="CREATE TABLE ".table($Gm)." (".$Gg($f)."\n";foreach($lb
as$r=>$kb)$i
.=$kb.($r<count($lb)-1?",":"").$Gg(idx($Lb,$r))."\n";if(!queries("$i)".($td!="table"&&in_array($td,driver()->engines())?" $td":"")))return
false;if($Q!=""){if($Ti&&!queries("INSERT INTO ".table($Gm)." (".implode(", ",$Ti).") SELECT ".implode(", ",array_map('Adminer\idf_escape',array_keys($Ti)))." FROM ".table($Q)))return
false;$jn=array();foreach(triggers($Q)as$hn=>$Nm){$fn=trigger($hn,$Q);$jn[]="CREATE TRIGGER ".idf_escape($hn)." ".implode(" ",$Nm)." ON ".table($A)."\n$fn[Statement]";}$La=$La?"":get_val("SELECT seq FROM sqlite_sequence WHERE name = ".q($Q));if(!queries("DROP TABLE ".table($Q))||($Q==$A&&!queries("ALTER TABLE ".table($Gm)." RENAME TO ".table($A)))||!alter_indexes($A,$v))return
false;if($La)queries("UPDATE sqlite_sequence SET seq = $La WHERE name = ".q($A));foreach($jn
as$fn){if(!queries($fn))return
false;}if(get_rows("PRAGMA foreign_key_check(".table($A).")")){connection()->error="FOREIGN KEY constraint failed";return
false;}queries("COMMIT");queries("PRAGMA foreign_keys = 1");queries("PRAGMA legacy_alter_table = 0");}return
true;}function
index_sql($Q,$T,$A,$e){return"CREATE $T ".($T!="INDEX"?"INDEX ":"").idf_escape($A!=""?$A:uniqid($Q."_"))." ON ".table($Q)." $e";}function
alter_indexes($Q,$b){foreach($b
as$Rj){if($Rj[0]=="PRIMARY")return
recreate_table($Q,$Q,array(),array(),array(),"",$b);}foreach(array_reverse($b)as$W){if(!queries($W[2]=="DROP"?"DROP INDEX ".idf_escape($W[1]):index_sql($Q,$W[0],$W[1],"(".implode(", ",$W[2]).")")))return
false;}return
true;}function
truncate_tables(array$S){return
apply_queries("DELETE FROM",$S);}function
drop_views(array$Zn){return
apply_queries("DROP VIEW",$Zn);}function
drop_tables(array$S){return
apply_queries("DROP TABLE",$S);}function
move_tables(array$S,array$Zn,$Cm){return
false;}function
trigger($A,$Q){if($A=="")return
array("Statement"=>"BEGIN\n\t;\nEND");$t='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';$in=trigger_options();preg_match("~^CREATE\\s+TRIGGER\\s*$t\\s*(".implode("|",$in["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($t))?\\s+ON\\s*$t\\s*(?:FOR\\s+EACH\\s+ROW\\s+)?(.*)~is",get_val("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($A)),$_);if(!$_)return
array();$hi=$_[3];return
array("Timing"=>strtoupper($_[1]),"Event"=>strtoupper($_[2]).($hi?" OF":""),"Of"=>idf_unescape($hi),"Trigger"=>$A,"Statement"=>$_[4],);}function
triggers($Q){$H=array();$in=trigger_options();foreach(get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q))as$I){preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|",$in["Timing"]).')\s*(.*?)\s+ON\b~i',$I["sql"],$_);$H[$I["name"]]=array($_[1],$_[2]);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
last_id($G){return
get_val("SELECT LAST_INSERT_ROWID()");}function
explain(Db$g,$F){return$g->query("EXPLAIN QUERY PLAN $F");}function
found_rows(array$R,array$Z){}function
types($Qd=false){return
array();}function
create_sql($Q,$La,$bm){$H=get_val("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($Q));foreach(indexes($Q)as$A=>$u){if($A==''||$u['type']=='FULLTEXT')continue;$H
.=";\n\n".index_sql($Q,$u['type'],$A,"(".implode(", ",array_map('Adminer\idf_escape',$u['columns'])).")");}return$H;}function
truncate_sql($Q){return"DELETE FROM ".table($Q);}function
use_sql($sc,$bm=""){return"";}function
trigger_sql($Q){return
implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q)));}function
show_variables(){$H=array();foreach(get_rows("PRAGMA pragma_list")as$I){$A=$I["name"];if($A!="pragma_list"&&$A!="compile_options"){$H[$A]=array($A,'');foreach(get_rows("PRAGMA $A")as$I)$H[$A][1].=implode(", ",$I)."\n";}}return$H;}function
show_status(){$H=array();foreach(get_vals("PRAGMA compile_options")as$Ci)$H[]=explode("=",$Ci,2)+array('','');return$H;}function
convert_field(array$n){}function
unconvert_field(array$n,$H){return$H;}function
support($Vd){return
preg_match('~^(check|columns|comment|database|drop_col|dump|fast_status|indexes|descidx|move_col|sql|status|table|transaction_ddl|trigger|variables|view|view_trigger)$~',$Vd);}}add_driver("mssql","MS SQL");if(isset($_GET["mssql"])){define('Adminer\DRIVER',"mssql");if(extension_loaded("sqlsrv")&&$_GET["ext"]!="pdo"){class
Db
extends
SqlDb{var$extension="SQLSRV";private$link,$result,$warnings,$transaction=false;private
function
get_error(){$this->error="";foreach(sqlsrv_errors()as$m){$this->errno=$m["code"];$this->error
.="$m[message]\n";}$this->error=rtrim($this->error);}function
attach(array$M,$U,$D){sqlsrv_configure("WarningsReturnAsErrors",0);$Sb=array("UID"=>$U,"PWD"=>$D,"CharacterSet"=>"UTF-8","ReturnDatesAsStrings"=>true);if(isset($_GET["sql"])&&!self::$instance)$Sb["MultipleActiveResultSets"]=false;$Sl=adminer()->connectSsl();if(isset($Sl["Encrypt"]))$Sb["Encrypt"]=$Sl["Encrypt"];if(isset($Sl["TrustServerCertificate"]))$Sb["TrustServerCertificate"]=$Sl["TrustServerCertificate"];$k=adminer()->database();if($k!="")$Sb["Database"]=$k;$Ej=$M["port"];$this->link=@sqlsrv_connect($M["host"].($Ej?",$Ej":""),$Sb);if($this->link){$If=sqlsrv_server_info($this->link);$this->server_info=$If['SQLServerVersion'];}else$this->get_error();return($this->link?'':$this->error);}function
quote($P){return
unicode_prefix($P)."'".str_replace("'","''",$P)."'";}function
select_db($sc){return$this->query(use_sql($sc));}function
query($F,$rn=false){$G=sqlsrv_query($this->link,$F);$this->error="";if(!$G){$this->get_error();return
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
as$co)$H[]=$co["message"];return$H;}function
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
fetch_field(){if(!$this->fields)$this->fields=sqlsrv_field_metadata($this->result);$qn=array(-155=>"datetimeoffset","time",-152=>"xml","varbinary","sql_variant",-11=>"uniqueidentifier","ntext","nvarchar","nchar","bit","tinyint","bigint","image","varbinary","binary","text",1=>"char","numeric","decimal","int","smallint","float","real","float",12=>"varchar",91=>"date","time","datetime",);$n=$this->fields[$this->offset++];$H=new
\stdClass;$H->name=$n["Name"];$H->native_type=idx($qn,$n["Type"],"");return$H;}function
seek($B){for($r=0;$r<$B;$r++)sqlsrv_fetch($this->result);}}function
last_id($G){return(string)get_val("SELECT SCOPE_IDENTITY()");}function
explain(Db$g,$F){$g->query("SET SHOWPLAN_ALL ON");$H=$g->query($F);$g->query("SET SHOWPLAN_ALL OFF");return$H;}}else{abstract
class
MssqlDb
extends
PdoDb{function
quote($P){return
unicode_prefix($P).parent::quote($P);}function
select_db($sc){return$this->query(use_sql($sc));}function
lastInsertId(){return$this->pdo->lastInsertId();}function
inTransaction(){return
parent::inTransaction()||(isset($_GET["sql"])&&get_val("SELECT @@TRANCOUNT"));}function
rollback(){return(parent::inTransaction()||!isset($_GET["sql"])?parent::rollback():!!$this->query("IF @@TRANCOUNT > 0 ROLLBACK"));}function
warnings(){$G=$this->multi;if(!is_object($G))return
array();$m=$G->errorInfo();return
array((string)$m[2]);}}function
last_id($G){return
connection()->lastInsertId();}function
explain(Db$g,$F){}if(extension_loaded("pdo_sqlsrv")){class
Db
extends
MssqlDb{var$extension="PDO_SQLSRV";function
attach(array$M,$U,$D){$Ej=$M["port"];$fd="sqlsrv:Server=$M[host]".($Ej?",$Ej":"").(isset($_GET["sql"])&&!self::$instance?";MultipleActiveResultSets=0":"");$Sl=adminer()->connectSsl();foreach(array("Encrypt","TrustServerCertificate")as$w){if(isset($Sl[$w]))$fd
.=";$w=".($Sl[$w]?1:0);}return$this->dsn($fd,$U,$D,array(\PDO::SQLSRV_ATTR_DIRECT_QUERY=>true));}}}elseif(extension_loaded("pdo_dblib")){class
Db
extends
MssqlDb{var$extension="PDO_DBLIB";function
attach(array$M,$U,$D){$Ej=$M["port"];$El=$M["socket"];$Ei=array(1002=>true);$H=$this->dsn("dblib:charset=utf8;host=$M[host]".($Ej!=""?";port=$Ej":($El!=""?";unix_socket=$El":"")),$U,$D,$Ei);if(!$H){$this->query("SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON");$this->server_info=get_val("SELECT CAST(SERVERPROPERTY('ProductVersion') AS varchar(20))",0,$this);}return$H;}}}}class
Driver
extends
SqlDriver{static$extensions=array("SQLSRV","PDO_SQLSRV","PDO_DBLIB");static$jush="mssql";static$serverSocket=true;var$insertFunctions=array("date|time"=>"getdate");var$editFunctions=array("int|decimal|real|float|money|datetime"=>"+/-","char|text"=>"+",);var$functions=array("len","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");var$generated=array("PERSISTED","VIRTUAL");var$onActions="NO ACTION|CASCADE|SET NULL|SET DEFAULT";var$inout="|OUTPUT";private$unknownTypes=array();function
operators($lm){return
array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","BETWEEN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");}static
function
connect($M,$U,$D){if($M=="")$M="localhost:1433";return
parent::connect($M,$U,$D);}function
__construct(Db$g){parent::__construct($g);$this->types=array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"int"=>10,"bigint"=>20,"bit"=>1,"decimal"=>0,"numeric"=>0,"real"=>12,"float"=>53,"smallmoney"=>10,"money"=>20,"vector"=>0,),'Date and time'=>array("date"=>10,"smalldatetime"=>19,"datetime"=>23,"datetime2"=>19,"time"=>8,"datetimeoffset"=>26),'Strings'=>array("char"=>8000,"varchar"=>8000,"text"=>2147483647,"nchar"=>4000,"nvarchar"=>4000,"ntext"=>1073741823,"uniqueidentifier"=>36,"xml"=>2147483647,"json"=>2147483647,"sql_variant"=>8000,"hierarchyid"=>892,),'Binary'=>array("binary"=>8000,"varbinary"=>8000,"image"=>2147483647),'Geometry'=>array("geometry"=>0,"geography"=>0),);$qn=array_flip(get_vals("SELECT name FROM sys.types WHERE is_user_defined = 0 ORDER BY name"));if($qn){foreach($this->types
as$Ke=>$Ne){foreach($Ne
as$T=>$x){if(isset($qn[$T]))unset($qn[$T]);else
unset($this->types[$Ke][$T]);}if(!$this->types[$Ke])unset($this->types[$Ke]);}$this->unknownTypes=array_keys($qn);}}function
types(){return
parent::types()+array_fill_keys($this->unknownTypes,0);}function
structuredTypes(){return
array_merge(parent::structuredTypes(),$this->unknownTypes);}function
typeName(\stdClass$n){return
idx((array)$n,'sqlsrv:decl_type',parent::typeName($n));}function
insertUpdate($Q,array$J,array$Rj){$o=fields($Q);$Bn=array();$Z=array();$N=reset($J);$e="c".implode(", c",range(1,count($N)));$eb=0;$Of=array();foreach($N
as$w=>$W){$eb++;$A=idf_unescape($w);if(!$o[$A]["auto_increment"])$Of[$w]="c$eb";if(isset($Rj[$A]))$Z[]="$w = c$eb";else$Bn[]="$w = c$eb";}$Y=array();foreach($J
as$N)$Y[]="(".implode(", ",$N).")";if($Z){$rf=queries("SET IDENTITY_INSERT ".table($Q)." ON");$H=queries("MERGE ".table($Q)." USING (VALUES\n\t".implode(",\n\t",$Y)."\n) AS source ($e) ON ".implode(" AND ",$Z).($Bn?"\nWHEN MATCHED THEN UPDATE SET ".implode(", ",$Bn):"")."\nWHEN NOT MATCHED THEN INSERT (".implode(", ",array_keys($rf?$N:$Of)).") VALUES (".($rf?$e:implode(", ",$Of)).");");if($rf)queries("SET IDENTITY_INSERT ".table($Q)." OFF");}else$H=queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES\n".implode(",\n",$Y));return$H;}function
begin(){remember_query("BEGIN TRANSACTION");return$this->conn->begin();}function
convertSearch($t,array$W,array$n){return(preg_match('~^(bit|n?text|xml|json|vector|uniqueidentifier|sql_variant|hierarchyid|geography|geometry)$~',$n["type"])?"CAST($t AS nvarchar(max))":$t);}function
quoteBinary($Rk){return"0x".bin2hex($Rk);}function
warnings(){$H=array();foreach($this->conn->warnings()as$jh){$jh=trim(preg_replace('~^(\[[^]]+])+~','',$jh));if($jh!="")$H[]=$jh;}return
nl_br(h(implode("\n",$H)));}function
tableHelp($A,$dg=false){$Hg=array("sys"=>"catalog-views/sys-","INFORMATION_SCHEMA"=>"information-schema-views/",);$z=$Hg[get_schema()];if($z)return"relational-databases/system-$z".preg_replace('~_~','-',strtolower($A))."-transact-sql";}function
isSystem($k,$K=""){return($K!=""?information_schema($k,$K)||preg_match('~^(guest|db_(owner|accessadmin|securityadmin|ddladmin|backupoperator|(deny)?data(reader|writer)))$~',$K):in_array($k,array("master","tempdb","model","msdb")));}}function
unicode_prefix($P){return(strlen($P)!=utf8_length($P)?"N":"");}function
idf_escape($t){return"[".str_replace("]","]]",$t)."]";}function
table($t){return($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($t);}function
get_databases($ne){return
get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");}function
limit($F,$Z,$y,$B=0,$il=" "){return($y?" TOP (".($y+$B).")":"")." $F$Z";}function
limit1($Q,$F,$Z,$il="\n"){return
limit($F,$Z,1,0,$il);}function
db_collation($k,array$Eb){return
get_val("SELECT collation_name FROM sys.databases WHERE name = ".q($k));}function
logged_user(){return
get_val("SELECT SUSER_NAME()");}function
tables_list(){return
get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");}function
count_tables(array$j){$H=array();foreach($j
as$k){connection()->select_db($k);$H[$k]=get_val("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");}return$H;}function
table_status($A="",$Ud=false){$H=array();$Cl=array();foreach(get_rows("SELECT object_id, SUM(CASE WHEN index_id < 2 THEN row_count ELSE 0 END) AS [Rows],
SUM(CASE WHEN index_id < 2 THEN used_page_count ELSE 0 END) * 8192 AS Data_length,
SUM(CASE WHEN index_id > 1 THEN used_page_count ELSE 0 END) * 8192 AS Index_length,
SUM(reserved_page_count - used_page_count) * 8192 AS Data_free
FROM sys.dm_db_partition_stats
GROUP BY object_id",null,"")as$I){$gi=$I["object_id"];unset($I["object_id"]);$Cl[$gi]=$I;}foreach(get_rows("SELECT ao.object_id, ao.name AS Name, ao.type_desc AS Engine,
	(SELECT cast(value as varchar(max)) FROM fn_listextendedproperty('MS_Description', 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment,
	CASE WHEN EXISTS (SELECT 1 FROM sys.indexes i WHERE i.object_id = ao.object_id AND i.is_disabled = 1) THEN 1 END AS invalid_index
FROM sys.all_objects AS ao
WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($A!=""?"AND name = ".q($A):"ORDER BY name"))as$I){$gi=$I["object_id"];unset($I["object_id"]);$H[$I["Name"]]=$I+idx($Cl,$gi,array());}return$H;}function
is_view(array$R){return$R["Engine"]=="VIEW";}function
fk_support(array$R){return
true;}function
type_length($T,array$I){return(preg_match("~char|binary~",$T)?($I["max_length"]==-1?"max":intval($I["max_length"])/($T[0]=='n'?2:1)):($T=="decimal"?"$I[precision],$I[scale]":(preg_match('~^(datetime2|datetimeoffset|time)$~',$T)?$I["scale"]:($T=="vector"?(intval($I["max_length"])-8)/4:""))));}function
fields($Q){$Lb=get_key_vals("SELECT objname, cast(value as varchar(max)) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($Q).", 'column', NULL)");$H=array();$mm=get_val("SELECT object_id FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') AND name = ".q($Q));foreach(get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name,
	COALESCE(bt.name, t.name) type, d.definition [default], d.name default_constraint, i.is_primary_key
FROM sys.all_columns c
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.types bt ON t.system_type_id = bt.user_type_id AND t.is_user_defined = 1
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.object_id
LEFT JOIN sys.index_columns ic ON c.object_id = ic.object_id AND c.column_id = ic.column_id
LEFT JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id
WHERE c.object_id = ".q($mm))as$I){$T=$I["type"];$x=type_length($T,$I);$l=$I["default"];$_c=false;if(preg_match("~^\\(N?('(?:[^']|'')*')\\)$~s",$l,$_))$l=idf_unescape($_[1]);elseif(preg_match('~^\(\(([-+]?[\d.]+)\)\)$~',$l,$_))$l=$_[1];elseif($l!==null){$l=preg_replace('~^\((.*)\)$~s','\1',$l);$_c=true;}$H[$I["name"]]=array("field"=>$I["name"],"full_type"=>$T.($x!=""?"($x)":""),"type"=>$T,"length"=>$x,"default"=>$l,"default_sql"=>$_c,"default_constraint"=>$I["default_constraint"],"null"=>$I["is_nullable"],"auto_increment"=>$I["is_identity"],"collation"=>$I["collation_name"],"privileges"=>($T=="timestamp"?array("select"=>1,"where"=>1,"order"=>1):array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1)),"primary"=>$I["is_primary_key"],"comment"=>$Lb[$I["name"]],);}foreach(get_rows("SELECT * FROM sys.computed_columns WHERE object_id = ".q($mm))as$I){$H[$I["name"]]["generated"]=($I["is_persisted"]?"PERSISTED":"VIRTUAL");$H[$I["name"]]["default"]=$I["definition"];}return$H;}function
indexes($Q,$h=null){$H=array();foreach(get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, is_disabled, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($Q),$h)as$I){$A=$I["name"];$H[$A]["type"]=($I["is_primary_key"]?"PRIMARY":($I["is_unique"]?"UNIQUE":"INDEX"));$H[$A]["lengths"]=array();$H[$A]["invalid"]=!!$I["is_disabled"];$H[$A]["columns"][$I["key_ordinal"]]=$I["column_name"];$H[$A]["descs"][$I["key_ordinal"]]=($I["is_descending_key"]?'1':null);}return$H;}function
view($A){return
array("select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',get_val("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($A))));}function
collations(){$H=array();foreach(get_vals("SELECT name FROM fn_helpcollations()")as$Db)$H[preg_replace('~_.*~','',$Db)][]=$Db;return$H;}function
information_schema($k,$K=""){return
in_array($K!=""?$K:get_schema(),array("INFORMATION_SCHEMA","sys"));}function
error(){return
nl_br(h(preg_replace('~^(\[[^]]*])+~m','',connection()->error)));}function
create_database($k,$Db){return
queries("CREATE DATABASE ".idf_escape($k).(preg_match('~^[a-z0-9_]+$~i',$Db)?" COLLATE $Db":""));}function
drop_databases(array$j){return!!queries("DROP DATABASE ".implode(", ",array_map('Adminer\idf_escape',$j)));}function
rename_database($A,$Db){if(preg_match('~^[a-z0-9_]+$~i',$Db))queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $Db");queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($A));return
true;}function
auto_increment(){return" IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";}function
alter_table($Q,$A,array$o,array$pe,$f,$td,$Db,$La,$nj){$b=array();$Lb=array();$Pi=fields($Q);foreach($o
as$n){$d=idf_escape($n[0]);$W=$n[1];if(!$W)$b["DROP"][]=" COLUMN $d";else{$Ni=idx($Pi[$n[0]],"comment");if($W[5]!=($Ni!=""?" COMMENT ".q($Ni):""))$Lb[idf_unescape($W[0])]=array($Ni!="",substr($W[5],9));unset($W[5]);if(preg_match('~ AS ~',$W[3]))unset($W[1],$W[2]);if($n[0]=="")$b["ADD"][]="\n  ".implode("",$W).($Q==""?substr($pe[$W[0]],16+strlen($W[0])):"");else{$l=$W[3];unset($W[3]);unset($W[6]);if($d!=$W[0])queries("EXEC sp_rename ".q(table($Q).".$d").", ".q(idf_unescape($W[0])).", 'COLUMN'");$b["ALTER COLUMN ".implode("",$W)][]="";$Oi=$Pi[$n[0]];if(default_value($Oi)!=$l){if($Oi["default"]!==null)$b["DROP"][]=" ".idf_escape($Oi["default_constraint"]);if($l)$b["ADD"][]="\n $l FOR $d";}}}}if($Q==""){$ka=(array)$b["ADD"];foreach($pe
as$w=>$W){if(!is_string($w))$ka[]="\n$W";}if(!queries("CREATE TABLE ".table($A)." (".implode(",",$ka)."\n)"))return
false;}else{if($Q!=$A)queries("EXEC sp_rename ".q(table($Q)).", ".q($A));if($pe)$b[""]=$pe;foreach($b
as$w=>$W){if(!queries("ALTER TABLE ".table($A)." $w".implode(",",$W)))return
false;}}$ak="@name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($A);foreach($Lb
as$w=>$W){list($ad,$Jb)=$W;$bk="$ak, @level2type = N'Column', @level2name = ".q($w);if($ad)queries("EXEC sp_dropextendedproperty $bk");if($Jb!="")queries("EXEC sp_addextendedproperty $bk, @value = $Jb");}if($f!==null){if(idx(table_status1($A),"Comment")!="")queries("EXEC sp_dropextendedproperty $ak");if($f!="")queries("EXEC sp_addextendedproperty $ak, @value = ".q($f));}return
true;}function
alter_indexes($Q,$b){$u=array();$ad=array();$ek=array();foreach($b
as$W){if($W[2]=="DROP"){if($W[0]=="PRIMARY"||($W[0]=="UNIQUE"&&get_val("SELECT is_unique_constraint FROM sys.indexes WHERE object_id = OBJECT_ID(".q(table($Q)).") AND name = ".q($W[1]))))$ad[]=idf_escape($W[1]);else$u[]=idf_escape($W[1])." ON ".table($Q);}else$ek[]=($W[0]!="PRIMARY"?"CREATE $W[0] ".($W[0]!="INDEX"?"INDEX ":"").idf_escape($W[1]!=""?$W[1]:uniqid($Q."_"))." ON ".table($Q):"ALTER TABLE ".table($Q)." ADD PRIMARY KEY")." (".implode(", ",$W[2]).")";}if($ad)array_unshift($ek,"ALTER TABLE ".table($Q)." DROP ".implode(", ",$ad));if($u)array_unshift($ek,"DROP INDEX ".implode(", ",$u));foreach($ek
as$F){if(!queries($F))return
false;}return
true;}function
found_rows(array$R,array$Z){}function
foreign_keys($Q){$H=array();$wi=array("CASCADE","NO ACTION","SET NULL","SET DEFAULT");$K=get_schema();foreach(get_rows("EXEC sp_fkeys @fktable_name = ".q($Q).", @fktable_owner = ".q($K))as$I){$q=&$H[$I["FK_NAME"]];$q["db"]=($I["PKTABLE_QUALIFIER"]==DB?"":$I["PKTABLE_QUALIFIER"]);$q["ns"]=($I["PKTABLE_OWNER"]==$K?"":$I["PKTABLE_OWNER"]);$q["table"]=$I["PKTABLE_NAME"];$q["on_update"]=$wi[$I["UPDATE_RULE"]];$q["on_delete"]=$wi[$I["DELETE_RULE"]];$q["source"][]=$I["FKCOLUMN_NAME"];$q["target"][]=$I["PKCOLUMN_NAME"];}return$H;}function
truncate_tables(array$S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views(array$Zn){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$Zn)));}function
drop_tables(array$S){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$S)));}function
move_tables(array$S,array$Zn,$Cm){return
apply_queries("ALTER SCHEMA ".idf_escape($Cm)." TRANSFER",array_merge($S,$Zn));}function
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
routine($A,$T){$Cc=get_val("SELECT m.definition
FROM sys.objects o
JOIN sys.sql_modules m ON m.object_id = o.object_id
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.name = ".q($A)." AND o.type = ".q($T=="PROCEDURE"?"P":"FN"));if(!$Cc)return
array();$H=array("definition"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+(?=\S)~isU','',$Cc),"fields"=>array());foreach(get_rows("SELECT p.name, TYPE_NAME(p.user_type_id) [type], p.max_length, p.precision, p.scale, p.is_output
FROM sys.parameters p
JOIN sys.objects o ON p.object_id = o.object_id
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.name = ".q($A)."
ORDER BY p.parameter_id")as$I){$Zd=$I["type"];$x=type_length($Zd,$I);$n=array("field"=>preg_replace('~^@~','',$I["name"]),"type"=>$Zd,"length"=>$x,"full_type"=>$Zd.($x!=""?"($x)":""),"null"=>true,"inout"=>($I["is_output"]?"OUTPUT":""),);if($n["field"]=="")$H["returns"]=$n;else$H["fields"][]=$n;}return$H;}function
routines(){return
get_rows("SELECT o.name SPECIFIC_NAME, o.name ROUTINE_NAME,
	CASE o.type WHEN 'P' THEN 'PROCEDURE' ELSE 'FUNCTION' END ROUTINE_TYPE, TYPE_NAME(p.user_type_id) DTD_IDENTIFIER
FROM sys.objects o
LEFT JOIN sys.parameters p ON o.object_id = p.object_id AND p.parameter_id = 0
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.type IN ('P', 'FN')
ORDER BY o.name");}function
routine_languages(){return
array();}function
routine_options($Ik){return
array();}function
routine_id($A,array$I){return
table($A);}function
schemas(){return
get_vals("SELECT name FROM sys.schemas");}function
get_schema(){if($_GET["ns"]!="")return$_GET["ns"];return
get_val("SELECT SCHEMA_NAME()");}function
set_schema($K,$h=null){$_GET["ns"]=$K;return!!get_val("SELECT 1 FROM sys.schemas WHERE name = ".q($K),0,$h);}function
schema_comment($K){return(string)get_val("SELECT CAST(value AS nvarchar(max)) FROM fn_listextendedproperty('MS_Description', 'SCHEMA', ".q($K).", NULL, NULL, NULL, NULL)");}function
set_schema_comment($K,$f){$ak="@name = N'MS_Description', @level0type = N'Schema', @level0name = ".q($K);return(schema_comment($K)==""||queries("EXEC sp_dropextendedproperty $ak"))&&($f==""||queries("EXEC sp_addextendedproperty $ak, @value = ".q($f)));}function
create_sql($Q,$La,$bm){$O=table_status1($Q);if(is_view($O)){$Yn=view($Q);return"CREATE VIEW ".table($Q)." AS $Yn[select]";}$ak="EXEC sp_addextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($Q);$Lb=($O["Comment"]!=""?";\n\n$ak, @value = ".q($O["Comment"]):"");$o=array();$Rj=false;foreach(fields($Q)as$A=>$n){$W=process_field($n,$n);if($W[6])$Rj=true;unset($W[5]);$o[]=implode("",$W);if($n["comment"]!="")$Lb
.=";\n\n$ak, @level2type = N'Column', @level2name = ".q($A).", @value = ".q($n["comment"]);}foreach(indexes($Q)as$A=>$u){if(!$Rj||$u["type"]!="PRIMARY"){$e=array();foreach($u["columns"]as$w=>$W)$e[]=idf_escape($W).($u["descs"][$w]?" DESC":"");$A=idf_escape($A);$o[]=($u["type"]=="INDEX"?"INDEX $A":"CONSTRAINT $A ".($u["type"]=="UNIQUE"?"UNIQUE":"PRIMARY KEY"))." (".implode(", ",$e).")";}}foreach(driver()->checkConstraints($Q)as$A=>$pb)$o[]="CONSTRAINT ".idf_escape($A)." CHECK ($pb)";return"CREATE TABLE ".table($Q)." (\n\t".implode(",\n\t",$o)."\n)$Lb";}function
foreign_keys_sql($Q){$o=array();foreach(foreign_keys($Q)as$pe)$o[]=ltrim(format_foreign_key($pe));return($o?"ALTER TABLE ".table($Q)." ADD\n\t".implode(",\n\t",$o).";\n\n":"");}function
truncate_sql($Q){return"TRUNCATE TABLE ".table($Q);}function
use_sql($sc,$bm=""){return"USE ".idf_escape($sc);}function
use_schema_sql($K,$bm){$A=idf_escape($K);$i="EXEC(".q("CREATE SCHEMA $A").")";$f=schema_comment($K);if($f!="")$i="BEGIN\n$i\nEXEC sp_addextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q($K).", @value = ".q($f)."\nEND";return($bm=="DROP+CREATE"?"DROP SCHEMA IF EXISTS $A;\n":"")."IF SCHEMA_ID(".q($K).") IS NULL $i";}function
trigger_sql($Q){$H="";foreach(triggers($Q)as$A=>$fn)$H
.=create_trigger(" ON ".table($Q),trigger($A,$Q)).";";return$H;}function
convert_field(array$n){}function
unconvert_field(array$n,$H){return$H;}function
support($Vd){return
preg_match('~^(check|comment|columns|database|drop_col|dump|fast_status|indexes|descidx|procedure|routine|scheme|sql|table|transaction_ddl|trigger|view|view_trigger)$~',$Vd);}}add_driver("oracle","Oracle");if(isset($_GET["oracle"])){define('Adminer\DRIVER',"oracle");function
easy_connect(array$M){return
url_host($M["host"]).($M["port"]!=""?":$M[port]":"").$M["path"];}if(extension_loaded("oci8")&&$_GET["ext"]!="pdo"){class
Db
extends
SqlDb{var$extension="OCI8";var$error_offset=0;private$link,$transaction=false;function
_error($yd,$m){$this->error=preg_replace('~^[^:]*: ~','',php_error_text($m));}function
attach(array$M,$U,$D){$this->link=@oci_new_connect($U,$D,easy_connect($M),"AL32UTF8");if($this->link){$this->server_info=oci_server_version($this->link);return'';}$m=oci_error();return($m?$m["message"]:'Unknown error.');}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($sc){return$this->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($sc));}function
query($F,$rn=false){$G=oci_parse($this->link,$F);$this->error="";$this->error_offset=0;if(!$G){$m=oci_error($this->link);$this->errno=$m["code"];$this->error=$m["message"];return
false;}set_error_handler(array($this,'_error'));$H=@oci_execute($G,($this->transaction?OCI_NO_AUTO_COMMIT:OCI_COMMIT_ON_SUCCESS));restore_error_handler();if(!$H){$m=oci_error($G);$this->error_offset=($m?$m["offset"]:0);}if($H){if(oci_num_fields($G))return
new
Result($G);$this->affected_rows=oci_num_rows($G);oci_free_statement($G);}return$H;}function
timeout($Ah){return
function_exists('oci_set_call_timeout')&&oci_set_call_timeout($this->link,$Ah);}function
inTransaction(){return$this->transaction;}function
begin(){$this->transaction=true;return
true;}function
commit(){return$this->end_transaction(@oci_commit($this->link));}function
rollback(){return$this->end_transaction(@oci_rollback($this->link));}private
function
end_transaction($H){$this->transaction=false;if(!$H){$m=oci_error($this->link);$this->errno=$m["code"];$this->error=$m["message"];}return$H;}}class
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
select_db($sc){return$this->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($sc));}}}class
Driver
extends
SqlDriver{static$extensions=array("OCI8","PDO_OCI");static$jush="oracle";static$serverPath=true;var$insertFunctions=array("date"=>"current_date","timestamp"=>"current_timestamp",);var$editFunctions=array("number|float|double"=>"+/-","date|timestamp"=>"+ interval/- interval","char|clob"=>"||",);var$functions=array("length","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");function
operators($lm){return
array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","BETWEEN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");}static
function
connect($M,$U,$D){$g=parent::connect($M,$U,$D);if(is_object($g))$g->query("ALTER SESSION SET CURSOR_SHARING = FORCE"." NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'"." NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS.FF'"." NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS.FF TZH:TZM'");return$g;}function
__construct(Db$g){parent::__construct($g);$this->types=array('Numbers'=>array("number"=>38,"binary_float"=>12,"binary_double"=>21),'Date and time'=>array("date"=>19,"timestamp"=>29,"interval year"=>12,"interval day"=>28),'Strings'=>array("char"=>2000,"varchar2"=>4000,"nchar"=>2000,"nvarchar2"=>4000,"clob"=>4294967295,"nclob"=>4294967295),'Binary'=>array("raw"=>2000,"long raw"=>2147483648,"blob"=>4294967295,"bfile"=>4294967296),'Geometry'=>array("sdo_geometry"=>0),);}function
begin(){return$this->conn->begin();}function
convertSearch($t,array$W,array$n){$T=$n["type"];$wj=strpos($W["op"],"LIKE")!==false;if($T=="xmltype")return"XMLSERIALIZE(CONTENT $t AS VARCHAR2(4000))";if($T=="json")return"JSON_SERIALIZE($t)";if(preg_match('~^(date|timestamp)~',$T))return"TO_CHAR($t, 'YYYY-MM-DD HH24:MI:SS')";if(preg_match('~char~',$T)||(preg_match('~clob~',$T)&&$wj))return$t;return(!$wj&&preg_match(number_type(),$T)?$t:"TO_CHAR($t)");}function
quoteBinary($Rk){return"HEXTORAW(".q(bin2hex($Rk)).")";}function
typeName(\stdClass$n){return
strtolower(parent::typeName($n));}function
hasCStyleEscapes(){return
true;}function
errorLine($F){$B=(isset($this->conn->error_offset)?$this->conn->error_offset:0);return($B?substr_count($F,"\n",0,min($B,strlen($F)))+1:0);}function
tableHelp($A,$dg=false){if(DB=="SYS"){if(preg_match('~^G?V_\$(.+)~',$A,$_))return"refrn/V-$_[1].html";if(preg_match('~^(ALL|DBA|USER)_~',$A))return"refrn/$A.html";}}function
select($Q,array$L,array$Z,array$Ke,array$Hi=array(),$y=1,$C=0,$Tj=false){if(in_array("*",$L)){$bc=array();$te=false;foreach(fields($Q)as$A=>$n){$Fa=convert_field($n);$te=($te||$Fa);$bc[]=($Fa?"$Fa AS ":"").idf_escape($A);}if($te)$L=$bc;}return
parent::select($Q,$L,$Z,$Ke,$Hi,$y,$C,$Tj);}function
allFields(){$H=array();$J=get_rows('SELECT c.table_name "tab", c.column_name "field", c.data_type "type", c.nullable "nullable",
	c.data_precision "precision", c.data_scale "scale", c.char_col_decl_length "char_length"
FROM all_tab_columns c
WHERE '.where_owner("c.owner").'
ORDER BY c.table_name, c.column_id',$this->conn);foreach($J
as$I){$x="$I[precision],$I[scale]";$I["length"]=(strpos($I["type"],"(")?"":($x==","?$I["char_length"]:$x));$I["type"]=strtolower($I["type"]);$I["null"]=($I["nullable"]=="Y");$H[$I["tab"]][]=$I;}return$H;}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return
idf_escape($t);}function
get_databases($ne){$H=get_vals("SELECT username FROM all_users WHERE oracle_maintained = 'N' ORDER BY 1");return($H?:get_vals("SELECT username FROM all_users ORDER BY 1"));}function
limit($F,$Z,$y,$B=0,$il=" "){return($B?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $F$Z) t WHERE rownum <= ".($y+$B).") WHERE rnum > $B":($y?" * FROM (SELECT $F$Z) WHERE rownum <= ".($y+$B):" $F$Z"));}function
limit1($Q,$F,$Z,$il="\n"){return" $F$Z";}function
db_collation($k,array$Eb){return
get_val("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");}function
logged_user(){return
get_val("SELECT USER FROM DUAL");}function
where_owner($Xi="owner"){return"$Xi = ".q(DB);}function
views_table($e){return"(SELECT $e FROM all_views WHERE ".where_owner().")";}function
objects_table(){return"(SELECT object_name, DECODE(object_type, 'VIEW', 'view', 'table') object_type FROM all_objects WHERE ".where_owner()." AND object_type IN ('TABLE', 'VIEW'))";}function
tables_list(){return
get_key_vals("SELECT * FROM ".objects_table()." ORDER BY 1");}function
count_tables(array$j){$H=array();foreach($j
as$k)$H[$k]=get_val("SELECT COUNT(*) FROM all_objects WHERE object_type IN ('TABLE', 'VIEW') AND owner = ".q($k));return$H;}function
table_status($A="",$Ud=false){$H=array();$Xk=q($A);if($Ud||$A!=""){foreach(get_rows('SELECT object_name "Name", object_type "Engine" FROM '.objects_table().($A!=""?" WHERE object_name = $Xk":"").' ORDER BY 1')as$I)$H[$I["Name"]]=$I;return$H;}foreach(get_rows('SELECT t.table_name "Name", \'table\' "Engine", s.bytes "Data_length", i.bytes "Index_length", t.num_rows "Rows"
FROM all_tables t
LEFT JOIN (SELECT segment_name, SUM(bytes) bytes FROM user_segments WHERE segment_type LIKE \'TABLE%\' GROUP BY segment_name) s ON s.segment_name = t.table_name
LEFT JOIN (SELECT i.table_name, SUM(s.bytes) bytes FROM user_indexes i
	JOIN user_segments s ON s.segment_name = i.index_name AND s.segment_type LIKE \'INDEX%\' GROUP BY i.table_name) i ON i.table_name = t.table_name
WHERE '.where_owner("t.owner")."
UNION SELECT view_name, 'view', 0, 0, 0 FROM ".views_table("view_name")."
ORDER BY 1")as$I)$H[$I["Name"]]=$I;foreach(get_vals("SELECT table_name FROM all_indexes ai WHERE ".where_owner("table_owner")." AND (status = 'UNUSABLE'
	OR EXISTS (SELECT 1 FROM all_ind_partitions aip WHERE aip.index_owner = ai.owner AND aip.index_name = ai.index_name AND aip.status = 'UNUSABLE')
	OR EXISTS (SELECT 1 FROM all_ind_subpartitions ais WHERE ais.index_owner = ai.owner AND ais.index_name = ai.index_name AND ais.status = 'UNUSABLE'))")as$Q){if($H[$Q])$H[$Q]["invalid_index"]="1";}return$H;}function
is_view(array$R){return$R["Engine"]=="view";}function
fk_support(array$R){return
true;}function
fields($Q){$H=array();$rf=null;foreach(get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($Q)." AND ".where_owner()." ORDER BY column_id")as$I){$T=$I["DATA_TYPE"];$x="$I[DATA_PRECISION],$I[DATA_SCALE]";if($x==","){$x=($T=="RAW"?$I["DATA_LENGTH"]:(preg_match('~CHAR~',$T)?$I["CHAR_COL_DECL_LENGTH"]:""));if($I["CHAR_USED"]=="C"&&preg_match('~^(VARCHAR2|CHAR)$~',$T))$x="$I[CHAR_LENGTH] CHAR";}elseif(strpos($T,"("))$x="";$l=$I["DATA_DEFAULT"];$_c=false;if($l!==null){$l=rtrim($l);if(preg_match("~^'(?:[^']|'')*'\$~s",$l))$l=idf_unescape($l);elseif(preg_match('~^NULL$~i',$l))$l=null;else$_c=!is_numeric($l);}if($I["IDENTITY_COLUMN"]=="YES"){if($rf===null)$rf=get_key_vals("SELECT column_name, generation_type FROM all_tab_identity_cols WHERE table_name = ".q($Q)." AND ".where_owner());$l="GENERATED ".$rf[$I["COLUMN_NAME"]].($I["DEFAULT_ON_NULL"]=="YES"?" ON NULL":"")." AS IDENTITY";}$Wj=array("insert"=>1,"select"=>1,"update"=>1,"order"=>1);if($I["DATA_TYPE_OWNER"]==""||$T=="XMLTYPE")$Wj["where"]=1;$H[$I["COLUMN_NAME"]]=array("field"=>$I["COLUMN_NAME"],"full_type"=>$T.($x?"($x)":""),"type"=>strtolower($T),"length"=>$x,"default"=>$l,"default_sql"=>($_c&&$I["IDENTITY_COLUMN"]!="YES"),"null"=>($I["NULLABLE"]=="Y"),"auto_increment"=>($I["IDENTITY_COLUMN"]=="YES"),"privileges"=>$Wj,);}return$H;}function
table_constraints($Q,$h=null){$H=array();foreach(get_rows('SELECT c.constraint_name "name", c.constraint_type "type", c.r_owner "r_owner", c.r_constraint_name "r_constraint", c.delete_rule "delete_rule", cc.column_name "column"
FROM all_constraints c
JOIN all_cons_columns cc ON cc.owner = c.owner AND cc.constraint_name = c.constraint_name
WHERE c.constraint_type IN (\'P\', \'U\', \'R\') AND '.where_owner("c.owner")." AND c.table_name = ".q($Q).'
ORDER BY cc.position',$h)as$I){$A=$I["name"];$H[$A]["type"]=$I["type"];$H[$A]["r_owner"]=$I["r_owner"];$H[$A]["r_constraint"]=$I["r_constraint"];$H[$A]["delete_rule"]=$I["delete_rule"];$H[$A]["columns"][]=$I["column"];}return$H;}function
indexes($Q,$h=null){$H=array();$Vb=array();$mj=array();foreach(table_constraints($Q,$h)as$A=>$Ub)$Vb[$A]=$Ub["type"];foreach(get_rows("SELECT aic.*, atc.data_default, ai.status, ai.partitioned
FROM all_ind_columns aic
JOIN all_indexes ai ON ai.owner = aic.index_owner AND ai.index_name = aic.index_name
LEFT JOIN all_tab_cols atc ON aic.column_name = atc.column_name AND aic.table_name = atc.table_name AND aic.index_owner = atc.owner
WHERE aic.table_name = ".q($Q)." AND ".where_owner("aic.table_owner")."
ORDER BY aic.column_position",$h)as$I){$Df=$I["INDEX_NAME"];$Hb=$I["DATA_DEFAULT"];$Hb=($Hb?trim($Hb,'"'):$I["COLUMN_NAME"]);$T=idx($Vb,$Df);$H[$Df]["type"]=($T=="P"?"PRIMARY":($T=="U"?"UNIQUE":"INDEX"));$H[$Df]["invalid"]=($I["STATUS"]=="UNUSABLE");$H[$Df]["columns"][]=$Hb;$H[$Df]["lengths"][]=($I["CHAR_LENGTH"]&&$I["CHAR_LENGTH"]!=$I["COLUMN_LENGTH"]?$I["CHAR_LENGTH"]:null);$H[$Df]["descs"][]=($I["DESCEND"]&&$I["DESCEND"]=="DESC"?'1':null);if($I["PARTITIONED"]=="YES")$mj[$Df]=q($Df);}if($mj){$xf=where_owner("index_owner")." AND index_name IN (".implode(", ",$mj).") AND status = 'UNUSABLE'";foreach(get_rows("SELECT index_name FROM all_ind_partitions WHERE $xf UNION SELECT index_name FROM all_ind_subpartitions WHERE $xf",$h)as$I)$H[$I["INDEX_NAME"]]["invalid"]=true;}uasort($H,function($ha,$Pa){$Hi=array("PRIMARY"=>0,"UNIQUE"=>1,"INDEX"=>2);return$Hi[$ha["type"]]-$Hi[$Pa["type"]];});return$H;}function
view($A){$J=get_rows('SELECT text "select" FROM '.views_table("view_name, text").' WHERE view_name = '.q($A));return($J?$J[0]:array());}function
collations(){return
array();}function
information_schema($k,$K=""){return
in_array($K!=""?$K:$k,array("INFORMATION_SCHEMA","SYS","SYSTEM"));}function
error(){return
h(connection()->error);}function
explain(Db$g,$F){$g->query("EXPLAIN PLAN FOR $F");return$g->query("SELECT * FROM plan_table");}function
found_rows(array$R,array$Z){}function
auto_increment(){return"";}function
alter_table($Q,$A,array$o,array$pe,$f,$td,$Db,$La,$nj){$b=$ad=array();$Pi=($Q?fields($Q):array());foreach($o
as$n){$W=$n[1];if($W&&$n[0]!=""&&idf_escape($n[0])!=$W[0])queries("ALTER TABLE ".table($Q)." RENAME COLUMN ".idf_escape($n[0])." TO $W[0]");$Oi=$Pi[$n[0]];if($W&&$Oi){$ni=process_field($Oi,$Oi);if($W[2]==$ni[2])$W[2]="";}if($W){list($W[2],$W[3])=array($W[3],$W[2]);$b[]=($Q!=""?($n[0]!=""?"MODIFY (":"ADD ("):"  ").implode($W).($Q!=""?")":"");}else$ad[]=idf_escape($n[0]);}if($Q=="")return
queries("CREATE TABLE ".table($A)." (\n".implode(",\n",array_merge($b,$pe))."\n)");return(!$b||queries("ALTER TABLE ".table($Q)."\n".implode("\n",$b)))&&(!$ad||queries("ALTER TABLE ".table($Q)." DROP (".implode(", ",$ad).")"))&&($Q==$A||queries("ALTER TABLE ".table($Q)." RENAME TO ".table($A)));}function
alter_indexes($Q,$b){$ad=array();$ek=array();foreach($b
as$W){if($W[0]!="INDEX"){$W[2]=preg_replace('~ DESC$~','',$W[2]);$i=($W[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($W[1]):"\nADD".($W[1]!=""?" CONSTRAINT ".idf_escape($W[1]):"")." $W[0] ".($W[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$W[2]).")");array_unshift($ek,"ALTER TABLE ".table($Q).$i);}elseif($W[2]=="DROP")$ad[]=idf_escape($W[1]);else$ek[]="CREATE INDEX ".idf_escape($W[1]!=""?$W[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ",$W[2]).")";}if($ad)array_unshift($ek,"DROP INDEX ".implode(", ",$ad));foreach($ek
as$F){if(!queries($F))return
false;}return
true;}function
foreign_keys($Q){$H=array();$Fm=array();foreach(table_constraints($Q)as$A=>$Ub){if($Ub["type"]=="R"){$H[$A]=array("source"=>$Ub["columns"],"target"=>array(),"on_delete"=>$Ub["delete_rule"],"on_update"=>null,);$Fm[$A]=array($Ub["r_owner"],$Ub["r_constraint"]);}}if($Fm){$Z=array();foreach($Fm
as$Cm)$Z[]="(owner = ".q($Cm[0])." AND constraint_name = ".q($Cm[1]).")";foreach(get_rows("SELECT owner, constraint_name, table_name, column_name FROM all_cons_columns WHERE ".implode(" OR ",array_unique($Z))." ORDER BY position")as$I){foreach($Fm
as$A=>$Cm){if($Cm==array($I["OWNER"],$I["CONSTRAINT_NAME"])){$H[$A]["db"]=$I["OWNER"];$H[$A]["table"]=$I["TABLE_NAME"];$H[$A]["target"][]=$I["COLUMN_NAME"];}}}}return$H;}function
trigger($A,$Q){if($A=="")return
array();$J=get_rows('SELECT trigger_name "Trigger", trigger_type "Type", triggering_event "Event", trigger_body "Statement"
FROM all_triggers
WHERE trigger_name = '.q($A)." AND ".where_owner());$H=reset($J);if($H){$T=$H["Type"];$H["Timing"]=(preg_match('~^(BEFORE|AFTER|INSTEAD OF)~',$T,$_)?$_[1]:$T);$H["Type"]=(preg_match('~EACH ROW~',$T)||$T=="INSTEAD OF"?"FOR EACH ROW":"");}return($H?:array());}function
triggers($Q){$H=array();foreach(get_rows("SELECT trigger_name, trigger_type, triggering_event FROM all_triggers WHERE table_name = ".q($Q)." AND ".where_owner())as$I)$H[$I["TRIGGER_NAME"]]=array(preg_replace('~ (STATEMENT|EACH ROW)$~','',$I["TRIGGER_TYPE"]),$I["TRIGGERING_EVENT"]);return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE","INSERT OR UPDATE","INSERT OR DELETE","UPDATE OR DELETE","INSERT OR UPDATE OR DELETE"),"Type"=>array("FOR EACH ROW",""),);}function
truncate_tables(array$S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views(array$Zn){return
apply_queries("DROP VIEW",$Zn);}function
drop_tables(array$S){return
apply_queries("DROP TABLE",$S);}function
last_id($G){return"0";}function
create_database($k,$Db){$H=queries("CREATE USER ".idf_escape($k)." NO AUTHENTICATION");return($H?queries("GRANT UNLIMITED TABLESPACE TO ".idf_escape($k)):$H);}function
drop_databases(array$j){$H=true;foreach($j
as$k)$H=!!queries("DROP USER ".idf_escape($k)." CASCADE")&&$H;return$H;}function
rename_database($A,$Db){return!!queries("ALTER USER ".idf_escape(DB)." RENAME TO ".idf_escape($A));}function
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
convert_field(array$n){if($n["type"]=="sdo_geometry")return"SDO_UTIL.TO_WKTGEOMETRY(".idf_escape($n["field"]).")";}function
unconvert_field(array$n,$H){return($n["type"]=="sdo_geometry"?"SDO_UTIL.FROM_WKTGEOMETRY($H)":$H);}function
support($Vd){return
preg_match('~^(columns|database|drop_col|fast_status|indexes|descidx|processlist|sql|status|table|trigger|variables|view|view_trigger)$~',$Vd);}}class
Adminer{static$instance;var$error='';function
name(){return"<a href='https://www.adminer.org/'".target_blank()." id='h1'><img src='".h(preg_replace("~\\?.*~","",ME)."?file=logo.svg&version=6.2.0+584b178")."' width='24' height='24' alt='' id='logo'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($i=false){return
password_file($i);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
verifyLoginToken(){return
true;}function
serverName($M){return
h($M);}function
database(){return
DB;}function
databases($ne=true){return
get_databases($ne);}function
pluginsLinks(){}function
operators($lm=null){return
driver()->operators($lm);}function
schemas(){$H=schemas();if($_GET["ns"]!=""&&!in_array($_GET["ns"],$H))array_unshift($H,$_GET["ns"]);return$H;}function
queryTimeout(){return
2;}function
afterConnect(){}function
headers(){}function
csp(array$jc){return$jc;}function
verifyVersion(){return
true;}function
serviceWorker(){service_worker();}function
manifest(){$mf=$_SERVER["HTTP_HOST"]?:$_SERVER["SERVER_NAME"];$gl=preg_replace('~\?.*~','',ME)?:'.';return
array('name'=>"Adminer".($mf!=""?" - $mf":""),'short_name'=>'Adminer','description'=>'Database management in a single PHP file','start_url'=>$gl,'scope'=>$gl,'display'=>'minimal-ui','icons'=>array(array('src'=>preg_replace("~\\?.*~","",ME)."?file=logo.svg&version=6.2.0+584b178",'sizes'=>'any','type'=>'image/svg+xml')),);}function
head($oc=null){return
true;}function
bodyClass(){echo" adminer";}function
css(){$H=array();foreach(array("","-dark")as$wh){$p="adminer$wh.css";if(file_exists($p)){$be=file_get_contents($p);$H["$p?v=".crc32($be)]=($wh?"dark":(preg_match('~prefers-color-scheme:\s*dark~',$be)?'':'light'));}}return$H;}function
loginForm(){echo"<table class='layout'>\n",adminer()->loginFormField('driver','<tr><th>'.'System'.'<td>',html_select("auth[driver]",SqlDriver::$drivers,DRIVER,on('change','loginDriver'))),adminer()->loginFormField('server','<tr><th>'.'Server'.'<td>',"<input name='auth[server]' value='".h(SERVER)."' title='".'hostname[:port] or :socket'."' placeholder='localhost' autocapitalize='off'>"),adminer()->loginFormField('username','<tr><th>'.'Username'.'<td>','<input name="auth[username]" id="username" autofocus value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("fire(qs('#username').form['auth[driver]'], 'change');")),adminer()->loginFormField('password','<tr><th>'.'Password'.'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'),adminer()->loginFormField('db','<tr><th>'.'Database'.'<td>','<input name="auth[db]" value="'.h($_GET["db"]).'" autocapitalize="off">'),"</table>\n","<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
loginFormField($A,$df,$X){return$df.$X."\n";}function
login($Mg,$D){if($D=="")return'Adminer does not support accessing a database without a password.'.require_password_link(null);if(!Driver::$passwords)return'The database does not support passwords.'.require_password_link($D);if(!password_required())return'The server accepts any password, so filling it in protects nothing.'.require_password_link($D);return
true;}function
tableName(array$lm){return
h($lm["Name"]);}function
fieldName(array$n,$Hi=0){$T=$n["full_type"].($n["null"]?" NULL":"");$f=$n["comment"];return'<span title="'.h($T.($f!=""?($T?": ":"").$f:'')).'">'.h($n["field"]).'</span>';}function
commentValue($T,$f){if($f==""||$T=='SCHEMA'||$T=='TABLE'||$T=='COLUMN')return
h($f);$Kj=function($Rk,$ib='td'){return
preg_replace('~^~m','<tr>',preg_replace('~\|~',"<$ib>",preg_replace('~\|$~m',"",rtrim($Rk))));};$Q='(\+--[-+]+\+\n)';$I='(\| .* \|\n)';return"<pre>\n".preg_replace_callback("~^$Q?$I$Q?($I*)$Q?~m",function($_)use($Kj){return"<table>\n".($_[1]?"<thead>".$Kj($_[2],'th')."<tbody>\n":$Kj($_[2])).$Kj($_[4])."\n</table>";},preg_replace('~(\n(    -|mysql)&gt; )(.+)~',"\\1<code class='jush-sql'>\\3</code>",preg_replace('~(.+)\n---+\n~',"<b>\\1</b>\n",h($f))))."</pre>\n";}function
commentInput($T,$c,$f){$X=h($f);return(preg_match('~\n~',$X)?"<textarea$c rows='2' cols='".($T=='TABLE'?20:30)."' style='vertical-align: bottom;'>\n$X</textarea>":"<input$c value='$X'>");}function
selectLinks(array$lm,$N=""){$A=$lm["Name"];echo'<p class="links">';$Hg=array();if($A!="")$Hg["select"]='Select data';if(support("table")||support("indexes"))$Hg["table"]='Show structure';if(support("table")){if(is_view($lm)){if(support("view"))$Hg["view"]='Alter view';}elseif(function_exists('Adminer\alter_table')&&$A!="")$Hg["create"]='Alter table';}if($N!==null)$Hg["edit"]='New item';foreach($Hg
as$w=>$W)echo" <a href='".h(ME)."$w=".url_escape($A).($w=="edit"?$N:"")."'".bold(isset($_GET[$w])).">$W</a>";echo"\n";}function
foreignKeys($Q){return
foreign_keys($Q);}function
backwardKeys($Q,$km){return
array();}function
backwardKeysPrint(array$Ra,array$I){}function
selectQuery($F,$Tl,$Td=false){$H="\n";if(!$Td&&($do=driver()->warnings())){$s="warnings";$H=", <a href='#$s' class='toggle'>".'Warnings'."</a>"."$H<div id='$s' class='hidden'>\n$do</div>\n";}return"<p><code class='sql jush-".JUSH."'>".h(str_replace("\n"," ",$F))."</code> <span class='time'>(".format_time($Tl).")</span>".(support("sql")?" <a href='".h(ME)."sql=".url_escape($F)."' class='hover'>".'Edit'."</a>":"").$H;}function
sqlCommandQuery($F){return
shorten_utf8(trim($F),1000);}function
sqlPrintAfter(){}function
sqlUrlLength(){return
2000;}function
explain(Db$g,$F,array$Li){$G=explain($g,$F);if(!$G)return"";ob_start();print_select_result($G,$g,$Li);return
ob_get_clean();}function
rowDescription($Q){return"";}function
rowDescriptions(array$J,array$qe){return$J;}function
selectLink($W,array$n){}function
selectVal($W,$z,array$n,$Si){$H=($W===null?"<i>NULL</i>":(preg_match("~char|binary|boolean~",$n["type"])&&!preg_match("~var~",$n["type"])?"<code>$W</code>":(preg_match('~^jsonb?$~',$n["full_type"])?"<code class='jush-json'>$W</code>":$W)));if(is_blob($n)&&!is_utf8($W))$H="<i>".lang_format(array('%d byte','%d bytes'),strlen($Si))."</i>";return($z?"<a href='".h($z)."'".(is_url($z)?target_blank():"").">$H</a>":$H);}function
editVal($W,array$n){return$W;}function
config(){return
array();}function
tableStructurePrint(array$o,$lm=null){echo"<div class='scrollable'>\n","<table class='nowrap odds'>\n","<thead><tr><th>".'Column'."<th>".'Type'.(support("comment")?"<th>".'Comment':"")."<tbody>\n";$On=(support("type")?types():array());foreach($o
as$n){echo"<tr><th>".h($n["field"]).doc_link(array(JUSH=>$n["doc"]));$T=h($n["full_type"]);$Db=h($n["collation"]);echo"<td><span title='$Db'>".(in_array($T,$On)?"<a href='".h(ME.'type='.url_escape($T))."'>$T</a>":$T.($Db&&isset($lm["Collation"])&&$Db!=$lm["Collation"]?" $Db":""))."</span>",($n["null"]?" <i>NULL</i>":""),($n["auto_increment"]?" <i>".'Auto Increment'."</i>":""),(isset($n["default"])?" <span title='".'Default value'."'>[<b>".($n["generated"]||$n["default_sql"]?"<code class='sql jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim($n["default"])),80,"</code>"):h($n["default"]))."</b>]</span>":""),(support("comment")?"<td>".adminer()->commentValue('COLUMN',$n["comment"]):""),"\n";}echo"</table>\n","</div>\n";}function
tableIndexesPrint(array$v,array$lm){$hj=false;foreach($v
as$A=>$u)$hj|=!!$u["partial"];echo"<table>\n";$yc=first(driver()->indexAlgorithms($lm));foreach($v
as$A=>$u){ksort($u["columns"]);$Tj=array();foreach($u["columns"]as$w=>$W)$Tj[]="<i>".h($W)."</i>".($u["lengths"][$w]?"(".h($u["lengths"][$w]).")":"").($u["descs"][$w]?" DESC":"");echo"<tr title='".h($A)."'>","<th>".h($u["type"]).($yc&&$u['algorithm']!=$yc?" (".h($u['algorithm']).")":""),"<td>".implode(", ",$Tj).($u["invalid"]?" <span class='error'>INVALID</span>":"");if($hj)echo"<td>".($u['partial']?"<code class='sql jush-".JUSH."'>WHERE ".h($u['partial']):"");echo"\n";}echo"</table>\n";}function
namePattern($T){if($T=="FOREIGN"||$T=="CHECK")return"";if($T=="TRIGGER")return"{table}_{timing}{event}";return(JUSH=="sql"?"":"{table}_")."{columns}";}function
selectColumnInput($c,array$e,$X="",$_j=""){return
select_input($c,$e,$X,$_j);}function
selectColumnsPrint(array$L,array$e){print_fieldset("select",'Select',$L);$r=0;$L[""]=array();foreach($L
as$w=>$W){$W=idx($_GET["columns"],$w,array());$d=adminer()->selectColumnInput(" name='columns[$r][col]' data-default=''".on('change',($w!==""?'selectFieldChange':'selectAddRow')),$e,$W["col"]);echo"<div>".(driver()->functions||driver()->grouping?html_select("columns[$r][fun]",array(-1=>"")+array_filter(array('Functions'=>driver()->functions,'Aggregation'=>driver()->grouping)),$W["fun"]," data-default=''".on('change',($w!==""?'helpClose':'selectFunAddRow')).on_help_value(' (.*)|$','($1)'))."($d)":$d)."</div>\n";$r++;}echo"</div></fieldset>\n";}function
selectSearchPrint(array$Z,array$e,array$v,$lm=null){print_fieldset("search",'Search',$Z);foreach($v
as$r=>$u){if($u["type"]=="FULLTEXT")echo"<div>(<i>".implode("</i>, <i>",array_map('Adminer\h',$u["columns"]))."</i>) ".h(driver()->fulltextOperator)," <input type='search' name='fulltext[$r]' value='".h(idx($_GET["fulltext"],$r))."' data-default=''".on('input','selectFieldChange').">",(JUSH=='sql'?checkbox("boolean[$r]",1,isset($_GET["boolean"][$r]),"BOOL"):''),"</div>\n";}$Ai=adminer()->operators($lm);foreach(array_merge((array)$_GET["where"],array(array()))as$r=>$W){if(!$W||(("$W[col]$W[val]"!=""||preg_match('~NULL$~',$W["op"]))&&in_array($W["op"],$Ai)))echo"<div>".adminer()->selectColumnInput(" name='where[$r][col]' data-default=''".on('change',($W?'selectFieldChange':'selectAddRow')),$e,$W["col"],"(".'anywhere'.")"),html_select("where[$r][op]",$Ai,$W["op"]," data-default='".h(first($Ai))."'".on('change','selectFirstChange')),"<input type='search' name='where[$r][val]' value='".h($W["val"])."' data-default=''".on('input','selectFirstChange').on('keydown','selectSearchKeydown').on('search','selectSearchSearch').">","</div>\n";}echo"</div></fieldset>\n";}function
selectOrderPrint(array$Hi,array$e,array$v){print_fieldset("sort",'Sort',$Hi);$r=0;foreach((array)$_GET["order"]as$w=>$W){if($W!=""){echo"<div>".adminer()->selectColumnInput(" name='order[$r]' data-default=''".on('change','selectFieldChange'),$e,$W),checkbox("desc[$r]",1,isset($_GET["desc"][$w]),'descending')."</div>\n";$r++;}}echo"<div>".adminer()->selectColumnInput(" name='order[$r]' data-default=''".on('change','selectAddRow'),$e),checkbox("desc[$r]",1,false,'descending')."</div>\n","</div></fieldset>\n";}function
selectLimitPrint($y){echo"<fieldset><legend>".'Limit'."</legend><div>","<input type='number' name='limit' class='size' value='".h($y?:"")."' data-default='50'".on('input','selectFieldChange').">","</div></fieldset>\n";}function
selectLengthPrint($Jm){echo"<fieldset><legend>".'Text length'."</legend><div>","<input type='number' name='text_length' class='size' value='".h($Jm)."' data-default='100'>","</div></fieldset>\n";}function
selectActionPrint(array$v){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>"," <span id='noindex' title='".'Full table scan'."'></span>","<script".nonce().">\n","const indexColumns = ";$e=array();foreach($v
as$u){$nc=reset($u["columns"]);if($u["type"]!="FULLTEXT"&&$nc)$e[$nc]=1;}$e[""]=1;foreach($e
as$w=>$W)json_row($w);echo";\n","selectFieldChange.call(qs('#form')['select']);\n","</script>\n","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return!information_schema(DB);}function
selectEmailPrint(array$od,array$e){}function
selectColumnsProcess(array$e,array$v){$L=array();$Ke=array();foreach((array)$_GET["columns"]as$w=>$W){if($W["fun"]=="count"||($W["col"]!=""&&(!$W["fun"]||in_array($W["fun"],driver()->functions)||in_array($W["fun"],driver()->grouping)))){$L[$w]=apply_sql_function($W["fun"],($W["col"]!=""?idf_escape($W["col"]):"*"));if(!in_array($W["fun"],driver()->grouping))$Ke[]=$L[$w];}}return
array($L,$Ke);}function
selectSearchProcess(array$o,array$v,$lm=null){$H=array();foreach($v
as$r=>$u){if($u["type"]=="FULLTEXT"&&idx($_GET["fulltext"],$r)!="")$H[]=driver()->fulltextSql($r,$u,$_GET["fulltext"][$r],isset($_GET["boolean"][$r]));}$Ai=adminer()->operators($lm);foreach((array)$_GET["where"]as$w=>$W){$W+=array("col"=>"","op"=>first($Ai),"val"=>"");$_GET["where"][$w]=$W;$Bb=$W["col"];if(("$Bb$W[val]"!=""||preg_match('~NULL$~',$W["op"]))&&in_array($W["op"],$Ai)){if($W["op"]=="SQL"&&(!$_POST||!verify_token()))SqlDb::$untrusted=true;$Ob=array();foreach(($Bb!=""?array($Bb=>idx($o,$Bb,array())):$o)as$A=>$n){if($Bb!=""||is_searchable($n,$W))$Ob[]=adminer()->selectSearchCondition(driver()->convertSearch(idf_escape($A),$W,$n),$W["op"],$W["val"]);}$H[]=(count($Ob)==1?$Ob[0]:($Ob?"(".implode(" OR ",$Ob).")":"1 = 0"));}}return$H;}function
selectSearchCondition($d,$xi,$W){if(preg_match('~IN$~',$xi))return"$d $xi ".($W!=""?process_in($W):"(NULL)");if($xi=="BETWEEN")return"$d $xi ".process_between($W);if($xi=="SQL")return"$d $W";if(preg_match('~^(I?LIKE) %%$~',$xi,$_))return"$d $_[1] ".q("%$W%");if($xi=="FIND_IN_SET")return"$xi(".q($W).", $d)";return"$d $xi".(!preg_match('~NULL$~',$xi)?" ".q($W):"");}function
selectOrderProcess(array$o,array$v){$H=array();foreach((array)$_GET["order"]as$w=>$W){if($W!="")$H[]=(preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~',$W)?$W:idf_escape($W)).(isset($_GET["desc"][$w])?" DESC".(JUSH=='pgsql'&&idx($o[$W],"null")?" NULLS LAST":""):"");}return$H;}function
selectLimitProcess(){return(isset($_GET["limit"])?intval($_GET["limit"]):50);}function
selectLengthProcess(){return(isset($_GET["text_length"])?"$_GET[text_length]":"100");}function
selectEmailProcess(array$Z,array$qe){return
false;}function
selectQueryBuild(array$L,array$Z,array$Ke,array$Hi,$y,$C){return"";}function
messageQuery($F,$Lm,$Td=false){restart_session();$jf=&get_session("queries");if(!idx($jf,$_GET["db"]))$jf[$_GET["db"]]=array();if(strlen($F)>1e6)$F=preg_replace('~[\x80-\xFF]+$~','',substr($F,0,1e6))."\n…";$jf[$_GET["db"]][]=array($F,time(),$Lm);$Ol="sql-".count($jf[$_GET["db"]]);$H="<a href='#$Ol' class='toggle'>".'SQL command'."</a> ".copy_icon()."\n";if(!$Td&&($do=driver()->warnings())){$s="warnings-".count($jf[$_GET["db"]]);$H="<a href='#$s' class='toggle'>".'Warnings'."</a>, $H<div id='$s' class='hidden'>\n$do</div>\n";}return" <span class='time'>".@date("H:i:s")."</span>"." $H<div id='$Ol' class='hidden'><pre><code class='jush-".JUSH."'>".shorten_utf8($F,1e4)."</code></pre>".($Lm?" <span class='time'>($Lm)</span>":'').(support("sql")?'<p><a href="'.h(str_replace("db=".url_escape(DB),"db=".url_escape($_GET["db"]),ME).'sql=&history='.(count($jf[$_GET["db"]])-1)).'">'.'Edit'.'</a>':'').'</div>';}function
error(){return
error();}function
editRowPrint($Q,array$o,$I,$Bn,$F='',$Lm=''){echo($F!=""?"<p><code class='sql jush-".JUSH."'>".h(str_replace("\n"," ",$F))."</code> <span class='time'>($Lm)</span>\n":"");}function
editFunctions(array$n){$H=($n["null"]?"NULL/":"");$Ze=isset($_GET["select"])||where($_GET);foreach(array(driver()->insertFunctions,driver()->editFunctions)as$w=>$Ee){if(!$w||(!isset($_GET["call"])&&$Ze)){foreach($Ee
as$wj=>$W){if(!$wj||preg_match("~$wj~",$n["type"]))$H
.="/$W";}}if($w&&$Ee&&!preg_match('~set|bool~',$n["type"])&&!is_blob($n))$H
.="/SQL";}if($n["auto_increment"]&&!$Ze)$H='Auto Increment';return
explode("/",$H);}function
editInput($Q,array$n,$c,$X){if($n["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$c value='orig' checked><i>".'original'."</i></label> ":"").enum_input("radio",$c,$n,$X,"NULL");return"";}function
editHint($Q,array$n,$X){return"";}function
processInput(array$n,$X,$De=""){if($De=="SQL")return$X;$A=$n["field"];$H=q($X);if(preg_match('~^(now|getdate|uuid)$~',$De))$H="$De()";elseif(preg_match('~^current_(date|timestamp)$~',$De))$H=$De;elseif(preg_match('~^([+-]|\|\|)$~',$De))$H=idf_escape($A)." $De $H";elseif(preg_match('~^[+-] interval$~',$De))$H=idf_escape($A)." $De ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i",$X)&&JUSH!="pgsql"?$X:$H);elseif(preg_match('~^(addtime|subtime|concat)$~',$De))$H="$De(".idf_escape($A).", $H)";elseif(preg_match('~^(md5|sha1|password|encrypt)$~',$De))$H="$De($H)";return
unconvert_field($n,$H);}function
dumpOutput(){$H=array('text'=>'open','file'=>'save');if(function_exists('gzencode'))$H['gz']='gzip';return$H;}function
dumpFormat(){return(support("dump")?array('sql'=>'SQL'):array())+array('csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpPrint(){}function
dumpDatabase($k){}function
dumpTable($Q,$bm,$dg=0){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($bm)dump_csv(array_keys(fields($Q)));}else{if($dg==2){$o=array();foreach(fields($Q)as$A=>$n)$o[]=idf_escape($A)." ".full_type_sql($n);$i="CREATE TABLE ".table($Q)." (".implode(", ",$o).")";}else$i=create_sql($Q,$_POST["auto_increment"],$bm);set_utf8mb4($i);if($bm&&$i){if(($bm=="DROP+CREATE"&&!function_exists('Adminer\drop_sql'))||$dg==1)echo"DROP ".($dg==2?"VIEW":"TABLE")." IF EXISTS ".table($Q).";\n";if($dg==1)$i=remove_definer($i);echo"$i;\n\n";}}}function
dumpData($Q,$bm,$F,array$L=array(),array$Z=array(),array$Ke=array(),array$Hi=array()){if($bm){$Xg=(JUSH=="sqlite"?0:1048576);$o=array();$sf=false;if($_POST["format"]=="sql"){if($bm=="TRUNCATE+INSERT"&&!function_exists('Adminer\truncate_all_sql'))echo
truncate_sql($Q).";\n";$o=fields($Q);if(JUSH=="mssql"){foreach($o
as$n){if($n["auto_increment"]){echo"SET IDENTITY_INSERT ".table($Q)." ON;\n";$sf=true;break;}}}}$G=($F!=""?connection()->query($F,1):driver()->select($Q,($L?:array("*")),$Z,$Ke,$Hi,0));if($G){$Of="";$cb="";$lg=array();$Fe=array();$dm="";$Wd=($Q!=''?'fetch_assoc':'fetch_row');$fc=0;while($I=$G->$Wd()){if(!$lg){$Y=array();foreach($I
as$W){$n=$G->fetch_field();if(idx($o[$n->name],'generated')){$Fe[$n->name]=true;continue;}$lg[]=$n->name;$w=idf_escape($n->name);$Y[]="$w = VALUES($w)";}$dm=($bm=="INSERT+UPDATE"?"\nON DUPLICATE KEY UPDATE ".implode(", ",$Y):"").";\n";}foreach($I
as$w=>$W){if(is_array($W))$I[$w]=json_encode($W,256|64);}if($_POST["format"]!="sql"){if($bm=="table"){dump_csv($lg);$bm="INSERT";}dump_csv($I);}else{if(!$Of)$Of="INSERT INTO ".table($Q)." (".implode(", ",array_map('Adminer\idf_escape',$lg)).") VALUES";foreach($I
as$w=>$W){if($Fe[$w]){unset($I[$w]);continue;}$n=$o[$w];$I[$w]=($W===null?"NULL":($W===false?0:unconvert_field($n,preg_match(number_type(),$n["type"])&&!preg_match('~\[~',$n["full_type"])&&is_numeric($W)?$W:(!is_blob($n)||is_utf8($W)?q($W):driver()->quoteBinary($W)))));}$Rk=($Xg?"\n":" ")."(".implode(",\t",$I).")";if(!$cb)$cb=$Of.$Rk;elseif(JUSH=='mssql'?$fc%1000!=0:strlen($cb)+4+strlen($Rk)+strlen($dm)<$Xg)$cb
.=",$Rk";else{echo$cb.$dm;$cb=$Of.$Rk;}}$fc++;}if($cb)echo$cb.$dm;}elseif($_POST["format"]=="sql")echo"-- ".str_replace("\n"," ",connection()->error)."\n";else
dump_csv(array(connection()->error));if($sf)echo"SET IDENTITY_INSERT ".table($Q)." OFF;\n";}}function
dumpFilename($qf){return
friendly_url($qf!=""?$qf:(SERVER?:"localhost"));}function
dumpHeaders($qf,$Ch=false){$Wi=$_POST["output"];$Od=(preg_match('~sql~',$_POST["format"])?"sql":($Ch?"tar":"csv"));header("Content-Type: ".($Wi=="gz"?"application/x-gzip":($Od=="tar"?"application/x-tar":($Od=="sql"||$Wi!="file"?"text/plain":"text/csv")."; charset=utf-8")));if($Wi=="gz"){ob_start(function($P){return
gzencode($P);},1e6);}return$Od;}function
dumpFooter(){if($_POST["format"]=="sql")echo"-- ".gmdate("Y-m-d H:i:s e")."\n";}function
importServerPath(){return"adminer.sql";}function
importPrint(){}function
importProcess(){return
false;}function
homepage(){echo'<p class="links">'.($_GET["ns"]==""&&support("database")?'<a href="'.h(ME).'database=">'.'Alter database'."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?'Alter schema':'Create schema')."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.'Database schema'."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".'Privileges'."</a>\n":"");if($_GET["ns"]!=="")echo(support("routine")?"<a href='#routines'>".'Routines'."</a>\n":""),(support("sequence")?"<a href='#sequences'>".'Sequences'."</a>\n":""),(support("type")?"<a href='#user-types'>".'User types'."</a>\n":""),(support("event")?"<a href='#events'>".'Events'."</a>\n":"");return
true;}function
navigation($vh){echo"<h1>".adminer()->name()." <span class='version'>".VERSION;$Wh=$_COOKIE["adminer_version"];echo" <a href='https://www.adminer.org/#download'".target_blank()." id='version'>".(version_compare(VERSION,$Wh)<0?h($Wh):"").version_iframe()."</a>","</span></h1>\n";if($vh=="auth"){$Wi="";foreach((array)$_SESSION["pwds"]as$Wn=>$vl){foreach($vl
as$M=>$Pn){$A=h(get_setting("vendor-$Wn-$M")?:get_driver($Wn));foreach($Pn
as$U=>$D){if($A&&$D!==null){$wc=$_SESSION["db"][$Wn][$M][$U];foreach(($wc?array_keys($wc):array(""))as$k)$Wi
.="<li><a href='".h(auth_url($Wn,$M,$U,$k))."'>($A) ".h("$U@").($M!=""?adminer()->serverName($M):"").h($k!=""?" - $k":"")."</a>\n";}}}}if($Wi)echo"<ul id='logins'".on('mouseover','menuOver').on('mouseout','menuOut').">\n$Wi</ul>\n";}else{$S=array();if($_GET["ns"]!==""&&!$vh&&DB!=""){connection()->select_db(DB);$S=table_status('',true);}adminer()->syntaxHighlighting($S);adminer()->databasesPrint($vh);$ja=array();if(DB==""||!$vh){if(support("sql")){$ja['sql']="<a href='".h(ME)."sql='".bold(isset($_GET["sql"])&&!isset($_GET["import"])).">".'SQL command'."</a>";$ja['import']="<a href='".h(ME)."import='".bold(isset($_GET["import"])).">".'Import'."</a>";}$ja['dump']="<a href='".h(ME)."dump=".url_escape(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".'Export'."</a>";}$yf=$_GET["ns"]!==""&&!$vh&&DB!="";if($yf&&function_exists('Adminer\alter_table'))$ja['create']='<a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".'Create table'."</a>";$ja=adminer()->menuActions($ja,$vh);echo($ja?"<p class='links'>\n".implode("\n",$ja)."\n":"");if($yf){if($S)adminer()->tablesPrint($S);else
echo"<p class='message'>".'No tables.'."</p>\n";}}}function
syntaxHighlighting(array$S){echo
script_src(preg_replace("~\\?.*~","",ME)."?file=jush.js&version=6.2.0+584b178",true);$yh=preg_replace('~<(?=/script)~i','<\\',Driver::jushModule());echo($yh?script("addEventListener('DOMContentLoaded', () => {\n$yh\n});"):"");if(support("sql")){echo"<script".nonce().">\n";if($S){$Hg=array();foreach($S
as$Q=>$T)$Hg[]=js_escape_re($Q);echo"var jushLinks = { ".JUSH.":";json_row(js_escape(ME).(support("table")?"table":"select").'=$&','/\b(?<!\$)('.implode('|',$Hg).')(?!\$)\b/g',false);$Ql=array("sql","check","event","procedure","trigger","view","type","table","processlist");if(support("routine")&&array_intersect_key($_GET,array_flip($Ql))){foreach(routines()as$I)json_row(js_escape(ME).'function='.url_escape($I["SPECIFIC_NAME"]).'&name=$&','/\b'.js_escape_re($I["ROUTINE_NAME"]).'(?=["`\]]?\()/g',false);}json_row('');echo"};\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$W)echo"jushLinks.$W = jushLinks.".JUSH.";\n";if(array_intersect_key($_GET,array_flip(array("sql","check","event","procedure","trigger","view")))){$Wl=(isset($_GET["trigger"])?array('INSERT INTO','UPDATE','DELETE FROM'):(isset($_GET["check"])?array():(isset($_GET["view"])?array('SELECT'):null)));$Na=Driver::jushAutocomplete($S,$Wl);echo($Na?"addEventListener('DOMContentLoaded', () => { autocompleter = $Na; });\n":"");}}echo"</script>\n";}echo
script("syntaxHighlighting('".doc_version()."', '".connection()->flavor."');");}function
databasesPrint($vh){if(support("single_db"))return;$j=adminer()->databases();if(DB&&$j&&!in_array(DB,$j))array_unshift($j,DB);echo"<form action=''>\n<p id='dbs'>\n";hidden_fields_get();$tc=on('mousedown','dbMouseDown').on('change','dbChange');echo"<label title='".'Database'."'>".'DB'.": ".($j?html_select("db",array(""=>"")+group_system($j),DB,$tc):"<input name='db' value='".h(DB)."' autocapitalize='off' size='19'>\n")."</label>","<input type='submit' value='".'Use'."'".($j?" class='hidden'":"").">\n";if(support("scheme")){if($vh!="db"&&DB!=""&&connection()->select_db(DB)){echo"<br><label>".'Schema'.": ".html_select("ns",array(""=>"")+group_system(adminer()->schemas(),true),$_GET["ns"],$tc)."</label>";if($_GET["ns"]!="")set_schema($_GET["ns"]);}}foreach(array("import","sql","schema","dump","privileges")as$W){if(isset($_GET[$W])){echo
input_hidden($W);break;}}echo"</p></form>\n";}function
menuActions(array$ja,$vh){return$ja;}function
tablesPrint(array$S){echo"<ul id='tables'".on('mouseover','menuOver').on('mouseout','menuOut').">";foreach($S
as$Q=>$O){$Q="$Q";$A=adminer()->tableName($O);if($A!=""&&!$O["dependent"])echo'<li><a href="'.h(ME).'select='.url_escape($Q).'"'.bold($_GET["select"]==$Q||$_GET["edit"]==$Q,"select hover")." title='".'Select data'."'>".'select'."</a> ",(support("table")||support("indexes")?'<a href="'.h(ME).'table='.url_escape($Q).'"'.bold(in_array($Q,array($_GET["table"],$_GET["create"],$_GET["indexes"],$_GET["foreign"],$_GET["trigger"],$_GET["check"],$_GET["view"])),(is_view($O)?"view":"structure"))." title='".'Show structure'."'>$A</a>":"<span>$A</span>")."\n";}echo"</ul>\n";}function
showVariables(){return
show_variables();}function
showStatus(){return
show_status();}function
processList(){return
process_list();}function
killProcess($s){return
kill_process($s);}function
formatSizeValue($W){return
format_number($W);}}class
Plugins{private
static$append=array('dumpFormat'=>true,'dumpOutput'=>true,'editRowPrint'=>true,'editFunctions'=>true,'config'=>true);var$plugins;var$disabled=array();var$drivers=array();var$driverFiles=array();var$error='';private$hooks=array();function
__construct($Dj){$Zc=SqlDriver::$drivers;$ff=" href='https://www.adminer.org/plugins/#use'".target_blank();if($Dj===null){$Dj=array();$Va="adminer-plugins";if(is_dir($Va)){foreach(glob("$Va/*.php")as$p){$ce=SqlDriver::$drivers;$this->includeOnce($p);foreach(array_diff_key(SqlDriver::$drivers,$ce)as$s=>$A)$this->driverFiles[$s]=$p;}}if(file_exists("$Va.php")){$_f=$this->includeOnce("$Va.php");if(is_array($_f)){foreach($_f
as$w=>$Aj)$Dj[is_object($Aj)?get_class($Aj):$w]=$Aj;}else$this->error
.=sprintf('%s must <a%s>return an array</a>.',"<b>$Va.php</b>",$ff)."<br>";}foreach(get_declared_classes()as$yb){if(!$Dj[$yb]&&(preg_match('~^Adminer\w~i',$yb)||is_subclass_of($yb,'Adminer\Plugin'))){$rk=new
\ReflectionClass($yb);$Wb=$rk->getConstructor();if($Wb&&$Wb->getNumberOfRequiredParameters())$this->error
.=sprintf('<a%s>Configure</a> %s in %s.',$ff,"<b>$yb</b>","<b>$Va.php</b>")."<br>";else$Dj[$yb]=new$yb;}}}$Tf=array_filter($Dj,function($Aj){return!is_object($Aj);});if($Tf){$this->error
.=sprintf('Every plugin must <a%s>be an object</a>.',$ff)."<br>";$Dj=array_diff_key($Dj,$Tf);}$hm=get_setting("plugins","adminer_config");foreach($Dj
as$w=>$Aj){if($Aj
instanceof
Plugin&&$Aj::$optional){$Ul=idx($hm,get_class($Aj));if($Ul!==null?!$Ul:!$Aj->enabled){$this->disabled[]=$Aj;unset($Dj[$w]);}}}$this->drivers=array_diff_key(SqlDriver::$drivers,$Zc);$this->plugins=$Dj;$oa=new
Adminer;$Dj[]=$oa;$rk=new
\ReflectionObject($oa);foreach($rk->getMethods()as$sh){foreach($Dj
as$Aj){$A=$sh->getName();if(method_exists($Aj,$A))$this->hooks[$A][]=$Aj;}}}function
includeOnce($p){return
include_once"./$p";}static
function
describe($Aj){$Hc=(method_exists($Aj,'description')?$Aj->description():"");if(!$Hc){$rk=new
\ReflectionObject($Aj);if(preg_match('~^/[\s*]+(.+?)(?:\s*\*/)?$~m',$rk->getDocComment(),$_))$Hc=$_[1];}$Wk=(method_exists($Aj,'screenshot')?$Aj->screenshot():"");return
h($Hc).($Wk?($Hc?" ":"")."(<a href='".h($Wk)."'".target_blank().">".'screenshot'."</a>)":"");}static
function
checksum($p){$be=str_replace("\r","",file_get_contents($p));$be=preg_replace('~\n\tprotected \$translations = array\(.*?\n\t\);~s','',$be);return
dechex(crc32($be));}function
checksums(){$de=array_values($this->driverFiles);foreach($this->plugins
as$Aj){$rk=new
\ReflectionObject($Aj);$de[]=$rk->getFileName();}$H=array();foreach($de
as$p)$H[basename($p,'.php')]=self::checksum($p);return$H;}static
function
officialChecksums(){return
array('adminer.js'=>'a0599090','backward-keys'=>'1c017d59','before-unload'=>'9674d220','config'=>'bdac2201','dark-switcher'=>'8c201319','database-hide'=>'e304a899','designs'=>'5903cccc','dump-alter'=>'896b579e','dump-bz2'=>'c9229da6','dump-date'=>'512aad63','dump-json'=>'f8aa6b99','dump-xml'=>'aedd2d3a','dump-zip'=>'735d7e2','edit-foreign'=>'d04a0ac8','edit-textarea'=>'a178d7d9','editor-setup'=>'a7dc3a37','editor-views'=>'5c12b185','enum-option'=>'e9005cb0','file-upload'=>'10add0e8','foreign-system'=>'634ce119','frames'=>'b0e1d11a','highlight-codemirror'=>'ada4b569','highlight-monaco'=>'ec1da606','highlight-prism'=>'19933b19','import-csv'=>'bcecfad2','login-ip'=>'4d174fea','login-otp'=>'66bd013d','login-passkey'=>'89783b50','login-password-less'=>'e150daac','login-reverse-proxy'=>'24558ea2','login-servers'=>'19c42e45','login-ssl'=>'2b06a5b7','login-table'=>'811f8cef','menu-links'=>'55b9b15c','name-patterns'=>'84c10d09','remote-color'=>'267580a3','row-numbers'=>'1ad1b6cf','select-email'=>'c33a2661','select-expression'=>'b6a4b3f1','select-foreign'=>'577c07b2','select-image'=>'39ed1607','slugify'=>'7b422f0e','sql-gemini'=>'c60ab309','sql-log'=>'8e435000','sql-url-length'=>'cd70f1a0','table-indexes-structure'=>'e0b1433','table-structure'=>'f71ec61b','tables-filter'=>'2729bf7','timeout'=>'97321caf','version-github'=>'627cadf9','version-noverify'=>'966937e9','clickhouse'=>'51a3d7d5','elastic'=>'9b83a12a','firebird'=>'5f3d9493','igdb'=>'3b00190b','imap'=>'3ba195cd','mongo'=>'235e3ba','redis'=>'41709f61','simpledb'=>'b8e2cc7d',);}function
__call($A,array$dj){$Da=array();foreach($dj
as$w=>$W)$Da[]=&$dj[$w];$H=null;foreach($this->hooks[$A]as$Aj){$X=call_user_func_array(array($Aj,$A),$Da);if($X!==null){if(!self::$append[$A])return$X;$H=$X+(array)$H;}}return$H;}}abstract
class
Plugin{static$optional=false;var$enabled=true;protected$translations=array();function
disable(){$this->enabled=false;return$this;}function
description(){return$this->lang('');}function
screenshot(){return"";}protected
function
lang($t,$ei=null){$Da=func_get_args();$Da[0]=idx($this->translations[LANG],$t)?:$t;return
call_user_func_array('Adminer\lang_format',$Da);}}class
Password{private$password_hash;private$password_matches=null;function
__construct($sj){$this->password_hash=$sj;}function
description(){return'Require a password verified by Adminer';}function
credentials(){$D=get_password();return
array(SERVER,$_GET["username"],($this->passwordMatches($D)&&!password_required()?"":$D));}function
login($Mg,$D){if($this->passwordMatches($D))return
true;if(!Driver::$passwords||!password_required())return
false;}protected
function
passwordMatches($D){if($this->password_matches===null)$this->password_matches=(function_exists('password_verify')&&password_verify(strval($D),$this->password_hash));return$this->password_matches;}}Adminer::$instance=(function_exists('adminer_object')?adminer_object():(is_dir("adminer-plugins")||file_exists("adminer-plugins.php")?new
Plugins(null):new
Adminer));SqlDriver::$drivers=array("server"=>"MySQL / MariaDB")+SqlDriver::$drivers;if(!defined('Adminer\DRIVER')){define('Adminer\DRIVER',"server");if(extension_loaded("mysqli")&&$_GET["ext"]!="pdo"){class
Db
extends
\mysqli{static$instance;var$extension="MySQLi",$flavor='';function
__construct(){parent::init();}function
attach(array$M,$U,$D){mysqli_report(MYSQLI_REPORT_OFF);$Ej=$M["port"];$qd=("$M[host]$Ej$M[socket]"=="");$Sl=adminer()->connectSsl();$Ln=($Sl&&($Sl['key']||$Sl['cert']||$Sl['ca']||isset($Sl['verify'])));if($Ln)$this->ssl_set($Sl['key'],$Sl['cert'],$Sl['ca'],'','');$H=@$this->real_connect((!$qd?$M["host"]:ini_get("mysqli.default_host")),(!$qd||$U!=""?$U:ini_get("mysqli.default_user")),(!$qd||$U.$D!=""?$D:ini_get("mysqli.default_pw")),null,($Ej!=""?intval($Ej):ini_get("mysqli.default_port")),($Ej!=""?null:$M["socket"]),($Ln?($Sl['verify']!==false?MYSQLI_CLIENT_SSL:64):0));$this->options(MYSQLI_OPT_LOCAL_INFILE,0);return($H?'':$this->error);}function
set_charset($nb){if(parent::set_charset($nb))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $nb");}function
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
sprintf('Disable %s or enable the %s or %s extension.',"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");$Ej="$M[port]$M[socket]";$A=$M["host"].($Ej!=""?":$Ej":"");$this->link=@mysql_connect(($A!=""?$A:ini_get("mysql.default_host")),($A.$U!=""?$U:ini_get("mysql.default_user")),($A.$U.$D!=""?$D:ini_get("mysql.default_password")),true,131072);if(!$this->link)return
mysql_error();$this->server_info=mysql_get_server_info($this->link);return'';}function
set_charset($nb){return
mysql_set_charset($nb,$this->link)||mysql_set_charset('utf8',$this->link);}function
quote($P){return"'".mysql_real_escape_string($P,$this->link)."'";}function
select_db($sc){return
mysql_select_db($sc,$this->link);}function
query($F,$rn=false){$G=@($rn?mysql_unbuffered_query($F,$this->link):mysql_query($F,$this->link));$this->error="";if(!$G){$this->errno=mysql_errno($this->link);$this->error=mysql_error($this->link);return
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
attach(array$M,$U,$D){$Ei=array(\PDO::MYSQL_ATTR_LOCAL_INFILE=>false);if(isset($_GET["select"]))$Ei[\PDO::MYSQL_ATTR_MULTI_STATEMENTS]=false;$Sl=adminer()->connectSsl();if($Sl){if($Sl['key'])$Ei[\PDO::MYSQL_ATTR_SSL_KEY]=$Sl['key'];if($Sl['cert'])$Ei[\PDO::MYSQL_ATTR_SSL_CERT]=$Sl['cert'];if($Sl['ca'])$Ei[\PDO::MYSQL_ATTR_SSL_CA]=$Sl['ca'];if(isset($Sl['verify']))$Ei[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT]=$Sl['verify'];}$mf=$M["host"];$Ej=$M["port"];$El=$M["socket"];return$this->dsn("mysql:charset=utf8".($mf!=""?";host=$mf":'').($Ej!=""?";port=$Ej":($El!=""?";unix_socket=$El":"")),$U,$D,$Ei);}function
set_charset($nb){return$this->query("SET NAMES $nb");}function
select_db($sc){return$this->query("USE ".idf_escape($sc));}function
query($F,$rn=false){$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,!$rn);return
parent::query($F,$rn);}}}class
Driver
extends
SqlDriver{static$extensions=array("MySQLi","MySQL","PDO_MySQL");static$jush="sql";static$serverSocket=true;var$unsigned=array("unsigned","zerofill","unsigned zerofill");var$functions=array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");var$partitionBy=array("HASH","LINEAR HASH","KEY","LINEAR KEY","RANGE","LIST");function
operators($lm){return
array("=","<",">","<=",">=","!=","&","LIKE","LIKE %%","REGEXP","IN","BETWEEN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");}static
function
connect($M,$U,$D){$g=parent::connect($M,$U,$D);if(is_string($g)){if(function_exists('iconv')&&!is_utf8($g)&&strlen($Rk=iconv("windows-1252","utf-8//IGNORE",$g))>strlen($g))$g=$Rk;return$g;}$g->set_charset(charset($g));$g->query("SET sql_quote_show_create = 1, autocommit = 1");$g->flavor=(preg_match('~MariaDB~',$g->server_info)?'maria':'mysql');add_driver(DRIVER,($g->flavor=='maria'?"MariaDB":"MySQL"));return$g;}function
__construct(Db$g){parent::__construct($g);$this->types=array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Geometry'=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),);$this->insertFunctions=array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",);$this->editFunctions=array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",);if(min_version('5.7.8',10.2,$g))$this->types['Strings']["json"]=4294967295;if(min_version('',10.7,$g)){$this->types['Strings']["uuid"]=128;$this->insertFunctions['uuid']='uuid';}if(min_version('',10.5,$g)){$this->types['Network']["inet6"]=39;if(min_version('','10.10',$g))$this->types['Network']["inet4"]=15;}if(min_version(9,11.7,$g))$this->types['Numbers']["vector"]=16383;if(min_version(5.7,10.2,$g))$this->generated=array("STORED","VIRTUAL");}function
unconvertFunction(array$n){return(preg_match("~binary~",$n["type"])?"<code class='jush-sql'>UNHEX</code>":($n["type"]=="bit"?doc_link(array('sql'=>'bit-value-literals.html'),"<code>b''</code>"):($n["type"]=="vector"?"<code class='jush-sql'>".($this->conn->flavor=='maria'?"VEC_FromText":"STRING_TO_VECTOR")."</code>":(preg_match("~geom|point|linestring|polygon~",$n["type"])?"<code class='jush-sql'>GeomFromText</code>":""))));}function
insert($Q,array$N){return($N?parent::insert($Q,$N):queries("INSERT INTO ".table($Q)." ()\nVALUES ()"));}function
insertUpdate($Q,array$J,array$Rj){$e=array_keys(reset($J));$Mj="INSERT INTO ".table($Q)." (".implode(", ",$e).") VALUES\n";$Y=array();foreach($e
as$w)$Y[$w]="$w = VALUES($w)";$dm="\nON DUPLICATE KEY UPDATE ".implode(", ",$Y);$Y=array();$x=0;foreach($J
as$N){$X="(".implode(", ",$N).")";if($Y&&(strlen($Mj)+$x+strlen($X)+strlen($dm)>1e6)){if(!queries($Mj.implode(",\n",$Y).$dm))return
false;$Y=array();$x=0;}$Y[]=$X;$x+=strlen($X)+2;}return
queries($Mj.implode(",\n",$Y).$dm);}function
slowQuery($F,$Mm){if(min_version('5.7.8','10.1.2')){if($this->conn->flavor=='maria')return"SET STATEMENT max_statement_time=$Mm FOR $F";elseif(preg_match('~^(SELECT\b)(.+)~is',$F,$_))return"$_[1] /*+ MAX_EXECUTION_TIME(".($Mm*1000).") */ $_[2]";}}function
convertColumn($t,array$n){if(preg_match("~binary~",$n["type"]))return"HEX($t)";if($n["type"]=="bit")return"BIN($t + 0)";if($n["type"]=="vector")return($this->conn->flavor=='maria'?"VEC_ToText":"VECTOR_TO_STRING")."($t)";if(preg_match("~geom|point|linestring|polygon~",$n["type"]))return(min_version(8)?"ST_":"")."AsWKT($t)";return"";}function
convertSearch($t,array$W,array$n){return($this->convertColumn($t,$n)?:(preg_match('~'.text_type().'~',$n["type"])&&!preg_match("~^utf8~",$n["collation"])&&preg_match('~[\x80-\xFF]~',$W['val'])?"CONVERT($t USING ".charset($this->conn).")":$t));}function
typeName(\stdClass$n){$A=parent::typeName($n);if($A!=""){$qn=array("TINY"=>"tinyint","SHORT"=>"smallint","LONG"=>"int","INT24"=>"mediumint","LONGLONG"=>"bigint","NEWDECIMAL"=>"decimal","VAR_STRING"=>"varchar","STRING"=>"char",);return
idx($qn,$A,strtolower($A));}$qn=array("decimal","tinyint","smallint","int","float","double",7=>"timestamp","bigint","mediumint","date","time","datetime","year",15=>"varchar","bit",242=>"vector",245=>"json","decimal","enum","set","tinytext","mediumtext","longtext","text","varchar","char","geometry",);$H=idx($qn,$n->type,"");return($n->charsetnr==63?str_replace(array("text","varchar","char"),array("blob","varbinary","binary"),$H):$H);}function
quoteBinary($Rk){return"X".q(bin2hex($Rk));}function
md5($d,array$n){if(is_blob($n)||preg_match('~'.text_type().'~',$n["type"]))return"MD5(".(is_blob($n)||preg_match("~^utf8~",$n["collation"])?$d:"CONVERT($d USING ".charset($this->conn).")").")";}function
warnings(){$G=$this->conn->query("SHOW WARNINGS");if($G&&$G->num_rows){ob_start();print_select_result($G);return
ob_get_clean();}}function
errorLine($F){return(preg_match('~ at line (\d+)$~',$this->conn->error,$_)?(int)$_[1]:0);}function
tableHelp($A,$dg=false){$Og=($this->conn->flavor=='maria');if(information_schema(DB))return
strtolower(str_replace("_","-",DB)."-".($Og?"$A-table/":str_replace("_","-",$A)."-table.html"));if(DB=="sys")return($Og?"sys-schema/":strtolower("sys-".str_replace("_","-",preg_replace('~^x\$~','',$A)).".html"));if(DB=="mysql")return($Og?"mysql$A-table/":"system-schema.html");}function
partitionsInfo($Q){$ye="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($Q);$G=$this->conn->query("SELECT PARTITION_METHOD, PARTITION_EXPRESSION, PARTITION_ORDINAL_POSITION $ye ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");$I=($G?$G->fetch_row():null);if(!$I)return
array();$H=array();list($H["partition_by"],$H["partition"],$H["partitions"])=$I;$oj=get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $ye AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");$H["partition_names"]=array_keys($oj);$H["partition_values"]=array_values($oj);return$H;}function
checkConstraints($Q){$H=parent::checkConstraints($Q);return($this->conn->flavor=='maria'?$H:array_map('stripslashes',$H));}function
hasCStyleEscapes(){static$fb;if($fb===null){$Pl=get_val("SHOW VARIABLES LIKE 'sql_mode'",1,$this->conn);$fb=(strpos($Pl,'NO_BACKSLASH_ESCAPES')===false);}return$fb;}function
hasEstimatedRows(){return
true;}function
isSystem($k,$K=""){return
information_schema($k,$K)||in_array($k,array("mysql","sys"));}function
lineComment(){return"#|-- ";}function
engines(){$H=array();foreach(get_rows("SHOW ENGINES")as$I){if(preg_match("~YES|DEFAULT~",$I["Support"]))$H[]=$I["Engine"];}return$H;}function
indexAlgorithms(array$lm){return(preg_match('~^(MEMORY|NDB)$~',$lm["Engine"])?array("HASH","BTREE"):array());}}function
idf_escape($t){return"`".str_replace("`","``",$t)."`";}function
table($t){return
idf_escape($t);}function
get_databases($ne){$H=get_session("dbs");if($H===null){$F="SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME";$Tl=microtime(true);$H=($ne?slow_query($F):get_vals($F));if(microtime(true)-$Tl>0.1){restart_session();set_session("dbs",$H);stop_session();}}return$H;}function
limit($F,$Z,$y,$B=0,$il=" "){return" $F$Z".($y?$il."LIMIT $y".($B?" OFFSET $B":""):"");}function
limit1($Q,$F,$Z,$il="\n"){return
limit($F,$Z,1,0,$il);}function
db_collation($k,array$Eb){$H=null;$i=get_val("SHOW CREATE DATABASE ".idf_escape($k),1);if(preg_match('~ COLLATE ([^ ]+)~',$i,$_))$H=$_[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$i,$_))$H=$Eb[$_[1]][-1];return$H;}function
logged_user(){return
get_val("SELECT CURRENT_USER()");}function
tables_list(){return
get_key_vals("SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME");}function
count_tables(array$j){$H=array();foreach($j
as$k)$H[$k]=count(get_vals("SHOW TABLES IN ".idf_escape($k)));return$H;}function
table_status($A="",$Ud=false){$H=array();$F="SELECT ENGINE AS Engine, TABLE_NAME AS Name, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($A!=""?"AND TABLE_NAME = ".q($A):"ORDER BY Name");$K=array();foreach(($Ud?array():get_rows($F))as$I)$K[$I["Name"]]=$I;$Qj=null;foreach(get_rows($Ud?$F:"SHOW TABLE STATUS".($A!=""?" LIKE ".q(addcslashes($A,"%_\\")):""))as$I){$Si=idx($K,$I["Name"]);if($Si){if($I["Comment"]!==$Si["Comment"]&&$I["Comment"]!==$Qj)$I["Error"]=$I["Comment"];$Qj=$I["Comment"];$I["Comment"]=$Si["Comment"];$I["Engine"]=$Si["Engine"];}if($I["Engine"]=="InnoDB")$I["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$I["Comment"]);if(!isset($I["Engine"]))$I["Comment"]="";if($A!="")$I["Name"]=$A;$H[$I["Name"]]=$I;}return$H;}function
is_view(array$R){return$R["Engine"]===null;}function
fk_support(array$R){return
preg_match('~InnoDB|IBMDB2I'.(min_version(5.6)?'|NDB':'').'~i',$R["Engine"]);}function
parse_type($Ae){preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$Ae,$_);return
array($_[1],$_[2],ltrim($_[3].$_[4]));}function
fields($Q){$Og=(connection()->flavor=='maria');$H=array();foreach(get_rows("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ".q($Q)." ORDER BY ORDINAL_POSITION")as$I){$n=$I["COLUMN_NAME"];$T=$I["COLUMN_TYPE"];$Ge=$I["GENERATION_EXPRESSION"];$Rd=$I["EXTRA"];preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$Rd,$Fe);list($pn,$x,$_n)=parse_type($T);$l=$I["COLUMN_DEFAULT"];$_c=false;if($l!=""){$ik=$Og;if($Og)$_c=!preg_match("~^('(?:[^'\\\\]|\\\\.|'')*'|NULL|[-+]?[\\d.]+)$~s",$l);elseif(preg_match('~text|json~',$pn)||preg_match('~\bDEFAULT_GENERATED\b~',$Rd)){$l=stripslashes($l);$ik=preg_match("~^(_\\w+)?('(?:[^'\\\\]|\\\\.|'')*')$~s",$l,$_);if($ik)$l=$_[2];else$_c=true;}elseif(preg_match("~^[bx]'[\\da-f]*'$~i",$l))$_c=true;if($ik)$l=($l=="NULL"?null:(preg_match("~^'.*'$~s",$l)?stripslashes(idf_unescape($l)):$l));if(preg_match('~binary~',$pn)&&preg_match("~^(?:0x(\\w*)|x'(\\w*)')$~",$l,$_)){$l=pack("H*",$_[1].idx($_,2));$_c=false;}}$H[$n]=array("field"=>$n,"full_type"=>$T,"type"=>$pn,"length"=>$x,"unsigned"=>$_n,"default"=>($Fe?($Og?$Ge:stripslashes($Ge)):$l),"default_sql"=>(!$Fe&&$_c),"null"=>($I["IS_NULLABLE"]=="YES"),"auto_increment"=>($Rd=="auto_increment"),"on_update"=>(preg_match('~\bon update current_timestamp\b~i',$Rd)?"CURRENT_TIMESTAMP":""),"collation"=>$I["COLLATION_NAME"],"privileges"=>array_flip(explode(",","$I[PRIVILEGES],where,order")),"comment"=>$I["COLUMN_COMMENT"],"primary"=>($I["COLUMN_KEY"]=="PRI"),"generated"=>($Fe[1]=="PERSISTENT"?"STORED":$Fe[1]),);}return$H;}function
indexes($Q,$h=null){$H=array();foreach(get_rows("SHOW INDEX FROM ".table($Q),$h)as$I){$A=$I["Key_name"];$H[$A]["type"]=($A=="PRIMARY"?"PRIMARY":($I["Index_type"]=="FULLTEXT"?"FULLTEXT":($I["Non_unique"]?(preg_match('~^(SPATIAL|VECTOR)$~',$I["Index_type"])?$I["Index_type"]:"INDEX"):"UNIQUE")));$H[$A]["columns"][]=$I["Column_name"];$H[$A]["lengths"][]=($I["Index_type"]=="SPATIAL"?null:$I["Sub_part"]);$H[$A]["descs"][]=null;$H[$A]["algorithm"]=$I["Index_type"];}return$H;}function
foreign_keys($Q){static$wj='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$H=array();$gc=get_val("SHOW CREATE TABLE ".table($Q),1);if($gc){preg_match_all("~CONSTRAINT ($wj) FOREIGN KEY ?\\(((?:$wj,? ?)+)\\) REFERENCES ($wj)(?:\\.($wj))? \\(((?:$wj,? ?)+)\\)(?: ON DELETE (".driver()->onActions."))?(?: ON UPDATE (".driver()->onActions."))?~",$gc,$Rg,PREG_SET_ORDER);foreach($Rg
as$_){preg_match_all("~$wj~",$_[2],$Il);preg_match_all("~$wj~",$_[5],$Cm);$H[idf_unescape($_[1])]=array("db"=>idf_unescape($_[4]!=""?$_[3]:$_[4]),"table"=>idf_unescape($_[4]!=""?$_[4]:$_[3]),"source"=>array_map('Adminer\idf_unescape',$Il[0]),"target"=>array_map('Adminer\idf_unescape',$Cm[0]),"on_delete"=>($_[6]?:"RESTRICT"),"on_update"=>($_[7]?:"RESTRICT"),);}}return$H;}function
view($A){return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',get_val("SHOW CREATE VIEW ".table($A),1)));}function
collations(){$H=array();foreach(get_rows("SHOW COLLATION")as$I){if($I["Default"])$H[$I["Charset"]][-1]=$I["Collation"];else$H[$I["Charset"]][]=$I["Collation"];}ksort($H);foreach($H
as$w=>$W)sort($H[$w]);return$H;}function
information_schema($k,$K=""){return($k=="information_schema")||(min_version(5.5)&&$k=="performance_schema");}function
error(){return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",connection()->error));}function
create_database($k,$Db){return
queries("CREATE DATABASE ".idf_escape($k).($Db?" COLLATE ".q($Db):""));}function
drop_databases(array$j){$H=apply_queries("DROP DATABASE",$j,'Adminer\idf_escape');restart_session();set_session("dbs",null);return$H;}function
rename_database($A,$Db){$H=false;if(create_database($A,$Db)){$S=array();$Zn=array();foreach(tables_list()as$Q=>$T){if($T=='VIEW')$Zn[]=$Q;else$S[]=$Q;}$H=(!$S&&!$Zn)||move_tables($S,$Zn,$A);drop_databases($H?array(DB):array());}return$H;}function
auto_increment(){$Ma=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$u){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$u["columns"],true)){$Ma="";break;}if($u["type"]=="PRIMARY")$Ma=" UNIQUE";}}return" AUTO_INCREMENT$Ma";}function
alter_table($Q,$A,array$o,array$pe,$f,$td,$Db,$La,$nj){$b=array();foreach($o
as$n){if($n[1]){$l=$n[1][3];if(preg_match('~ GENERATED~',$l)){$n[1][3]=(connection()->flavor=='maria'?"":$n[1][2]);$n[1][2]=$l;}$b[]=($Q!=""?($n[0]!=""?"CHANGE ".idf_escape($n[0]):"ADD"):" ")." ".implode($n[1]).($Q!=""?$n[2]:"");}else$b[]="DROP ".idf_escape($n[0]);}$b=array_merge($b,$pe);$O=($f!==null?" COMMENT=".q($f):"").($td?" ENGINE=".q($td):"").($Db?" COLLATE ".q($Db):"").($La!=""?" AUTO_INCREMENT=$La":"");if($nj){$oj=array();if($nj["partition_by"]=='RANGE'||$nj["partition_by"]=='LIST'){foreach($nj["partition_names"]as$w=>$W){$X=$nj["partition_values"][$w];$oj[]="\n  PARTITION ".idf_escape($W)." VALUES ".($nj["partition_by"]=='RANGE'?"LESS THAN":"IN").($X!=""?" ($X)":" MAXVALUE");}}$O
.="\nPARTITION BY $nj[partition_by]($nj[partition])";if($oj)$O
.=" (".implode(",",$oj)."\n)";elseif($nj["partitions"])$O
.=" PARTITIONS ".(+$nj["partitions"]);}elseif($nj===null)$O
.="\nREMOVE PARTITIONING";if($Q=="")return
queries("CREATE TABLE ".table($A)." (\n".implode(",\n",$b)."\n)$O");if($Q!=$A)$b[]="RENAME TO ".table($A);if($O)$b[]=ltrim($O);return($b?queries("ALTER TABLE ".table($Q)."\n".implode(",\n",$b)):true);}function
alter_indexes($Q,$b){$lb=array();foreach($b
as$W)$lb[]=($W[2]=="DROP"?"\nDROP INDEX ".idf_escape($W[1]):"\nADD $W[0] ".($W[0]=="PRIMARY"?"KEY ":"").($W[1]!=""?idf_escape($W[1])." ":"")."(".implode(", ",$W[2]).")");return
queries("ALTER TABLE ".table($Q).implode(",",$lb));}function
truncate_tables(array$S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views(array$Zn){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$Zn)));}function
drop_tables(array$S){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$S)));}function
move_tables(array$S,array$Zn,$Cm){$xk=array();foreach($S
as$Q)$xk[]=table($Q)." TO ".idf_escape($Cm).".".table($Q);if(!$xk||queries("RENAME TABLE ".implode(", ",$xk))){$Dc=array();foreach($Zn
as$Q)$Dc[table($Q)]=view($Q);connection()->select_db($Cm);$k=idf_escape(DB);foreach($Dc
as$A=>$Yn){if(!queries("CREATE VIEW $A AS ".str_replace(" $k."," ",$Yn["select"]))||!queries("DROP VIEW $k.$A"))return
false;}return
true;}return
false;}function
copy_tables(array$S,array$Zn,$Cm){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($S
as$Q){$A=($Cm==DB?table("copy_$Q"):idf_escape($Cm).".".table($Q));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $A"))||!queries("CREATE TABLE $A LIKE ".table($Q))||!queries("INSERT INTO $A SELECT * FROM ".table($Q)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I){$fn=$I["Trigger"];list($Dd,$hi)=trigger_event($I);if(!queries("CREATE TRIGGER ".($Cm==DB?idf_escape("copy_$fn"):idf_escape($Cm).".".idf_escape($fn))." $I[Timing] $Dd".($hi!=""?" $hi":"")." ON $A FOR EACH ROW\n$I[Statement];"))return
false;}}foreach($Zn
as$Q){$A=($Cm==DB?table("copy_$Q"):idf_escape($Cm).".".table($Q));$Yn=view($Q);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $A"))||!queries("CREATE VIEW $A AS $Yn[select]"))return
false;}return
true;}function
trigger_event(array$I){$Fd=explode(",",$I["Event"]);$H=array();foreach(array("DELETE","INSERT","UPDATE")as$Dd){if(in_array($Dd,$Fd))$H[]=$Dd;}$H=implode(" OR ",$H);if(in_array("UPDATE",$Fd)&&min_version('','12.0.1')&&preg_match('~\s(?:BEFORE|AFTER)\s+(.+?)\s+ON\s~is',get_val("SHOW CREATE TRIGGER ".idf_escape($I["Trigger"]),2),$_)&&preg_match('~\bOF\s+(.+)~is',$_[1],$hi))return
array("$H OF",$hi[1]);return
array($H,"");}function
trigger($A,$Q){if($A=="")return
array();$J=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($A));$H=reset($J);if($H)list($H["Event"],$H["Of"])=trigger_event($H);return($H?:array());}function
triggers($Q){$H=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I){list($Dd)=trigger_event($I);$H[$I["Trigger"]]=array($I["Timing"],$Dd);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>(min_version('','12.0.1')?array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF",):array("INSERT","UPDATE","DELETE")),"Type"=>array("FOR EACH ROW"),);}function
routine($A,$T){$J=get_rows("SELECT PARAMETER_NAME, DTD_IDENTIFIER, PARAMETER_MODE, COLLATION_NAME
FROM information_schema.PARAMETERS
WHERE SPECIFIC_SCHEMA = DATABASE() AND ROUTINE_TYPE = '$T' AND SPECIFIC_NAME = ".q($A)."
ORDER BY ORDINAL_POSITION");$o=array();foreach($J
as$I){$Ae=$I["DTD_IDENTIFIER"];list($pn,$x,$_n)=parse_type($Ae);$o[]=array("field"=>$I["PARAMETER_NAME"],"type"=>$pn,"length"=>$x,"unsigned"=>$_n,"null"=>true,"full_type"=>$Ae,"inout"=>($T=="FUNCTION"?"":$I["PARAMETER_MODE"]),"collation"=>$I["COLLATION_NAME"],);}$H=connection()->query("SELECT
	ROUTINE_COMMENT comment,
	ROUTINE_DEFINITION definition,
	LOWER(EXTERNAL_LANGUAGE) language,
	IF(DEFINER = CURRENT_USER(), '', DEFINER) definer,
	IF(IS_DETERMINISTIC = 'YES', 'DETERMINISTIC', 'NOT DETERMINISTIC') is_deterministic,
	SQL_DATA_ACCESS data_access,
	CONCAT('SQL SECURITY ', SECURITY_TYPE) security
FROM information_schema.ROUTINES
WHERE ROUTINE_SCHEMA = DATABASE() AND ROUTINE_TYPE = '$T' AND ROUTINE_NAME = ".q($A))->fetch_assoc();if(!$H)return
array();$H['options']=array("DEFINER"=>$H['definer'],"DETERMINISTIC"=>$H['is_deterministic'],"SQL_DATA_ACCESS"=>$H['data_access'],"SQL_SECURITY"=>$H['security'],"COMMENT"=>$H['comment'],);if($o&&$o[0]['field']=='')$H['returns']=array_shift($o);$H['fields']=$o;return$H;}function
routines(){return
get_rows("SELECT SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE()");}function
routine_languages(){return(min_version(9,99)?array("sql"=>"sql","javascript"=>"js"):array());}function
routine_options($Ik){return
array("DEFINER"=>array(),"DETERMINISTIC"=>array("NOT DETERMINISTIC","DETERMINISTIC"),"SQL_DATA_ACCESS"=>array("CONTAINS SQL","NO SQL","READS SQL DATA","MODIFIES SQL DATA"),"SQL_SECURITY"=>array("SQL SECURITY DEFINER","SQL SECURITY INVOKER"),"COMMENT"=>array(),);}function
routine_id($A,array$I){return
idf_escape($A);}function
last_id($G){return
get_val("SELECT LAST_INSERT_ID()");}function
explain(Db$g,$F){return$g->query("EXPLAIN ".(min_version(5.7)?"":"PARTITIONS ").$F);}function
found_rows(array$R,array$Z){return($Z||$R["Engine"]!="InnoDB"?null:$R["Rows"]);}function
create_sql($Q,$La,$bm){$H=get_val("SHOW CREATE TABLE ".table($Q),1);if(!$La)$H=preg_replace('~(\n\)[^\n]*?) AUTO_INCREMENT=\d+~','\1',$H);return$H;}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
use_sql($sc,$bm=""){$A=idf_escape($sc);$H="";if(preg_match('~CREATE~',$bm)&&($i=get_val("SHOW CREATE DATABASE $A",1))){set_utf8mb4($i);if($bm=="DROP+CREATE")$H="DROP DATABASE IF EXISTS $A;\n";$H
.="$i;\n";}return$H."USE $A";}function
trigger_sql($Q){$H="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")),null,"-- ")as$I){list($I["Event"],$I["Of"])=trigger_event($I);$H
.="\n".create_trigger(" ON ".table($I["Table"]),$I+array("Type"=>"FOR EACH ROW")).";\n";}return$H;}function
show_variables(){return
get_rows("SHOW VARIABLES");}function
show_status(){return
get_rows("SHOW STATUS");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
convert_field(array$n){return
driver()->convertColumn(idf_escape($n["field"]),$n);}function
unconvert_field(array$n,$H){if(preg_match("~binary~",$n["type"]))$H="UNHEX($H)";if($n["type"]=="bit")$H="CONVERT(b$H, UNSIGNED)";if($n["type"]=="vector")$H=(connection()->flavor=='maria'?"VEC_FromText":"STRING_TO_VECTOR")."($H)";if(preg_match("~geom|point|linestring|polygon~",$n["type"])){$Mj=(min_version(8)?"ST_":"");$H=$Mj."GeomFromText($H, $Mj"."SRID($n[field]))";}return$H;}function
support($Vd){return
preg_match('~^(comment|columns|copy|database|drop_col|dump|event|indexes|kill|privileges|move_col|procedure|processlist|routine|sql|status|table|trigger|variables|view'.(min_version(8)?'|descidx':'').(min_version('8.0.16','10.2.1')?'|check':'').(min_version(8,99)?'|fast_status':'').')$~',$Vd);}function
kill_process($s){return
queries("KILL ".number($s));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){return
get_val("SELECT @@max_connections");}function
types($Qd=false){return
array();}function
type_values($s){return"";}function
type_definition($s){return
array("kind"=>"","definition"=>"");}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($K,$h=null){return
true;}function
schema_comment($K){return"";}function
set_schema_comment($K,$f){return
true;}}define('Adminer\JUSH',Driver::$jush);define('Adminer\SERVER',"".$_GET[DRIVER]);define('Adminer\DB',"$_GET[db]");define('Adminer\ME',preg_replace('~\?.*~','',relative_uri()).'?'.(sid()?SID.'&':'').($_GET["ext"]?"ext=".url_escape($_GET["ext"]).'&':'').(isset($_GET[DRIVER])?DRIVER."=".url_escape(SERVER).'&':'').(isset($_GET["username"])?"username=".url_escape($_GET["username"]).'&':'').(isset($_GET["db"])?'db='.url_escape(DB).'&'.(isset($_GET["ns"])?"ns=".url_escape($_GET["ns"])."&":""):''));if(isset($_GET["manifest"])){header("Content-Type: application/manifest+json; charset=utf-8");header("Cache-Control: no-cache");echo
json_encode(adminer()->manifest(),64|256);exit;}function
page_header($Om,$m="",$bb=array(),$Pm="",$ai=false,$Vc=""){if($ai){header("HTTP/1.1 404 Not Found");$m=($m?:'Not found.');}page_headers();if(is_ajax()&&$m){page_messages($m);exit;}if(!ob_get_level())ob_start('ob_gzhandler',4096);$Qm=$Om.($Pm!=""?": $Pm":"");$Rm=strip_tags($Qm.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".adminer()->name());echo'<!DOCTYPE html>
<html lang=\'en\' dir=\'ltr\' class=\'ltr nojs\'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>',$Rm,'</title>
<link rel="stylesheet" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=6.2.0+584b178"),'">
';$kc=adminer()->css();if(is_int(key($kc)))$kc=array_fill_keys($kc,'light');$We=in_array('light',$kc)||in_array('',$kc);$Ue=in_array('dark',$kc)||in_array('',$kc);$oc=($We?($Ue?null:false):($Ue?:null));$gh=" media='(prefers-color-scheme: dark)'";if($oc!==false)echo"<link rel='stylesheet'".($oc?"":$gh)." href='".h(preg_replace("~\\?.*~","",ME)."?file=dark.css&version=6.2.0+584b178")."'>\n";echo"<meta name='color-scheme' content='".($oc===null?"light dark":($oc?"dark":"light"))."'>\n",script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=6.2.0+584b178");if(adminer()->head($oc))echo"<link rel='icon' href='data:image/gif;base64,"."R0lGODlhEAAQAJEAAAQCBPz+/PwCBAROZCH5BAEAAAAALAAAAAAQABAAAAI2hI+pGO1rmghihiUdvUBnZ3XBQA7f05mOak1RWXrNq5nQWHMKvuoJ37BhVEEfYxQzHjWQ5qIAADs='>\n","<link rel='apple-touch-icon' href='".h(preg_replace("~\\?.*~","",ME)."?file=logo.svg&version=6.2.0+584b178")."'>\n";if(adminer()->manifest())echo"<link rel='manifest' href='".h(preg_replace('~\?.*~','',ME)."?manifest=")."' crossorigin='use-credentials'>\n";foreach($kc
as$Gn=>$wh){$c=($wh=='dark'&&!$oc?$gh:($wh=='light'&&$Ue?" media='(prefers-color-scheme: light)'":""));echo"<link rel='stylesheet'$c href='".h($Gn)."'>\n";}echo"\n<body class='";adminer()->bodyClass();echo"'>\n",script((isset($_COOKIE["adminer_version"])||!adminer()->verifyVersion()?"":"onload = partial(verifyVersion, '".VERSION."');\n")."
const offlineMessage = '".js_escape('You are offline.')."';
const numberFormat = '".js_escape('#,##0')."';
const numberDigits = '".js_escape('0123456789')."';
const urlSeparators = '".js_escape(ini_get("arg_separator.input"))."';"),"<div id='help' class='jush-".JUSH." jsonly hidden'".on('mouseover','helpKeep').on('mouseout','helpMouseout')."></div>\n","<div id='content'>\n","<span id='menuopen' class='jsonly'".on('click','menuToggle')."><button title='".'Menu'."' class='icon icon-move' aria-expanded='false'></button></span>\n";if($bb!==null){$z=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($z?:".").'">'.get_driver(DRIVER).'</a> » ';$z=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$M=adminer()->serverName(SERVER);$M=($M!=""?$M:'Server');if($bb===false)echo"$M\n";else{echo"<a href='".h($z.(DB!=""&&support("single_db")?"&db=":""))."' accesskey='1' title='Alt+Shift+1'>$M</a> » ";$Zk="";if(is_string($bb)){$Zk=$bb;$bb=array();}if($_GET["ns"]!=""||(DB!=""&&is_array($bb))){$uc="$z&db=".url_escape(DB).(support("scheme")?"&ns=":"").(support("single_table")?"&select=":"");echo'<a href="'.h($uc.($_GET["ns"]==""?$Zk:"")).'">'.h(DB).'</a> » ';}if(is_array($bb)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1).$Zk).'">'.h($_GET["ns"]).'</a> » ';foreach($bb
as$w=>$W){$Gc=(is_array($W)?$W[1]:h($W));if($Gc!="")echo"<a href='".h(ME."$w=").url_escape(is_array($W)?$W[0]:$W)."'>$Gc</a> » ";}}echo"$Om\n";}}echo"<h2>$Qm$Vc</h2>\n","<div id='ajaxstatus' role='status' class='jsonly'></div>\n";restart_session();page_messages($m);adminer()->serviceWorker();$j=&get_session("dbs");if(DB!=""&&$j&&!in_array(DB,$j,true))$j=null;stop_session();define('Adminer\PAGE_HEADER',1);ob_flush();flush();if($ai){page_footer($ai===true?"":$ai);exit;}}function
service_worker(){$uk=has_passwords();$Ab=($uk?"navigator.serviceWorker.register('".js_escape(preg_replace('~\?.*~','',ME)."?file=worker.js&version=6.2.0+584b178")."', {scope: location.pathname}).catch(() => {});":"navigator.serviceWorker.getRegistration().then(registration => registration && registration.unregister());
	caches.keys().then(keys => keys.forEach(key => key.startsWith('adminer-') && caches.delete(key)));");echo
script("if (navigator.serviceWorker) {\n\t$Ab\n}");}function
has_passwords(){foreach((array)$_SESSION["pwds"]as$vl){foreach($vl
as$Pn){foreach($Pn
as$D){if($D!==null)return
true;}}}return
false;}function
page_headers(){header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach(adminer()->csp(csp())as$jc){$bf=array();foreach($jc
as$w=>$W)$bf[]="$w $W";header("Content-Security-Policy: ".implode("; ",$bf));}adminer()->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self' https://www.adminer.org","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
design_checksums(){$Mn=array();foreach(array_keys(adminer()->css())as$Gn)$Mn[preg_replace('~\?.*~','',$Gn)]=true;$H=array();foreach(array("adminer.css","adminer-dark.css")as$p){if($Mn[$p]&&file_exists($p)){preg_match('~^/\* Adminer design ([-\w]+) \*/~',file_get_contents($p),$_);$H[$p]=array((string)$_[1],Plugins::checksum($p));}}return$H;}function
official_design_checksums(){return
array('adminer-dark/adminer-dark.css'=>'a26bcd7b','brade/adminer.css'=>'be4161f0','bueltge/adminer.css'=>'1a8f00b4','cpanel/adminer.css'=>'59ce604e','dracula/adminer-dark.css'=>'cfaf61dd','esterka/adminer.css'=>'1f805f36','flat/adminer.css'=>'49a61af9','galkaev/adminer-dark.css'=>'16c46f94','haeckel/adminer.css'=>'147a3565','hever/adminer.css'=>'c9d1d11c','konya/adminer.css'=>'2b409696','lavender-light/adminer.css'=>'bf03f5d7','lucas-sandery/adminer.css'=>'6596353','mancave/adminer-dark.css'=>'d9169f12','mvt/adminer.css'=>'ebd3afdc','nette/adminer.css'=>'1b8ac749','ng9/adminer.css'=>'488583cf','nicu/adminer.css'=>'a38b3b6a','pappu687/adminer.css'=>'b58d128c','paranoiq/adminer.css'=>'64d27e5','pepa-linha/adminer.css'=>'baf25f0','pokorny/adminer.css'=>'ee9eea6d','price/adminer.css'=>'81be9a85','rmsoft/adminer.css'=>'6cd4a237','rmsoft_blue-dark/adminer.css'=>'32102a8','rmsoft_blue/adminer.css'=>'7d8d5b18','win98/adminer.css'=>'e82d63c3',);}function
version_iframe(){return(isset($_COOKIE["adminer_version"])||!adminer()->verifyVersion()?"":"<noscript><iframe sandbox src='https://www.adminer.org/version/?current=".VERSION."&amp;noscript=1'></iframe></noscript>");}function
get_nonce(){static$Zh;if(!$Zh)$Zh=base64_encode(rand_string());return$Zh;}function
page_messages($m){$Fn=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$nh=idx($_SESSION["messages"],$Fn);if($nh){echo"<div class='message'>".implode("</div>\n<div class='message'>",$nh)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$Fn]);}if($m)echo"<div class='error'>$m</div>\n";if(adminer()->error)echo"<div class='error'>".adminer()->error."</div>\n";}function
page_footer($vh=""){echo"</div>\n\n<div id='foot' class='foot'>\n<div id='menu'>\n";adminer()->navigation($vh);echo"</div>\n";if($vh!="auth")echo'<form action="" method="post">
<p class="logout">
<span title="Username">',h($_GET["username"])."\n",'</span>
<input type=\'submit\' name=\'logout\' value=\'Logout\' id=\'logout\'>
',input_token(),'</form>
';echo"</div>\n\n",script("setupSubmitHighlight(document);");}function
int32($Eh){while($Eh>=2147483648)$Eh-=4294967296;while($Eh<=-2147483649)$Eh+=4294967296;return(int)$Eh;}function
long2str(array$V,$bo){$Rk='';foreach($V
as$W)$Rk
.=pack('V',$W);if($bo)return
substr($Rk,0,end($V));return$Rk;}function
str2long($Rk,$bo){$V=array_values(unpack('V*',str_pad($Rk,4*ceil(strlen($Rk)/4),"\0")));if($bo)$V[]=strlen($Rk);return$V;}function
xxtea_mx($no,$mo,$em,$ig){return
int32((($no>>5&0x7FFFFFF)^$mo<<2)+(($mo>>3&0x1FFFFFFF)^$no<<4))^int32(($em^$mo)+($ig^$no));}function
encrypt_string($Yl,$w){if($Yl=="")return"";$w=array_values(unpack("V*",pack("H*",md5($w))));$V=str2long($Yl,true);$Eh=count($V)-1;$no=$V[$Eh];$mo=$V[0];$ck=floor(6+52/($Eh+1));$em=0;while($ck-->0){$em=int32($em+0x9E3779B9);$gd=$em>>2&3;for($Yi=0;$Yi<$Eh;$Yi++){$mo=$V[$Yi+1];$Dh=xxtea_mx($no,$mo,$em,$w[$Yi&3^$gd]);$no=int32($V[$Yi]+$Dh);$V[$Yi]=$no;}$mo=$V[0];$Dh=xxtea_mx($no,$mo,$em,$w[$Yi&3^$gd]);$no=int32($V[$Eh]+$Dh);$V[$Eh]=$no;}return
long2str($V,false);}function
decrypt_string($Yl,$w){if($Yl=="")return"";if(!$w)return
false;$w=array_values(unpack("V*",pack("H*",md5($w))));$V=str2long($Yl,false);$Eh=count($V)-1;$no=$V[$Eh];$mo=$V[0];$ck=floor(6+52/($Eh+1));$em=int32($ck*0x9E3779B9);while($em){$gd=$em>>2&3;for($Yi=$Eh;$Yi>0;$Yi--){$no=$V[$Yi-1];$Dh=xxtea_mx($no,$mo,$em,$w[$Yi&3^$gd]);$mo=int32($V[$Yi]-$Dh);$V[$Yi]=$mo;}$no=$V[$Eh];$Dh=xxtea_mx($no,$mo,$em,$w[$Yi&3^$gd]);$mo=int32($V[0]-$Dh);$V[0]=$mo;$em=int32($em-0x9E3779B9);}return
long2str($V,true);}$zj=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$W){list($w)=explode(":",$W);$zj[$w]=$W;}}function
add_invalid_login(){$Ta=get_temp_dir()."/adminer-invalid";foreach(glob("$Ta*")?:array($Ta)as$p){$ve=file_open_lock($p);if($ve)break;}if(!$ve)$ve=file_open_lock("$Ta-".rand_string());if(!$ve)return;$Vf=json_decode(stream_get_contents($ve),true);$Lm=time();if($Vf){foreach($Vf
as$Wf=>$W){if($W[0]<$Lm)unset($Vf[$Wf]);}}$Tf=&$Vf[adminer()->bruteForceKey()];if(!$Tf)$Tf=array($Lm+30*60,0);$Tf[1]++;file_write_unlock($ve,json_encode($Vf));}function
check_invalid_login(array&$zj){$Vf=array();foreach(glob(get_temp_dir()."/adminer-invalid*")as$p){$ve=file_open_lock($p);if($ve){$Vf=json_decode(stream_get_contents($ve),true);file_unlock($ve);break;}}$w=adminer()->bruteForceKey();$Tf=idx($Vf,$w,array());$Yh=($Tf[1]>29?$Tf[0]-time():0);if($Yh>0){$m=lang_format(array('Too many unsuccessful logins, try again in %d minute.','Too many unsuccessful logins, try again in %d minutes.'),ceil($Yh/60));if($_SERVER["HTTP_X_FORWARDED_FOR"]!=""&&$w==$_SERVER["REMOTE_ADDR"])$m
.='<br>'.sprintf('Use the %s <a%s>plugin</a> if Adminer runs behind a reverse proxy.','<b>login-reverse-proxy</b>'," href='https://www.adminer.org/plugins/?version=".VERSION."'".target_blank());auth_error($m,$zj,false);}}function
password_required(){static$H;if($H===null){$H=(bool)get_session("password_required");if(!$H){$ic=adminer()->credentials();$H=!is_object(Driver::connect($ic[0],$ic[1],""));if($H)set_session("password_required",true);}}return$H;}function
require_password_link($D){$_h="<a href='https://www.adminer.org/password/'".target_blank().">".'More options'."</a>";if(!function_exists('password_hash'))return" $_h";$Bj=($D!==null?$D:base64_encode(substr(pack("H*",rand_string()),0,12)));$af=password_hash($Bj,PASSWORD_DEFAULT);$p="adminer-plugins.php";$Kd=file_exists("adminer-plugins.php");if($Kd)$Rf=($D!==null?sprintf('Add this line to %s to require the entered password:',"<b>$p</b>"):sprintf('Add this line to %s to require the password %s:',"<b>$p</b>","<b>$Bj</b>"));else{$p="<button name='password_less' value='".h($af)."' class='link'>$p</button>";$Rf=($D!==null?sprintf('Save %s next to Adminer to require the entered password:',$p):sprintf('Save %s next to Adminer to require the password %s:',$p,"<b>$Bj</b>"));}$Fg="\t<a>new</a> Adminer\\Password(<span class='jush-apo'>'".h($af)."'</span>),";$H="<p>$Rf
<pre><code class='jush'>".($Kd?$Fg:"&lt;?php\n<a>return</a> <a>array</a>(\n$Fg\n);")."</code></pre>
<p>$_h
";return" <a href='#password-less' class='toggle'>".'Require a password.'."</a>
<div id='password-less' class='hidden'>".($Kd?$H:"<form action='' method='post'>\n".$H.input_token()."</form>")."</div>";}if(preg_match('~^[-\w$./]+$~',$_POST["password_less"])&&verify_token()){header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=adminer-plugins.php");echo"<?php\nreturn array(\n\tnew Adminer\\Password('$_POST[password_less]'),\n);\n";exit;}$Ka=$_POST["auth"];if($Ka&&(!adminer()->verifyLoginToken()||verify_token())){session_regenerate_id();$Wn=$Ka["driver"];$M=$Ka["server"];$U=$Ka["username"];$D=(string)$Ka["password"];$k=$Ka["db"];set_password($Wn,$M,$U,$D);$_SESSION["db"][$Wn][$M][$U][$k]=true;if($Ka["permanent"]){$w=implode("-",array_map('base64_encode',array($Wn,$M,$U,$k)));$Uj=adminer()->permanentLogin(true);$zj[$w]="$w:".base64_encode($Uj?encrypt_string($D,$Uj):"");cookie("adminer_permanent",implode(" ",$zj));}if(!array_diff(array_keys($_POST),array("auth","token"))||$Wn!=DRIVER||$M!=SERVER||$U!==$_GET["username"]||$k!=DB)redirect(auth_url($Wn,$M,$U,$k));}elseif($_POST["logout"]&&(!$_SESSION["token"]||verify_token())){Driver::disconnect();foreach(array("pwds","db","dbs","queries")as$w)set_session($w,null);unset_permanent($zj);redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.'.' '.str_replace('<a ','<a'.target_blank().' ','Thanks for using Adminer. Consider <a href="https://www.adminer.org/en/donation/">donating</a>.'));}elseif($zj&&!$_SESSION["pwds"]){session_regenerate_id();$Uj=adminer()->permanentLogin();foreach($zj
as$w=>$W){list(,$xb)=explode(":",$W);list($Wn,$M,$U,$k)=array_map('base64_decode',explode("-",$w));set_password($Wn,$M,$U,decrypt_string(base64_decode($xb),$Uj));$_SESSION["db"][$Wn][$M][$U][$k]=true;}}function
unset_permanent(array&$zj){foreach($zj
as$w=>$W){list($Wn,$M,$U,$k)=array_map('base64_decode',explode("-",$w));if($Wn==DRIVER&&$M==SERVER&&$U==$_GET["username"]&&$k==DB)unset($zj[$w]);}cookie("adminer_permanent",implode(" ",$zj));}function
auth_error($m,array&$zj,$Uf=true){$wl=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$wl]||$_GET[$wl])&&!$_SESSION["token"])$m='Session expired. Please log in again.';elseif($Uf&&($D=get_password())!==null){restart_session();add_invalid_login();if($D===false)$m
.=($m?'<br>':'').sprintf('Master password expired. <a href="https://www.adminer.org/en/extension/"%s>Implement</a> the %s method to make it permanent.',target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);unset_permanent($zj);}}if(!$_COOKIE[$wl]&&$_GET[$wl]&&ini_bool("session.use_only_cookies"))$m='Session support must be enabled.';$dj=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?:rand_string()),$dj["lifetime"]);if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);page_header('Login',$m,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth","token")))echo"<p class='message'>".'The action will be performed after successful login with the same credentials.'."\n";echo
input_token(),"</div>\n";adminer()->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists('Adminer\Db')){unset($_SESSION["pwds"][DRIVER]);unset_permanent($zj);page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",Driver::$extensions)),false);page_footer("auth");exit;}$g='';if(isset($_GET["username"])&&is_string(get_password())){check_invalid_login($zj);$ic=adminer()->credentials();$g=Driver::connect($ic[0],$ic[1],$ic[2]);if(is_object($g)){Db::$instance=$g;Driver::$instance=new
Driver($g);if($g->flavor)save_settings(array("vendor-".DRIVER."-".SERVER=>get_driver(DRIVER)));}}$Mg=null;if(!is_object($g)||($Mg=adminer()->login($_GET["username"],get_password()))!==true){$m=(is_string($g)?nl_br(h($g)):(is_string($Mg)?$Mg:'Invalid credentials.')).(preg_match('~^ | $~',get_password())?'<br>'.'There is a space in the entered password, which might be the cause.':'');auth_error($m,$zj);}if($_POST["logout"]&&$_SESSION["token"]&&!verify_token()){page_header('Logout','Invalid CSRF token. Submit the form again.');page_footer("db");exit;}if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);stop_session(true);if($Ka&&$_POST["token"])$_POST["token"]=get_token();$m='';if($_POST){if(!verify_token()){header("HTTP/1.1 403 Forbidden");$m='Invalid CSRF token. Submit the form again.'.' '.'If you did not send this request from Adminer, close this page.';}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){header("HTTP/1.1 413 Content Too Large");$m=sprintf('The POST data is too large. Reduce the data or increase the %s configuration directive.',"<b>post_max_size</b>");if(isset($_GET["sql"]))$m
.=' '.'You can upload a large SQL file via FTP and import it from the server.';}function
print_select_result($G,$h=null,array$Li=array(),&$y=0,&$hd=false){$Hg=array();$v=array();$e=array();$S=array();$Rj=array();$ld=array();$qn=array();$H=array();$xh=$hd;$hd=false;for($r=0;(!$y||$r<$y)&&is_array($I=$G->fetch_row());$r++){if(!$r){echo"<div class='scrollable'>\n","<table class='nowrap odds'".($xh?on('click','tableClick').on('dblclick','tableClick').on('keydown','editingKeydown'):"").">\n","<thead><tr>";for($fg=0;$fg<count($I);$fg++){$n=$G->fetch_field();$A=$n->name;$Q=(isset($n->table)?$n->table:"");$Ki=(isset($n->orgtable)?$n->orgtable:"");$Ji=(isset($n->orgname)?$n->orgname:$A);$pn=driver()->typeName($n);if($Li&&JUSH=="sql")$Hg[$fg]=($A=="table"?"table=":($A=="possible_keys"?"indexes=":null));elseif($Ki!=""){$ua=($Q!=""?$Q:$Ki);if($Q!="")$H[$Q]=$Ki;if(!isset($v[$ua])){if(!isset($Rj[$Ki])){$Rj[$Ki]=array();foreach(indexes($Ki,$h)as$u){if($u["type"]=="PRIMARY"){$Rj[$Ki]=array_flip($u["columns"]);break;}}}$S[$ua]=$Ki;$v[$ua]=$Rj[$Ki];$e[$ua]=$Rj[$Ki];}if(isset($e[$ua][$Ji])){unset($e[$ua][$Ji]);$v[$ua][$Ji]=$fg;$Hg[$fg]=$ua;}elseif($xh&&isset($n->orgname)&&$n->db==DB&&!is_blob(array("type"=>$pn)))$ld[$fg]=array($ua,$Ji,preg_match('~text|json|lob~',$pn));}$qn[$fg]=$pn;echo"<th title='".h(trim(($Ki!=""?"$Ki.$Ji":($n->name!=$Ji?$Ji:""))." ".$pn))."'>".h($A).($Li?doc_link(array('sql'=>"explain-output.html#explain_".strtolower($A),'mariadb'=>"explain/#the-columns-in-explain-select",)):"");}foreach($ld
as$fg=>$ib){if($e[$ib[0]])unset($ld[$fg]);}echo"<tbody>\n";}$tf=array();foreach($v
as$ua=>$u){if($u&&!$e[$ua]){$t="";foreach($u
as$Bb=>$fg){if($I[$fg]===null){$t=null;break;}$t
.="&where[".url_escape(bracket_escape($Bb))."]=".url_escape($I[$fg]);}$tf[$ua]=$t;}}echo"<tr>";foreach($I
as$w=>$W){$z="";if(isset($Hg[$w])){if($Li&&JUSH=="sql"){$Q=$I[array_search("table=",$Hg)];$z=ME.$Hg[$w].url_escape($Li[$Q]!=""?$Li[$Q]:$Q);}elseif(idx($tf,$Hg[$w])!==null)$z=ME."edit=".url_escape($S[$Hg[$w]]).$tf[$Hg[$w]];}$of=select_value($W,$z,array('type'=>(preg_match('~binary~',$qn[$w])?'blob':$qn[$w])),null);$c="";$ib=idx($ld,$w);if($ib&&idx($tf,$ib[0])!==null&&is_utf8($W)){$hd=true;$c=" data-name='".h("val[".bracket_escape($S[$ib[0]])."][".bracket_escape(substr($tf[$ib[0]],1))."][".bracket_escape($ib[1])."]")."' data-text='".($ib[2]?1:0)."'".(html_entity_decode(strip_tags($of),ENT_QUOTES,"UTF-8")!==$W?" data-value='".h($W)."'":"");}echo"<td".(preg_match(number_type(),$qn[$w])?" class='number'":"")."$c>$of";}}$y=$r;echo($r?"</table>\n</div>":"<p class='message'>".'No rows.')."\n";return$H;}function
textarea($A,$X,$J=10,$Fb=80,$hg=JUSH,$zd=0){echo"<textarea name='".h($A)."' rows='$J' cols='$Fb' class='sqlarea jush-".h($hg)."' spellcheck='false' wrap='off'".($zd?" data-error-line='$zd'":"").">\n";if(is_array($X)){foreach($X
as$W)echo
h($W[0])."\n\n\n";}else
echo
h($X);echo"</textarea>";}function
select_input($c,array$Ei,$X="",$_j=""){if($Ei&&$X!=""&&!isset($Ei[$X]))$Ei=array($X=>$X)+$Ei;$Bm=($Ei?"select":"input");return"<$Bm$c".($Ei?"><option value=''>$_j".optionlist($Ei,$X,true)."</select>":" size='10' value='".h($X)."' placeholder='$_j'>");}function
json_row($w,$W=null,$Cd=true){static$he=true;if($he)echo"{";if($w!=""){echo($he?"":",")."\n\t\"".addcslashes($w,"\r\n\t\"\\/").'": '.($W!==null?($Cd?'"'.addcslashes($W,"\r\n\"\\/").'"':$W):'null');$he=false;}else{echo"\n}\n";$he=true;}}function
flat_collations(){$Eb=collations();return(is_array(reset($Eb))?call_user_func_array('array_merge',array_values($Eb)):$Eb);}function
edit_type($w,array$n,array$Eb,array$re=array(),array$Sd=array()){$T=(string)$n["type"];echo"<td><select name='".h($w)."[type]' class='type' aria-labelledby='label-type'".on_help_value().">";if($T&&!array_key_exists($T,driver()->types())&&!isset($re[$T])&&!in_array($T,$Sd))$Sd[]=$T;$Zl=driver()->structuredTypes();if($re)$Zl['Foreign keys']=$re;echo
optionlist(array_merge($Sd,$Zl),$T),"</select><td>","<input name='".h($w)."[length]' value='".h($n["length"])."' size='3'".(!$n["length"]&&preg_match('~var(char|binary)$~',$T)?" class='required'":"")." aria-labelledby='label-length'>","<td class='options'>",($Eb?"<input list='collations' name='".h($w)."[collation]'".option_types($T,'('.text_type().')$')." value='".h($n["collation"])."' placeholder='(".'collation'.")'>":''),(driver()->unsigned?"<select name='".h($w)."[unsigned]'".option_types($T,'^$|'.number_type()).'><option>'.optionlist(driver()->unsigned,$n["unsigned"]).'</select>':''),(isset($n['on_update'])?"<select name='".h($w)."[on_update]'".option_types($T,'timestamp|datetime').'>'.optionlist(array(""=>"(".'ON UPDATE'.")","CURRENT_TIMESTAMP"),(preg_match('~^CURRENT_TIMESTAMP~i',$n["on_update"])?"CURRENT_TIMESTAMP":$n["on_update"])).'</select>':''),($re?"<select name='".h($w)."[on_delete]'".option_types($T,'`')."><option value=''>(".'ON DELETE'.")".optionlist(explode("|",driver()->onActions),$n["on_delete"])."</select> ":" ");}function
option_types($T,$qn){return" data-types='".h($qn)."'".(preg_match("~$qn~",$T)?"":" class='hidden'");}function
process_length($x){if(JUSH=="mssql"&&preg_match('~^\s*\(?\s*max\s*\)?\s*$~i',$x))return"(max)";if(JUSH=="oracle"&&preg_match('~^\s*\(?\s*(\d+)\s+(CHAR|BYTE)\s*\)?\s*$~i',$x,$_))return"($_[1] ".strtoupper($_[2]).")";$wd=driver()->enumLength;return(preg_match("~^\\s*\\(?\\s*$wd(?:\\s*,\\s*$wd)*+\\s*\\)?\\s*\$~",$x)&&preg_match_all("~$wd~",$x,$Rg)?"(".implode(",",$Rg[0]).")":preg_replace('~^[0-9].*~','(\0)',preg_replace('~[^-0-9,+()[\]]~','',$x)));}function
process_in($W){$W=preg_replace('~^\s*\(\s*(.*?)\s*\)\s*$~s','\1',$W);$ik="'(?:[^']|'')*+'";$eg=(preg_match("~^\\s*$ik(?:\\s*,\\s*$ik)*+\\s*\$~",$W)&&preg_match_all("~$ik~",$W,$Rg)?array_map('Adminer\idf_unescape',$Rg[0]):array_map('trim',explode(",",$W)));return"(".implode(", ",array_map('Adminer\q',$eg)).")";}function
process_between($W){$ik="('(?:[^']|'')*+')";$kk=(preg_match("~^\\s*$ik\\s+AND\\s+$ik\\s*\$~i",$W,$_)?array(idf_unescape($_[1]),idf_unescape($_[2])):preg_split('~\s+AND\s+~i',trim($W)));return(count($kk)==2?q($kk[0])." AND ".q($kk[1]):"NULL AND NULL");}function
process_type(array$n,$Cb="COLLATE"){return" ".(is_user_type($n["type"])?idf_escape($n["type"]):$n["type"]).process_length($n["length"]).(preg_match(number_type(),$n["type"])&&in_array($n["unsigned"],driver()->unsigned)?" $n[unsigned]":"").(preg_match('~'.text_type().'~',$n["type"])&&$n["collation"]?" $Cb ".(JUSH=="mssql"&&preg_match('~^\w+$~',$n["collation"])?$n["collation"]:q($n["collation"])):"");}function
process_field(array$n,array$mn){return
array(idf_escape(trim($n["field"])),process_type($mn),($n["null"]?" NULL":" NOT NULL"),default_value($n),(preg_match('~timestamp|datetime~',$n["type"])&&$n["on_update"]?" ON UPDATE $n[on_update]".process_length($n["length"]):""),(support("comment")&&$n["comment"]!=""?" COMMENT ".q($n["comment"]):""),($n["auto_increment"]?auto_increment():null),);}function
default_value(array$n){if($n["default"]===null)return"";$l=str_replace("\r","",$n["default"]);$Fe=$n["generated"];$P=!preg_match('~]$~',$n["length"])&&(preg_match('~char|binary|text|clob|json|enum|set|String~',$n["type"])||driver()->enumLength($n));$ej=(JUSH=="sqlite"||(JUSH=="sql"&&$n["default_sql"]&&!preg_match("~^(current_timestamp(\\(\\d*\\))?|[bx]'[\\da-f]*')$~i",$l)));return(in_array($Fe,driver()->generated)?(JUSH=="mssql"?" AS ($l)".($Fe=="VIRTUAL"?"":" $Fe"):" GENERATED ALWAYS AS ($l) $Fe"):(preg_match('~^GENERATED ~i',$l)?" $l":" DEFAULT ".(!$n["default_sql"]&&($P||preg_match('~^(?![a-z])~i',$l))?(JUSH=="sql"&&preg_match('~text|json~',$n["type"])?"(".q($l).")":q($l)):($ej?"($l)":$l))));}function
edit_fields(array$o,array$Eb,$T="TABLE",array$re=array()){$o=array_values($o);$zc=(($_POST?$_POST["defaults"]:get_setting("defaults"))?"":" class='hidden'");$Kb=(($_POST?$_POST["comments"]:get_setting("comments"))?"":" class='hidden'");echo"<thead><tr>\n",($T=="PROCEDURE"?"<td>":""),"<th id='label-name'>".($T=="TABLE"?'Column name':'Parameter name'),"<th id='label-type'>".'Type'."<textarea id='enum-edit' rows='4' cols='12' wrap='off' hidden></textarea>".script("qs('#enum-edit').onblur = editingLengthBlur;"),"<th id='label-length'>".'Length',"<th>".'Options';if($T=="TABLE")echo"<th id='label-null'>NULL\n","<th><input type='radio' name='auto_increment_col' value=''><abbr id='label-ai' title='".'Auto Increment'."'>A_I</abbr>",doc_link(array('sql'=>"example-auto-increment.html",'mariadb'=>"auto_increment/",'sqlite'=>"autoinc.html",'pgsql'=>"datatype-numeric.html#DATATYPE-SERIAL",'cockroach'=>"serial",'mssql'=>"t-sql/statements/create-table-transact-sql-identity-property",)),"<th id='label-default'$zc>".'Default value',(support("comment")?"<th id='label-comment'$Kb>".'Comment':"");$vg=!support("move_col");echo"<td>".icon("plus","add[".($vg?count($o):0)."]","+",'Add next',($vg?on('click','editingAddLastRow'):"")),"<tbody".on('click','editingClick').on('input','editingInput').on('keydown','editingKeydown').">\n";foreach($o
as$r=>$n){$r++;$Mi=$n[($_POST?"orig":"field")];$Nc=(isset($_POST["add"][$r-1])||(isset($n["field"])&&!idx($_POST["drop_col"],$r)))&&(support("drop_col")||$Mi=="");echo"<tr".($Nc?"":" hidden").">\n",($T=="PROCEDURE"?"<td>".html_select("fields[$r][inout]",explode("|",driver()->inout),$n["inout"]):"")."<th>",(support("move_col")?icon("move","","↕",'Move')." ":"");if($Nc)echo"<input name='fields[$r][field]' value='".h($n["field"])."' data-maxlength='64' autocapitalize='off' aria-labelledby='label-name'".(isset($_POST["add"][$r-1])?" autofocus":"").">";echo
input_hidden("fields[$r][orig]",$Mi);edit_type("fields[$r]",$n,$Eb,$re);if($T=="TABLE"){echo"<td><label class='block'>".checkbox("fields[$r][null]",1,$n["null"],"","","","label-null")."</label>","<td><label class='block'><input type='radio' name='auto_increment_col' value='$r'".($n["auto_increment"]?" checked":"")." aria-labelledby='label-ai'></label>","<td$zc>".html_select("fields[$r][generated]",array_merge(array("","DEFAULT","DEFAULT SQL"),driver()->generated),$n["generated"],"","label-default")." ";$c=" name='fields[$r][default]' aria-labelledby='label-default'";$X=h($n["default"]);echo(preg_match('~\n~',$n["default"])?"<textarea$c rows='2' cols='30' style='vertical-align: bottom;'>\n$X</textarea>":"<input$c value='$X'>");if(support("comment")){$c=" name='fields[$r][comment]' data-maxlength='".(min_version(5.5)?1024:255)."' aria-labelledby='label-comment'";echo"<td$Kb>".adminer()->commentInput('COLUMN',$c,$n["comment"]);}}echo"<td>",(support("move_col")?icon("plus","add[$r]","+",'Add next')." ":""),($Mi==""||support("drop_col")?icon("cross","drop_col[$r]","x",'Remove'):"");}}function
process_fields(array&$o){if($_POST["add"]){$o=array_values($o);array_splice($o,key($_POST["add"]),0,array(array()));}return$_POST["add"]||$_POST["drop_col"];}function
drop_create($ad,$i,$cd,$Hm,$ed,$Lg,$mh,$kh,$lh,$qi,$Rh){if($_POST["drop"])query_redirect($ad,$Lg,$mh);elseif($qi=="")query_redirect($i,$Lg,$lh);elseif(support("transaction_ddl")){driver()->begin();queries_redirect($Lg,$kh,queries($ad)&&queries($i)&&driver()->commit());driver()->rollback();}elseif($qi!=$Rh){$hc=queries($i);queries_redirect($Lg,$kh,$hc&&queries($ad));if($hc&&$cd)queries($cd);}else
queries_redirect($Lg,$kh,queries($Hm)&&queries($ed)&&queries($ad)&&queries($i));}function
create_trigger($ui,array$I){$Nm=" $I[Timing] $I[Event]".(preg_match('~ OF~',$I["Event"])?" $I[Of]":"");return"CREATE TRIGGER ".idf_escape($I["Trigger"]).(JUSH=="mssql"?$ui.$Nm:$Nm.$ui).preg_replace('~[\s;]+$~',''," $I[Type]\n$I[Statement]").";";}function
q_dollar($P){$Ec='$$';while(strpos($P.$Ec,$Ec)!=strlen($P))$Ec='$_'.substr($Ec,1);return$Ec.$P.$Ec;}function
routine_collate($Db){static$ob=array();if($Db&&!$ob){foreach(collations()as$nb=>$Un){foreach((array)$Un
as$W)$ob[$W]=$nb;}}return($ob[$Db]?"CHARACTER SET ".q($ob[$Db])." ":"")."COLLATE";}function
create_routine($Ik,array$I){$N=array();$o=$I["fields"];ksort($o);foreach($o
as$n){if($n["field"]!=""){$Mf=(preg_match("~^(".driver()->inout.")\$~",$n["inout"])?$n["inout"]:"");$N[]="\n  ".(JUSH=="mssql"?"@$n[field]".process_type($n).($Mf?" $Mf":""):($Mf?"$Mf ":"").idf_escape($n["field"]).process_type($n,routine_collate($n["collation"])));}}$Bc="";$Ei=array();foreach(routine_options($Ik)as$w=>$Y){$X=idx($I["options"],$w,"");if($w=="DEFINER")$Bc=($X?" $w=".implode("@",array_map('Adminer\q',explode("@",$X,2))):"");elseif(!$Y){if($X!="")$Ei[]="$w ".q($X);}elseif($X!=reset($Y)&&in_array($X,$Y))$Ei[]=$X;}$tg=$I["language"];$Cc=preg_replace('~[\s;]+$~','',$I["definition"]);$Wc=(JUSH=="pgsql"||($tg&&$tg!="sql"));$cj=($N?implode(",",$N)."\n":"");return"CREATE$Bc $Ik ".table(trim($I["name"])).(JUSH=="mssql"&&$Ik=="PROCEDURE"?rtrim($cj):" ($cj)").($Ik=="FUNCTION"?"\nRETURNS".process_type($I["returns"],routine_collate($I["returns"]["collation"])):"").($tg?" LANGUAGE $tg":"").($Ei?"\n".implode(" ",$Ei):"").($Wc?" AS ".q_dollar("\n".trim($Cc)."\n"):(JUSH=="mssql"?"\nAS":"")."\n$Cc;");}function
remove_definer($F){$Bc=implode("@",array_map('Adminer\idf_escape',explode("@",logged_user(),2)));return
preg_replace('(^([A-Z =]+) DEFINER='.preg_quote($Bc).')','\1',$F);}function
object_name($T,$Q,array$e){return
str_replace(array("{table}","{columns}"),array($Q,implode("_",$e)),adminer()->namePattern($T));}function
format_foreign_key(array$q,$A=""){$k=$q["db"];$bi=$q["ns"];return($A!=""?" CONSTRAINT ".idf_escape($A):"")." FOREIGN KEY (".implode(", ",array_map('Adminer\idf_escape',$q["source"])).") REFERENCES ".($k!=""&&$k!=$_GET["db"]?idf_escape($k).".":"").($bi!=""&&$bi!=$_GET["ns"]?idf_escape($bi).".":"").idf_escape($q["table"])." (".implode(", ",array_map('Adminer\idf_escape',$q["target"])).")".(preg_match("~^(".driver()->onActions.")\$~",$q["on_delete"])?" ON DELETE $q[on_delete]":"").(preg_match("~^(".driver()->onActions.")\$~",$q["on_update"])?" ON UPDATE $q[on_update]":"").($q["deferrable"]?" $q[deferrable]":"");}function
tar_file($p,$Sm){$H=pack("a100a8a8a8a12a12",$p,644,0,0,decoct($Sm->size),decoct(time()));$ub=8*32;for($r=0;$r<strlen($H);$r++)$ub+=ord($H[$r]);$H
.=sprintf("%06o",$ub)."\0 ";echo$H,str_repeat("\0",512-strlen($H));$Sm->send();echo
str_repeat("\0",511-($Sm->size+511)%512);}function
doc_version(){$ul=connection()->server_info;if(JUSH=='oracle'){preg_match('~(?:.* |^)(\d+)\.\d+\.\d+\.\d+\.\d+~s',$ul,$_);return($_[1]>=18?$_[1]:"19");}$tk=(JUSH=='sql'||connection()->flavor=='cockroach'?'~^\d+\.\d+~':'~^\d\.?\d~');$Xn=(preg_match($tk,$ul,$_)?$_[0]:"");if(JUSH=='mssql')return($Xn>=15?"sql-server-ver$Xn":($Xn==12?"azuresqldb-current":"sql-server-2017"));return$Xn;}function
doc_link(array$vj,$Im="📖"){$Xn=doc_version();$Hn=array('sql'=>"https://dev.mysql.com/doc/refman/$Xn/en/",'sqlite'=>"https://www.sqlite.org/",'pgsql'=>"https://www.postgresql.org/docs/".(connection()->flavor=='cockroach'?"current":$Xn)."/",'mssql'=>"https://learn.microsoft.com/en-us/sql/",'oracle'=>"https://docs.oracle.com/en/database/oracle/oracle-database/$Xn/",);if(connection()->flavor=='maria'){$Hn['sql']="https://mariadb.com/kb/en/";$vj['sql']=($vj['mariadb']?:str_replace(".html","/",$vj['sql']));}if(connection()->flavor=='cockroach'&&$vj['cockroach']){$Hn['pgsql']="https://docs.cockroachlabs.com/docs/v$Xn/";$vj['pgsql']=$vj['cockroach'];}return($vj[JUSH]?" <a href='".h($Hn[JUSH].$vj[JUSH].(JUSH=='mssql'?"?view=$Xn":""))."'".target_blank()." class='doc' title='".'Documentation'."'>$Im</a>":"");}function
db_size($k){if(!connection()->select_db($k))return"?";$H=0;foreach(table_status()as$R)$H+=$R["Data_length"]+$R["Index_length"];return
adminer()->formatSizeValue($H);}function
set_utf8mb4($i){static$N=false;if(!$N&&preg_match('~\butf8mb4~i',$i)){$N=true;echo"SET NAMES ".charset(connection()).";\n\n";}}if(DB==""&&isset($_GET["ns"]))redirect(remove_from_uri('ns'));if(!(DB!=""?connection()->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect"||$_GET["script"]=="kill")){if(DB!=""||$_GET["refresh"]){restart_session();set_session("dbs",null);}if(DB!="")page_header('Database'.": ".h(DB),adminer()->error(),true,"","db");else{if(!isset($_GET["db"])&&support("single_db")){$j=adminer()->databases();if($j)redirect(ME."db=".url_escape($j[0]));}if($_POST["db"]&&!$m)queries_redirect(substr(ME,0,-1),'Databases have been dropped.',drop_databases($_POST["db"]));page_header('Select database',$m,false);echo"<p class='links'>\n";foreach(array('database'=>'Create database','privileges'=>'Privileges','processlist'=>'Process list','variables'=>'Variables','status'=>'Status',)as$w=>$W){if(support($w))echo"<a href='".h(ME)."$w='>$W</a>\n";}echo"<p>".sprintf('%s version: %s through PHP extension %s',get_driver(DRIVER),"<b>".h(connection()->server_info)."</b>","<b>".connection()->extension."</b>")."\n","<p>".sprintf('Logged in as: %s',"<b>".h(logged_user())."</b>")."\n";$j=adminer()->databases();if($j){$Vk=support("scheme");$Eb=collations();echo"<form action='' method='post'>\n","<table class='checkable odds'".on('click','tableClick').on('dblclick','tableClick').">\n","<thead><tr>".(support("database")?"<td class='hover'>":"")."<th".(JUSH!='mssql'?" aria-sort='ascending'":"").">".'Database'.(get_session("dbs")!==null?" - <a href='".h(ME)."refresh=1'>".'Refresh'."</a>":"")."<th>".'Collation'."<th>".'Tables'."<th>".'Size'." - <a href='".h(ME)."dbsize=1'".on('click','ajaxSetHtml',ME."script=connect").">".'Compute'."</a>"."<tbody>\n";$j=($_GET["dbsize"]?count_tables($j):array_flip($j));foreach($j
as$k=>$S){$Hk=h(preg_replace('~&db=[^&]*~','',ME))."db=".url_escape($k);$s=h("Db-".$k);echo"<tr>".(support("database")?"<td class='hover'>".checkbox("db[]",$k,in_array($k,(array)$_POST["db"]),"","","",$s):""),"<th><a href='$Hk' id='$s'>".h($k)."</a>";$Db=h(db_collation($k,$Eb));echo"<td>".(support("database")?"<a href='$Hk".($Vk?"&amp;ns=":"")."&amp;database=' title='".'Alter database'."'>$Db</a>":$Db),"<td align='right'><a href='$Hk&amp;schema=' id='tables-".h($k)."' title='".'Database schema'."'>".($_GET["dbsize"]?format_number($S):"?")."</a>","<td align='right' id='size-".h($k)."'>".($_GET["dbsize"]?db_size($k):"?"),"\n";}echo"</table>\n",(support("database")?"<div class='footer'><div>\n"."<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>\n"."<input type='hidden' name='all' value=''".on('click','countDbs').">\n"."<input type='submit' name='drop' value='".'Drop'."'".confirm().">\n"."</div></fieldset>\n"."</div></div>\n":""),input_token(),"</form>\n",script("tableCheck();");}$oa=adminer();$Dj=($oa
instanceof
Plugins?$oa->plugins:array());$Zc=($oa
instanceof
Plugins?$oa->drivers:array());$Kc=design_checksums();if($Dj||$Zc||$Kc){$vb=($oa
instanceof
Plugins?$oa->checksums():array());$ji=Plugins::officialChecksums();$Cn=function($Gn){return" (<a href='$Gn'".target_blank()." class='update'>".VERSION."</a>)";};$Cj=function($be)use($vb,$ji,$Cn){return($vb[$be]&&$ji[$be]&&$vb[$be]!==$ji[$be]?$Cn("https://www.adminer.org/plugins/?version=".VERSION):"");};echo"<div class='plugins'>\n","<h3>".'Loaded plugins'."</h3>\n<ul>\n";foreach($Dj
as$Aj){$rk=new
\ReflectionObject($Aj);$Hc=Plugins::describe($Aj);echo"<li><b>".get_class($Aj)."</b>".($Hc!=""?": $Hc":"").$Cj(basename((string)$rk->getFileName(),'.php'))."\n";}foreach($Zc
as$s=>$A)echo"<li><b>".h($s)."</b>: ".h($A).$Cj(basename((string)$oa->driverFiles[$s],'.php'))."\n";if($Kc){$li=official_design_checksums();foreach($Kc
as$p=>$Jc){list($A,$ub)=$Jc;$ki=$li["$A/$p"];echo"<li><b>".h($p)."</b>".h($A?": $A":"").($ki&&$ki!==$ub?$Cn("https://www.adminer.org/?version=".VERSION."#extras"):"")."\n";}}echo"</ul>\n";adminer()->pluginsLinks();echo"</div>\n";}}page_footer("db");exit;}if(support("scheme")){if(DB!=""&&$_GET["ns"]!==""){if(!isset($_GET["ns"]))redirect(preg_replace('~&db=[^&]+~','\0&ns='.url_escape(get_schema()),relative_uri()));if(!set_schema($_GET["ns"]))page_header('Schema'.h(": $_GET[ns]"),adminer()->error(),true,"","ns");}}adminer()->afterConnect();class
TmpFile{private$handler;var$size=0;function
__construct(){$this->handler=tmpfile();}function
write($Yb){$this->size+=strlen($Yb);fwrite($this->handler,$Yb);}function
send(){fseek($this->handler,0);fpassthru($this->handler);fclose($this->handler);}}if($_GET["select"]!=""&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["callf"]))$_GET["call"]=$_GET["callf"];if(isset($_GET["function"]))$_GET["procedure"]=$_GET["function"];if(isset($_GET["download"])){$a=$_GET["download"];$o=fields($a);header("Content-Type: application/octet-stream");$Y=array_merge((array)$_GET["where"],(array)$_GET["val"]);header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$Y)).".".friendly_url($_GET["field"]));$L=array(idf_escape($_GET["field"]));$G=driver()->select($a,$L,array(where($_GET,$o)),$L);$I=($G?$G->fetch_row():array());echo
driver()->value($I[0],$o[$_GET["field"]]);exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$o=fields($a);if(!$o)$m=adminer()->error();$R=table_status1($a);$A=adminer()->tableName($R);$m=$m?:h($R["Error"]);page_header(($o&&is_view($R)?$R['Engine']=='materialized view'?'Materialized view':'View':'Table').": ".($A!=""?$A:h($a)),$m,array(),"",!$o,($o?doc_link(array(JUSH=>driver()->tableHelp($a,is_view($R)))):""));$Gk=array();foreach($o
as$w=>$n)$Gk+=$n["privileges"];adminer()->selectLinks($R,(isset($Gk["insert"])||!support("table")?"":null));$f=$R["Comment"];if($f!="")echo"<p class='nowrap'>".'Comment'.": ".adminer()->commentValue('TABLE',$f)."\n";if($o)adminer()->tableStructurePrint($o,$R);function
tables_links(array$S){echo"<ul>\n";foreach($S
as$I){$z=preg_replace('~&ns=[^&]*~',"&ns=".url_escape($I["ns"]),ME);echo"<li><a href='".h($z."table=".url_escape($I["table"]))."'>".($I["ns"]!=$_GET["ns"]?"<b>".h($I["ns"])."</b>.":"").h($I["table"])."</a>";}echo"</ul>\n";}$Kf=driver()->inheritsFrom($a);if($Kf){echo"<h3>".'Inherits from'."</h3>\n";tables_links($Kf);}if(support("indexes")&&driver()->supportsIndex($R)){echo"<div>\n","<h3 id='indexes'>".'Indexes'."</h3>\n";$v=indexes($a);if($v)adminer()->tableIndexesPrint($v,$R);if(driver()->supportsAlterIndex($R))echo'<p class="links hover"><a href="'.h(ME).'indexes='.url_escape($a).'">'.'Alter indexes'."</a>\n";echo"</div>\n";}if(!is_view($R)&&driver()->supportsAlterTable($R)){if(fk_support($R)){echo"<div>\n","<h3 id='foreign-keys'>".'Foreign keys'."</h3>\n";$re=foreign_keys($a);$za=function_exists('Adminer\alter_table');if($re){echo"<table>\n","<thead><tr><th>".'Source'."<th>".'Target'."<th>".'ON DELETE'."<th>".'ON UPDATE'.($za?"<td class='hover'>":"")."<tbody>\n";foreach($re
as$A=>$q){echo"<tr".(!is_int($A)?" title='".h($A)."'":"").">","<th><i>".implode("</i>, <i>",array_map('Adminer\h',$q["source"]))."</i>";$z=($q["db"]!=""?preg_replace('~&db=[^&]*~',"&db=".url_escape($q["db"]),ME):($q["ns"]!=""?preg_replace('~&ns=[^&]*~',"&ns=".url_escape($q["ns"]),ME):ME));echo"<td><a href='".h($z."table=".url_escape($q["table"]))."'>".($q["db"]!=""&&$q["db"]!=DB?"<b>".h($q["db"])."</b>.":"").($q["ns"]!=""&&$q["ns"]!=$_GET["ns"]?"<b>".h($q["ns"])."</b>.":"").h($q["table"])."</a>","(<i>".implode("</i>, <i>",array_map('Adminer\h',$q["target"]))."</i>)","<td>".h($q["on_delete"]),"<td>".h($q["on_update"]),($za?'<td class="hover"><a href="'.h(ME.'foreign='.url_escape($a).'&name='.url_escape($A)).'">'.'Alter'.'</a>':''),"\n";}echo"</table>\n";}echo($za?'<p class="links hover"><a href="'.h(ME).'foreign='.url_escape($a).'">'.'Create foreign key'."</a>\n":''),"</div>\n";}if(support("check")){echo"<div>\n","<h3 id='checks'>".'Checks'."</h3>\n";$qb=driver()->checkConstraints($a);if($qb){echo"<table>\n";foreach($qb
as$w=>$W)echo"<tr".($w!=$W?" title='".h($w)."'":"").">","<td><code class='sql jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim($W)),80,"</code>"),"<td class='hover'><a href='".h(ME.'check='.url_escape($a).'&name='.url_escape($w))."'>".'Alter'."</a>","\n";echo"</table>\n";}echo'<p class="links hover"><a href="'.h(ME).'check='.url_escape($a).'">'.'Create check'."</a>\n","</div>\n";}}if(support(is_view($R)?"view_trigger":"trigger")&&driver()->supportsAlterTable($R)){echo"<div>\n","<h3 id='triggers'>".'Triggers'."</h3>\n";$jn=triggers($a);if($jn){echo"<table>\n";foreach($jn
as$w=>$W){echo"<tr valign='top'><td>".h($W[0])."<td>".h($W[1])."<th>".h($w)."<td class='hover'><a href='".h(ME.'trigger='.url_escape($a).'&name='.url_escape($w))."'>".'Alter'."</a>";$Ik=$W[2];if($Ik){$Kk=preg_replace('~&ns=[^&]*~',"&ns=".url_escape($Ik["ns"]),ME).'function='.url_escape($Ik["function"]).'&name='.url_escape($Ik["name"]);echo", <a href='".h($Kk)."' title='".h($Ik["name"])."'>".'Alter function'."</a>";}echo"\n";}echo"</table>\n";}echo'<p class="links hover"><a href="'.h(ME).'trigger='.url_escape($a).'">'.'Create trigger'."</a>\n","</div>\n";}$zl=driver()->shadowTables($a);if($zl){echo"<h3 id='shadow-tables'>".'Shadow tables'."</h3>\n";tables_links($zl);}$Jf=driver()->inheritedTables($a);if($Jf){echo"<h3 id='partitions'>".'Inherited by'."</h3>\n";$ij=driver()->partitionsInfo($a);if($ij)echo"<p><code class='sql jush-".JUSH."'>BY ".h("$ij[partition_by]($ij[partition])")."</code>\n";tables_links($Jf);}}elseif(isset($_GET["schema"])){page_header('Database schema',"",array(),h(DB.($_GET["ns"]?".$_GET[ns]":"")));function
schema_column($Q,array$qk,array&$e){if(!isset($e[$Q])){$e[$Q]=0;foreach((array)idx($qk,$Q)as$A=>$sk){if($A!=$Q)$e[$Q]=max($e[$Q],schema_column($A,$qk,$e)+1);}}return$e[$Q];}function
type_class($T){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$w=>$W){if(preg_match("~$w|$W~",$T))return" class='$w'";}}$tm=array();$vm=array();$um=array();$Yd=array();$ca=($_GET["schema"]?:$_COOKIE["adminer_schema-".str_replace(".","_",DB)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$ca,$Rg,PREG_SET_ORDER);foreach($Rg
as$r=>$_){$tm[$_[1]]=array((float)$_[2],(float)$_[3]);$vm[]="\n\t'".js_escape($_[1])."': [ $_[2], $_[3] ]";}$K=array();$qk=array();$re=array();$xa=driver()->allFields();$gf=array();$wm=array();foreach(table_status('',true)as$Q=>$R){if(!is_view($R)){if(adminer()->tableName($R)!=""&&!$R["dependent"])$wm[$Q]=$R;else$gf[$Q]=true;}}foreach($wm
as$Q=>$R){$E=0;$K[$Q]["fields"]=array();foreach($xa[$Q]as$n){$E+=1.25;$Yd[$Q][$n["field"]]=$E;$K[$Q]["fields"][$n["field"]]=$n;}foreach(adminer()->foreignKeys($Q)as$W){if($W["db"]==""&&$W["ns"]==""&&!$gf[$W["table"]]){$re[$Q][]=$W;$qk[$W["table"]][$Q]=array();}}}$e=array();$Je=array();$ko=array();$Qe=array();foreach(array_keys($K)as$A)schema_column($A,$qk,$e);arsort($e);foreach($e
as$A=>$d){$th=null;foreach((array)idx($re,$A)as$W){if($W["table"]!=$A&&$K[$W["table"]])$th=($th===null?$e[$W["table"]]:min($th,$e[$W["table"]]));}$e[$A]=max($d,(int)$th-1);}$ym=array();foreach($K
as$A=>$Q){$d=$e[$A];$Je[$d][]=$A;$Km=.75*strlen($A);foreach($Q["fields"]as$n)$Km=max($Km,.65*strlen($n["field"]));$ym[$A]=ceil($Km)+1;$ko[$d]=max(idx($ko,$d,0),$ym[$A]);}foreach($re
as$A=>$Un){foreach($Un
as$W){$Pe=$e[$A]+(idx($e,$W["table"],$e[$A])>$e[$A]?1:0);$Qe[$Pe]=idx($Qe,$Pe,0)+1;}}ksort($Je);$ef=0;$jo=0;$Gb=0;$Pj=null;$pm=array();foreach($Je
as$d=>$S){if($Pj!==null){$Gb=round($Gb+$ko[$Pj]+1.7+idx($Qe,$d,0)*.1,1);$Hi=array();foreach($S
as$A){$em=0;$fc=0;$Mh=array_keys((array)idx($qk,$A));foreach((array)idx($re,$A)as$W)$Mh[]=$W["table"];foreach($Mh
as$Fh){if($K[$Fh]&&$e[$Fh]<$d){$em+=$K[$Fh]["pos"][0];$fc++;}}$Hi[$A]=($fc?$em/$fc:$ef);}asort($Hi);$S=array_keys($Hi);}$Vm=0;foreach($S
as$A){$E=1.25*count($K[$A]["fields"]);$K[$A]["pos"]=($tm[$A]?:array($Vm,$Gb));$pm[$A]=$K[$A]["pos"][1];$Vm+=2.5+$E;$ef=max($ef,$K[$A]["pos"][0]+2.5+$E);$jo=max($jo,round($K[$A]["pos"][1]+$ko[$d],1));if(!$tm[$A])$um[]="\n\t'".js_escape($A)."': [ ".$K[$A]["pos"][0].", ".$K[$A]["pos"][1]." ]";}$Pj=$d;}$zg=array();$Ua=array();foreach($re
as$A=>$Un){foreach($Un
as$W){$Dm=idx($pm,$W["table"],$pm[$A]);$Jl=$pm[$A]+$ko[$e[$A]];$Fk=($Dm-1>$Jl);$xg=($Fk?$Jl+1:min($pm[$A],$Dm)-1);$Ta=idx($Ua,(string)$xg,0);$Ua[(string)$xg]=$Ta+1;$xg=round($Fk?min($xg+$Ta*.1,$Dm-1):$xg-$Ta*.1,1);while($zg[(string)$xg])$xg-=.0001;$K[$A]["references"][$W["table"]][(string)$xg]=array($W["source"],$W["target"]);$qk[$W["table"]][$A][(string)$xg]=$W["target"];$zg[(string)$xg]=true;}}echo'<div id="schema" style="height: ',$ef,'em; width: ',$jo,'em;">
<script',nonce(),'>
const tablePos = {',implode(",",$vm)."\n",'};
const tablePosDefault = {',implode(",",$um)."\n",'};
const em = qs(\'#schema\').offsetHeight / ',$ef,';
document.onmousemove = schemaMousemove;
document.onmouseup = event => schemaMouseup(event, \'',js_escape(DB),'\');
</script>
';foreach($K
as$A=>$Q){echo"<div class='table'".on('mousedown','schemaMousedown')." style='top: ".$Q["pos"][0]."em; left: ".$Q["pos"][1]."em; width: ".$ym[$A]."em;'>",'<a href="'.h(ME).'table='.url_escape($A).'"><b>'.h($A)."</b></a>";foreach($Q["fields"]as$n){$W='<span'.type_class($n["type"]).' title="'.h($n["type"].($n["length"]?"($n[length])":"").($n["null"]?" NULL":'')).'">'.h($n["field"]).'</span>';echo"<br>".($n["primary"]?"<i>$W</i>":$W);}foreach((array)$Q["references"]as$Em=>$sk){foreach($sk
as$xg=>$nk){$yg=$xg-$Q["pos"][1];$bm=($yg>0?"left: 100%; width: calc($yg"."em - 100%)":"left: $yg"."em");$jo=($yg>0?"100%":(-$yg)."em");$r=0;foreach($nk[0]as$Il)echo"\n<div class='references' title='".h($Em)."' id='refs$xg-".($r++)."' style='$bm"."; top: ".$Yd[$A][$Il]."em; padding-top: .5em;'>"."<div style='border-top: 1px solid gray; width: $jo;'></div></div>";}}foreach((array)$qk[$A]as$Em=>$sk){foreach($sk
as$xg=>$Fm){$yg=$xg-$Q["pos"][1];$r=0;foreach($Fm
as$Cm)echo"\n<div class='references arrow' title='".h($Em)."' id='refd$xg-".($r++)."' style='left: $yg"."em; top: ".$Yd[$A][$Cm]."em;'>"."<div style='height: .5em; border-bottom: 1px solid gray; width: ".(-$yg)."em;'></div>"."</div>";}}echo"\n</div>\n";}foreach($K
as$A=>$Q){foreach((array)$Q["references"]as$Em=>$sk){if($K[$Em]){foreach($sk
as$xg=>$nk){$uh=$ef;$Zg=-10;foreach($nk[0]as$w=>$Il){$Fj=$Q["pos"][0]+$Yd[$A][$Il];$Gj=$K[$Em]["pos"][0]+$Yd[$Em][$nk[1][$w]];$uh=min($uh,$Fj,$Gj);$Zg=max($Zg,$Fj,$Gj);}echo"<div class='references' id='refl$xg' style='left: $xg"."em; top: $uh"."em; padding: .5em 0;'><div style='border-right: 1px solid gray; margin-top: 1px; height: ".($Zg-$uh)."em;'></div></div>\n";}}}}echo'</div>
<p class="links"><a href="',h(ME."schema=".url_escape($ca)),'" id="schema-link">Permanent link</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST&&!$m){$l=array("auto_increment"=>'');foreach(array("type","routine","event","trigger")as$gm){if(support($gm))$l[$gm."s"]='';}save_settings(array_intersect_key($_POST+$l,array_flip(array("output","format","db_style","schema_style","table_style","data_style"))+$l),"adminer_export");$wa=(DB==""||$_GET["ns"]==="");$S=array_flip((array)$_POST["tables"])+array_flip((array)$_POST["data"]);$Od=dump_headers((count($S)==1?key($S):DB),($wa||count($S)>1));$bg=preg_match('~sql~',$_POST["format"]);if($bg){echo"-- Adminer ".VERSION." ".get_driver(DRIVER)." ".str_replace("\n"," ",connection()->server_info)." dump\n\n";if(JUSH=="sql"){echo"SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
".($_POST["data_style"]?"SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
":"")."
";connection()->query("SET time_zone = '+00:00'");connection()->query("SET sql_mode = ''");}}$bm=$_POST["db_style"];$j=array(DB);if(DB==""){$j=$_POST["databases"];if(is_string($j))$j=explode("\n",rtrim(str_replace("\r","",$j),"\n"));}foreach((array)$j
as$k){adminer()->dumpDatabase($k);if(connection()->select_db($k)){if($bg&&$bm)echo
use_sql($k,$bm).";\n\n";foreach(($_GET["ns"]===""?(array)$_POST["schemas"]:(DB!=""||!support("scheme")?array(""):adminer()->schemas()))as$K){if($K!=""){if(DB==""&&information_schema(DB,$K))continue;set_schema($K);}if($bg&&$_POST["schema_style"]&&function_exists('Adminer\use_schema_sql'))echo
use_schema_sql($_GET["ns"],$_POST["schema_style"]).";\n\n";$Xl=($_POST["table_style"]||$_POST["data_style"]?table_status('',true):array());$Nd=array();$rc=array();foreach($Xl
as$A=>$R){if($wa||in_array($A,(array)$_POST["tables"]))$Nd[$A]=$R;if($wa||in_array($A,(array)$_POST["data"]))$rc[$A]=$R;}if($bg){if($_POST["table_style"]=="DROP+CREATE"&&function_exists('Adminer\drop_sql'))echo
drop_sql($Nd);if($_POST["data_style"]=="TRUNCATE+INSERT"&&function_exists('Adminer\truncate_all_sql')){$kn=array();foreach($rc
as$A=>$R){if(!is_view($R)&&!($_POST["table_style"]=="DROP+CREATE"&&isset($Nd[$A])))$kn[]=$A;}echo
truncate_all_sql($kn);}$Vi="";if($_POST["types"]){foreach(types()as$s=>$T){$Cc=type_definition($s);$fi=($Cc["kind"]=='d'?"DOMAIN":"TYPE");if($Cc["definition"])$Vi
.=($bm!='DROP+CREATE'?"DROP $fi IF EXISTS ".table($T).";;\n":"")."CREATE $fi ".table($T)." $Cc[definition];\n\n";else$Vi
.="-- Could not export type ".preg_replace('~[\r\n]+~',' ',$T)."\n\n";}}if($_POST["routines"]){foreach(routines()as$I){$A=$I["ROUTINE_NAME"];$Ik=$I["ROUTINE_TYPE"];$If=routine($I["SPECIFIC_NAME"],$Ik);if($If["definition"]!=""){$i=create_routine($Ik,array("name"=>$A)+$If);set_utf8mb4($i);$Vi
.=($bm!='DROP+CREATE'?"DROP $Ik IF EXISTS ".table($A).";;\n":"")."$i;\n\n";}else$Vi
.="-- Could not export ".strtolower($Ik)." ".preg_replace('~[\r\n]+~',' ',$A)."\n\n";}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$I){$i=remove_definer(get_val("SHOW CREATE EVENT ".idf_escape($I["Name"]),3));set_utf8mb4($i);$Vi
.=($bm!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($I["Name"]).";;\n":"")."$i;;\n\n";}}echo($Vi&&JUSH=='sql'?"DELIMITER ;;\n\n$Vi"."DELIMITER ;\n\n":$Vi);}if($_POST["table_style"]||$_POST["data_style"]){$Zn=array();foreach($Xl
as$A=>$R){$Q=array_key_exists($A,$Nd);$pc=array_key_exists($A,$rc);if($Q||$pc){$Sm=null;if($Od=="tar"){$Sm=new
TmpFile;ob_start(array($Sm,'write'),1e5);}adminer()->dumpTable($A,($Q?$_POST["table_style"]:""),(is_view($R)?2:0));if(is_view($R))$Zn[]=$A;elseif($pc){$o=fields($A);$L=array("*");$cc=convert_fields($o,$o);if($cc)$L[]=substr($cc,2);adminer()->dumpData($A,$_POST["data_style"],"",$L);}if($bg&&$_POST["triggers"]&&$Q&&($jn=trigger_sql($A)))echo"\nDELIMITER ;;\n$jn\nDELIMITER ;\n";if($Od=="tar"){ob_end_flush();tar_file((DB!=""?"":"$k/")."$A.csv",$Sm);}elseif($bg)echo"\n";}}if($bg&&$_POST["table_style"]&&function_exists('Adminer\foreign_keys_sql')){foreach($Nd
as$A=>$R){if(!is_view($R))echo
foreign_keys_sql($A);}}if($bg){foreach($Zn
as$Yn)adminer()->dumpTable($Yn,$_POST["table_style"],1);}if($Od=="tar")echo
pack("x1024");}}}}adminer()->dumpFooter();exit;}page_header('Export',$m,($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),h(DB));echo'
<form action="" method="post">
<table class="layout">
';$vc=array('','USE','DROP+CREATE','CREATE');$Tk=(JUSH=="mssql"?array('','DROP+CREATE','CREATE'):$vc);$xm=array('','DROP+CREATE','CREATE');$qc=array('','TRUNCATE+INSERT','INSERT');if(JUSH=="sql")$qc[]='INSERT+UPDATE';$I=get_settings("adminer_export");if(!$I)$I=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"schema_style"=>"","table_style"=>"DROP+CREATE","data_style"=>"INSERT");echo"<tr><th>".'Output'."<td>".html_radios("output",adminer()->dumpOutput(),$I["output"])."\n","<tr><th>".'Format'."<td>".html_radios("format",adminer()->dumpFormat(),$I["format"])."\n",(JUSH=="sqlite"?"":"<tr><th>".'Database'."<td>".html_select('db_style',$vc,$I["db_style"]).(support("type")?checkbox("types",1,$I["types"],'User types'):"").(support("routine")?checkbox("routines",1,$I["routines"],'Routines'):"").(support("event")?checkbox("events",1,$I["events"],'Events'):"")),(function_exists('Adminer\use_schema_sql')?"<tr><th>".'Schema'."<td>".html_select('schema_style',$Tk,$I["schema_style"]):""),"<tr><th>".'Tables'."<td>".html_select('table_style',$xm,$I["table_style"]).checkbox("auto_increment",1,$I["auto_increment"],'Auto Increment').(support("trigger")?checkbox("triggers",1,$I["triggers"],'Triggers'):""),"<tr><th>".'Data'."<td>".html_select('data_style',$qc,$I["data_style"]),'</table>
';adminer()->dumpPrint();echo'<p><input type=\'submit\' value=\'Export\'>
',input_token(),'
<table',on('click','dumpClick'),'>
';$Nj=array();if($_GET["ns"]===""&&support("scheme")){echo"<thead><tr><th style='text-align: left;'>","<label class='block'><input type='checkbox' id='check-schemas' checked class='jsonly' title='".'All'."'".on('click','formCheck','^schemas\[').">".'Schema'."</label>","<tbody>\n";foreach(adminer()->schemas()as$K){if(!information_schema(DB,$K))echo"<tr><td>".checkbox("schemas[]",$K,true,$K,"","block")."\n";}}elseif(DB!=""){$sb=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$sb class='jsonly' title='".'All'."'".on('click','formCheck','^tables\[').">".'Table'."</label>","<th style='text-align: right;'><label class='block'>".'Data'."<input type='checkbox' id='check-data'$sb class='jsonly' title='".'All'."'".on('click','formCheck','^data\[')."></label>","<tbody>\n";$Zn="";$_m=tables_list();foreach($_m
as$A=>$T){$Mj=preg_replace('~_.*~','',$A);$sb=($a==""||$a==(substr($a,-1)=="%"?"$Mj%":$A));$Tj="<tr><td>".checkbox("tables[]",$A,$sb,$A,"","block");if($T!==null&&!preg_match('~table~i',$T))$Zn
.="$Tj\n";else
echo"$Tj<td align='right'><label class='block'><span id='Rows-".h($A)."'></span>".checkbox("data[]",$A,$sb)."</label>\n";$Nj[$Mj]++;}echo$Zn;if($_m)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}else{$j=adminer()->databases();echo"<thead><tr><th style='text-align: left;'>","<label class='block'>".($j?"<input type='checkbox' id='check-databases'".($a==""?" checked":"")." class='jsonly' title='".'All'."'".on('click','formCheck','^databases\[').">":"").'Database'."</label>","<tbody>\n";if($j){foreach($j
as$k){if(!information_schema($k)){$Mj=preg_replace('~_.*~','',$k);echo"<tr><td>".checkbox("databases[]",$k,$a==""||$a=="$Mj%",$k,"","block")."\n";$Nj[$Mj]++;}}}else
echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}echo'</table>
</form>
';$he=true;foreach($Nj
as$w=>$W){if($w!=""&&$W>1){echo($he?"<p>":" ")."<a href='".h(ME)."dump=".url_escape("$w%")."'>".h($w)."</a>";$he=false;}}}elseif(isset($_GET["privileges"])){page_header('Privileges');echo'<p class="links"><a href="'.h(ME).'user=">'.'Create user'."</a>";$G=connection()->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$He=$G;if(!$G)$G=connection()->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");echo"<form action=''><p>\n";hidden_fields_get();echo
input_hidden("db",DB),($He?"":input_hidden("grant")),"<table class='odds'>\n","<thead><tr><th>".'Username'."<th>".'Server'."<td class='hover'><tbody>\n";while($I=$G->fetch_assoc())echo'<tr><td>'.h($I["User"]),"<td>".h($I["Host"]),'<td class="hover"><a href="'.h(ME.'user='.url_escape($I["User"]).'&host='.url_escape($I["Host"])).'">'.'Edit'."</a>\n";if(!$He||DB!="")echo"<tr><td><input name='user' autocapitalize='off'>","<td><input name='host' value='localhost' autocapitalize='off'>","<td class='hover'><input type='submit' value='".'Edit'."'>\n";echo"</table>\n","</form>\n";}elseif(isset($_GET["sql"])){if(!$m&&$_POST["export"]){save_settings(array("output"=>$_POST["output"],"format"=>$_POST["format"]),"adminer_import");dump_headers("sql");if($_POST["format"]=="sql")echo"$_POST[query]\n";else{adminer()->dumpTable("","");adminer()->dumpData("","table",$_POST["query"]);adminer()->dumpFooter();}exit;}if(!$m&&$_POST["val"]){$ra=0;$cm=true;$jb=array();$Qk=0;foreach($_POST["val"]as$J)$Qk+=count($J);$Wa=$Qk>1&&driver()->begin();foreach($_POST["val"]as$nm=>$J){$Q=bracket_escape($nm,true);$o=fields($Q);$om=indexes($Q);foreach($J
as$t=>$I){parse_str(bracket_escape($t,true),$Z);$un=array();foreach($Z["where"]as$w=>$W)$un[bracket_escape($w,true)]=$W;if(!$o||$Z["null"]||array_diff_key($un,$o)||!unique_array($un,$om)){$cm=false;break
2;}$N=array();$L=array();foreach($I
as$jg=>$W){$w=bracket_escape($jg,true);$n=idx($o,$w);if(!$n){$cm=false;break
3;}$N[idf_escape($w)]=(preg_match('~char|text~',$n["type"])||$W!=""?adminer()->processInput($n,$W):"NULL");$L[$jg]=$w;}$hk=where($Z,$o);if(!driver()->update($Q,$N," WHERE $hk",0," ")){$cm=false;break
2;}$ra+=connection()->affected_rows;$e=array();foreach($L
as$w)$e[]=idf_escape($w);$Dn=driver()->select($Q,$e,array($hk),$e);$Th=($Dn?$Dn->fetch_row():array());$fg=0;foreach($L
as$jg=>$w){$n=$o[$w];$am=array('type'=>(preg_match('~binary~',$n["type"])?'blob':$n["type"]));$jb["val[$nm][$t][$jg]"]=select_value(idx($Th,$fg++),"",$am,null);}}}if($Wa&&$cm)$cm=driver()->commit();queries_redirect(null,lang_format(array('%d item has been affected.','%d items have been affected.'),$ra),$cm);if($Wa&&!$cm)driver()->rollback();page_headers();page_messages($m);foreach($jb
as$A=>$W)echo"<div data-name='".h($A)."' hidden>$W</div>\n";exit;}restart_session();$kf=&get_session("queries");$jf=&$kf[DB];if(!$m&&$_POST["clear"]){$jf=array();redirect(remove_from_uri("history"));}stop_session();$pa=get_settings("adminer_import");if($_POST&&$pa)save_settings($pa,"adminer_import");page_header((isset($_GET["import"])?'Import':'SQL command'),$m);$Gg=driver()->lineComment();$zd=0;function
query_error_line($Im,$B,$F,$Fg){return
substr_count(substr($Im,0,$B),"\n")+($Fg?:substr_count(substr($F,0,strspn($F," \t\r\n")),"\n")+1);}if(!$m&&$_POST&&!(isset($_GET["import"])&&adminer()->importProcess())){$Ec=driver()->delimiter;$ve=false;if(!isset($_GET["import"]))$F=$_POST["query"];elseif($_POST["webfile"]){$Nl=adminer()->importServerPath();$ve=@fopen((file_exists($Nl)?$Nl:"compress.zlib://$Nl.gz"),"rb");$F=($ve?fread($ve,1e6):false);}else$F=get_file("sql_file",true,$Ec);if(is_string($F)){if(($hh=ini_bytes("memory_limit"))!="-1")ini_set("memory_limit",max($hh,strval(2*strlen($F)+memory_get_usage()+8e6)));if($F!=""&&strlen($F)<1e6){$ck=$F.(preg_match("~$Ec\\s*\$~",$F)?"":$Ec);if(!$jf||first(end($jf))!=$ck){restart_session();$jf[]=array($ck,time());set_session("queries",$kf);stop_session();}}$Kl="(?:\\s|\xEF\xBB\xBF|/\\*[\s\S]*?\\*/|(?:$Gg)[^\n]*\n?|--\r?\n)";$B=0;$gk=0;$qd=true;$ec=false;$oh=false;$h=connect();if($h&&DB!=""){$h->select_db(DB);if($_GET["ns"]!="")set_schema($_GET["ns"],$h);}$Ib=0;$Ad=array();$fj='[\'"'.(JUSH=="sql"?'`':(JUSH=="sqlite"?'`[':(JUSH=="mssql"?'[':''))).']|/\*|'.$Gg.'|$'.(JUSH=="pgsql"?'|\$([a-zA-Z]\w*)?\$':'');$Xm=microtime(true);while($F!=""){if(!$B&&preg_match("~^$Kl*+DELIMITER\\s+(\\S+)~i",$F,$_)){$Ec=preg_quote($_[1]);$F=substr($F,strlen($_[0]));$gk+=strlen($_[0]);}elseif(!$B&&JUSH=='pgsql'&&preg_match("~^($Kl*+COPY\\s+)[^;]+\\s+FROM\\s+stdin;~i",$F,$_)){$Ec="\n\\\\\\.\r?\n";$ec=true;$B=strlen($_[0]);}elseif(!$B&&JUSH=='pgsql'&&preg_match("~^$Kl*+\\\\connect[ \t][^\n]*~",$F,$_)){$Ec="\n";$oh=true;$B=strlen($_[0]);}else{preg_match("($Ec\\s*|$fj)",$F,$_,PREG_OFFSET_CAPTURE,$B);list($te,$E)=$_[0];if(!$te&&$ve&&!feof($ve))$F
.=fread($ve,1e5);else{if(!$te&&rtrim($F)=="")break;$B=$E+strlen($te);if($te&&!preg_match("(^$Ec)",$te)){$gb=driver()->hasCStyleEscapes()||(JUSH=="pgsql"&&($E>0&&strtolower($F[$E-1])=="e"));$wj=($te=='/*'?'\*/':($te=='['?']':(preg_match("~^(?:$Gg)~",$te)?"\n":preg_quote($te).($gb?'|\\\\.':''))));while(preg_match("($wj|\$)s",$F,$_,PREG_OFFSET_CAPTURE,$B)){$Rk=$_[0][0];if(!$Rk&&$ve&&!feof($ve))$F
.=fread($ve,1e5);else{$B=$_[0][1]+strlen($Rk);if(!$Rk||$Rk[0]!="\\")break;}}}else{$ck=substr($F,0,$E+($ec?3:0));$F=substr($F,$B);$dk=$gk;$gk+=$B;$B=0;if($ec||$oh){$Ec=driver()->delimiter;$ec=false;$oh=false;}$Ab="<code class='jush-".JUSH."'>".adminer()->sqlCommandQuery($ck)."</code>";if(preg_match("~^$Kl*+\$~",$ck)&&!preg_match('~/\*M?!~',$ck)){echo($_POST["only_errors"]?"":"<pre>$Ab</pre>\n");continue;}$qd=false;$Ib++;$Tj="<pre id='sql-$Ib'>$Ab</pre>\n";if(JUSH=="sqlite"&&preg_match("~^$Kl*+(ATTACH|VACUUM\\b.*\\bINTO)\\b~is",$ck,$_)!==0){echo$Tj,"<p class='error'>".sprintf('%s queries are not supported.',preg_match('~ATTACH~i',$_[1])?'ATTACH':'VACUUM INTO')."\n";$Ad[]=" <a href='#sql-$Ib'>$Ib</a>";if(!$zd&&!isset($_GET["import"]))$zd=query_error_line($_POST["query"],$dk,$ck,0);if($_POST["error_stops"])break;}else{if(!$_POST["only_errors"]){echo$Tj;ob_flush();flush();}$Tl=microtime(true);if(JUSH=="pgsql"&&preg_match("~^$Kl*+\\\\connect\\s+(\"(?:[^\"]|\"\")+\"|[^\\s\";]+)\\s*;*\\s*\$~",$ck,$_)){$k=idf_unescape($_[1]);if(connection()->select_db($k)){if($h)$h->select_db($k);echo($_POST["only_errors"]?"":"<p class='message'>".'Database'.": ".h($k)." <span class='time'>(".format_time($Tl).")</span>\n");continue;}}elseif(connection()->multi_query($ck)&&$h&&preg_match("~^$Kl*+USE\\b~i",$ck))$h->query($ck);do{$G=connection()->store_result();if(connection()->error){echo($_POST["only_errors"]?$Tj:""),"<p class='error'>".'Error in query'.(connection()->errno?" (".connection()->errno.")":"").": ".adminer()->error()."\n";$Ad[]=" <a href='#sql-$Ib'>$Ib</a>";if(!$zd&&!isset($_GET["import"]))$zd=query_error_line($_POST["query"],$dk,$ck,driver()->errorLine($ck));if($_POST["error_stops"])break
2;}else{$z=ME."sql=".url_escape(trim($ck));$Lm=" <span class='time'>(".format_time($Tl).")</span>".(strlen($z)<adminer()->sqlUrlLength()-100?" <a href='".h($z)."'>".'Edit'."</a>":"");$ra=connection()->affected_rows;$do=($_POST["only_errors"]?"":driver()->warnings());$eo="warnings-$Ib";if($do)$Lm
.=", <a href='#$eo' class='toggle'>".'Warnings'."</a>";$Ld="";$Md="explain-$Ib";if(is_object($G)){$y=$_POST["limit"];$di=$y;$hd=!$_POST["only_errors"];if($hd)echo"<form action='' method='post'>\n";$Li=print_select_result($G,$h,array(),$di,$hd);if(!$_POST["only_errors"]){$di=max($G->num_rows,$di);echo"<p class='sql-footer'>".($di?($y&&$di>$y?sprintf('%d / ',$y):"").lang_format(array('%d row','%d rows'),$di):""),$Lm;if($h&&preg_match("~^($Kl|\\()*+SELECT\\b~i",$ck)&&($Ld=adminer()->explain($h,$ck,$Li))!="")echo", <a href='#$Md' class='toggle'>Explain</a>";if($hd)echo", <input type='submit' name='save' value='".'Save'."' class='jsonly' disabled"." title='".'Ctrl+click on a value to modify it.'."'".on('click','sqlSave','Saving…').">";$s="export-$Ib";echo", <a href='#$s' class='toggle'>".'Export'."</a><span id='$s' class='hidden'>: ".html_select("output",adminer()->dumpOutput(),$pa["output"])." ".html_select("format",adminer()->dumpFormat(),$pa["format"]).input_hidden("query",$ck)."<input type='submit' name='export' value='".'Export'."'".($y?"":on('click','sqlExport')).">".input_token()."</span>\n"."</form>\n";}}else{if(preg_match("~^$Kl*+(CREATE|DROP|ALTER)$Kl++(DATABASE|SCHEMA)\\b~i",$ck)){restart_session();set_session("dbs",null);stop_session();}if(!$_POST["only_errors"])echo"<p class='message' title='".h(connection()->info)."'>".lang_format(array('Query executed OK, %d row affected.','Query executed OK, %d rows affected.'),$ra)."$Lm\n";}echo($do?"<div id='$eo' class='hidden'>\n$do</div>\n":""),($Ld!=""?"<div id='$Md' class='hidden explain'>\n$Ld</div>\n":"");}$Tl=microtime(true);}while(connection()->next_result());}}}}}if($qd)echo"<p class='message'>".'No commands to execute.'."\n";else{$zf=connection()->inTransaction();driver()->rollback();if($zf)echo"<pre><code class='jush-".JUSH."'>ROLLBACK".(JUSH=="mssql"?" TRANSACTION":"")." -- Adminer</code></pre>\n";if($_POST["only_errors"])echo"<p class='message'>".lang_format(array('%d query executed OK.','%d queries executed OK.'),$Ib-count($Ad))," <span class='time'>(".format_time($Xm).")</span>\n";elseif($Ad&&$Ib>1)echo"<p class='error'>".'Error in query'.": ".implode("",$Ad)."\n";}}else
echo"<p class='error'>".upload_error($F)."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form"';$En="";if(!isset($_GET["import"]))echo
on('submit','sqlSubmit',remove_from_uri("sql|limit|error_stops|only_errors|history"),adminer()->sqlUrlLength());else
echo
on_upload_progress($En);echo'>
';$Id="<input type='submit' value='".'Execute'."' title='Ctrl+Enter'>";if(!isset($_GET["import"])){$ck=$_GET["sql"];if($_POST)$ck=$_POST["query"];elseif($_GET["history"]=="all")$ck=$jf;elseif($_GET["history"]!="")$ck=idx($jf[$_GET["history"]],0);echo"<p>";textarea("query",$ck,20,80,JUSH,$zd);echo($_POST?"":script("qs('textarea').focus();")),"<p>";adminer()->sqlPrintAfter();echo"$Id\n",'Limit rows'.": <input type='number' name='limit' class='size' value='".h($_POST?$_POST["limit"]:$_GET["limit"])."'>\n";}else{$Re=(extension_loaded("zlib")?"[.gz]":"");echo"<fieldset><legend>".'File upload'."</legend><div>",($En?input_hidden(ini_get("session.upload_progress.name"),$En):""),"SQL$Re: ".file_input(" name='sql_file[]' multiple","\n$Id"),($En?" <progress class='jsonly hidden' max='1' value='0'></progress>":""),"</div></fieldset>\n";$wf=adminer()->importServerPath();if($wf)echo"<fieldset><legend>".'From server'."</legend><div>",sprintf('Webserver file %s',"<code>".h($wf)."$Re</code>")," <input type='submit' name='webfile' value='".'Run file'."'>","</div></fieldset>\n";adminer()->importPrint();echo"<p>";}echo
checkbox("error_stops",1,($_POST?$_POST["error_stops"]:isset($_GET["import"])||$_GET["error_stops"]),'Stop on error')."\n",checkbox("only_errors",1,($_POST?$_POST["only_errors"]:isset($_GET["import"])||$_GET["only_errors"]),'Show only errors')."\n",input_token();if(!isset($_GET["import"])&&$jf){print_fieldset("history",'History',$_GET["history"]!="");for($W=end($jf);$W;$W=prev($jf)){$w=key($jf);list($ck,$Lm,$md)=$W;echo'<div><a href="'.h(ME."sql=&history=$w").'" class="hover">'.'Edit'."</a>"." <span class='time' title='".@date('Y-m-d',$Lm)."'>".@date("H:i:s",$Lm)."</span>"." <code class='sql jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim(preg_replace("~^(?:$Gg).*~m",'',$ck))),80,"</code>").($md?" <span class='time'>($md)</span>":"")."</div>\n";}echo"<input type='submit' name='clear' value='".'Clear'."'>\n","<a href='".h(ME."sql=&history=all")."'>".'Edit all'."</a>\n","</div></fieldset>\n";}echo'</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$o=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$o):""):where($_GET,$o));$Bn=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($o
as$A=>$n){if((!$Bn&&!isset($n["privileges"]["insert"]))||adminer()->fieldName($n)=="")unset($o[$A]);}if($_POST&&!$m&&!isset($_GET["select"])){$Lg=relative_uri((string)$_POST["referer"]);if($_POST["insert"])$Lg=($Bn?null:relative_uri());elseif(!preg_match('~^.+&select=.+$~',$Lg))$Lg=ME."select=".url_escape($a);$v=indexes($a);$vn=unique_array($_GET["where"],$v);$hk="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($Lg,'Item has been deleted.',driver()->delete($a,$hk,$vn?0:1));else{$N=array();foreach($o
as$A=>$n){$W=process_input($n);if($W!==false&&$W!==null)$N[idf_escape($A)]=$W;}if($Bn){if(!$N)redirect($Lg);queries_redirect($Lg,'Item has been updated.',driver()->update($a,$N,$hk,$vn?0:1));if(is_ajax()){page_headers();page_messages($m);exit;}}else{$G=driver()->insert($a,$N);$wg=($G?last_id($G):0);queries_redirect($Lg,sprintf('Item%s has been inserted.',($wg?" $wg":"")),$G);}}}$I=null;$F="";$Lm="";if($Z){$L=array();$dl=array("*");foreach($o
as$A=>$n){if(isset($n["privileges"]["select"])){$Fa=($_POST["clone"]&&$n["auto_increment"]?"''":convert_field($n));$d=($Fa?"$Fa AS ":"").idf_escape($A);$L[]=$d;if($Fa)$dl[]=$d;}}$I=array();if(!support("table")){$L=array("*");$dl=$L;}if($L){$Tl=microtime(true);$G=driver()->select($a,$L,array($Z),$L,array(),(isset($_GET["select"])?2:1));$F=str_replace("SELECT ".implode(", ",$L),"SELECT ".implode(", ",$dl),driver()->query);$Lm=format_time($Tl);if(!$G)$m=adminer()->error();else{$I=$G->fetch_assoc();if(!$I)$I=false;}if(isset($_GET["select"])&&(!$I||$G->fetch_assoc()))$I=null;}}if(!$o&&driver()->primary!=""){if(!$Z){$G=driver()->select($a,array("*"),array(),array("*"));$I=($G?$G->fetch_assoc():false);if(!$I)$I=array(driver()->primary=>"");}if($I){foreach($I
as$w=>$W){if(!$Z)$I[$w]=null;$o[$w]=array("field"=>$w,"null"=>($w!=driver()->primary),"auto_increment"=>($w==driver()->primary));}}}if($_POST["save"]){$Hj=array();foreach((array)$_POST["fields"]as$w=>$W)$Hj[bracket_escape($w,true)]=$W;$I=$Hj+($I?$I:array());}edit_form($a,$o,$I,$Bn,$m,$F,$Lm);}elseif(isset($_GET["create"])){function
referencable_primary($gl){$H=array();foreach(table_status('',true)as$rm=>$Q){if($rm!=$gl&&!$Q["dependent"]&&fk_support($Q)){foreach(fields($rm)as$n){if($n["primary"]){if($H[$rm]){unset($H[$rm]);break;}$H[$rm]=$n;}}}}return$H;}$a=$_GET["create"];$kj=driver()->partitionBy;$pj=($kj&&$a!=""?driver()->partitionsInfo($a):array());$pk=referencable_primary($a);$re=array();foreach($pk
as$rm=>$n)$re[str_replace("`","``",$rm)."`".str_replace("`","``",$n["field"])]=$rm;$Pi=array();$R=array();$ai=false;if($a!=""){$Pi=fields($a);$R=table_status1($a);$ai=(count($R)<2);}$za=($a==""||driver()->supportsAlterTable($R));$I=$_POST;$I["fields"]=(array)$I["fields"];if($I["auto_increment_col"])$I["fields"][$I["auto_increment_col"]]["auto_increment"]=true;if($_POST&&!$m)save_settings(array("comments"=>$_POST["comments"],"defaults"=>$_POST["defaults"]));if($_POST&&!process_fields($I["fields"])&&!$m){if($_POST["drop"])queries_redirect(substr(ME,0,-1),'Table has been dropped.',drop_tables(array($a)));else{$o=array();$xa=array();$In=false;$pe=array();$Oi=reset($Pi);$ta=" FIRST";foreach($I["fields"]as$n){$q=$re[$n["type"]];$mn=($q!==null?$pk[$q]:$n);if($n["field"]!=""){if(!$n["generated"])$n["default"]=null;$n["default_sql"]=($n["generated"]=="DEFAULT SQL");$Yj=process_field($n,$mn);$xa[]=array($n["orig"],$Yj,$ta);if(!$Oi||$Yj!==process_field($Oi,$Oi)){$o[]=array($n["orig"],$Yj,$ta);if($n["orig"]!=""||$ta||(JUSH=="sqlite"&&$n["comment"]!=""))$In=true;}if($q!==null)$pe[idf_escape($n["field"])]=($a!=""&&JUSH!="sqlite"?"ADD":" ").format_foreign_key(array('table'=>$re[$n["type"]],'source'=>array($n["field"]),'target'=>array($mn["field"]),'on_delete'=>$n["on_delete"],),object_name("FOREIGN",trim($I["name"]),array($n["field"])));$ta=" AFTER ".idf_escape($n["field"]);}elseif($n["orig"]!=""){$In=true;$o[]=array($n["orig"]);}if($n["orig"]!=""){$Oi=next($Pi);if(!$Oi)$ta="";}}$nj=array();if(in_array($I["partition_by"],$kj)){foreach($I
as$w=>$W){if(preg_match('~^partition~',$w))$nj[$w]=$W;}foreach($nj["partition_names"]as$w=>$A){if($A==""){unset($nj["partition_names"][$w]);unset($nj["partition_values"][$w]);}}$nj["partition_names"]=array_values($nj["partition_names"]);$nj["partition_values"]=array_values($nj["partition_values"]);if($nj==$pj)$nj=array();}elseif(preg_match("~partitioned~",$R["Create_options"]))$nj=null;$jh='Table has been altered.';if($a==""){cookie("adminer_engine",$I["Engine"]);$jh='Table has been created.';}$A=trim($I["name"]);$Lg=ME.(support("table")?"table=":"select=").url_escape($A);$f=($I["Comment"]!=$R["Comment"]?$I["Comment"]:null);$td=($I["Engine"]&&$I["Engine"]!=$R["Engine"]?$I["Engine"]:"");$G=alter_table($a,$A,(JUSH=="sqlite"&&($In||$pe||$f!==null||$td)?$xa:$o),$pe,$f,$td,($I["Collation"]&&$I["Collation"]!=$R["Collation"]?$I["Collation"]:""),($I["Auto_increment"]!=""?number($I["Auto_increment"]):""),$nj);if($G&&!Queries::$queries&&$a!=""&&!$o&&!$pe)redirect($Lg);queries_redirect($Lg,$jh,$G);}}$Vl=($a!=""?"alter":"create");page_header(($a!=""?'Alter table':'Create table'),$m,array("table"=>$a),h($a),$ai,doc_link(array('sql'=>"$Vl-table.html",'mariadb'=>($a!=""?"$Vl-table":""),'pgsql'=>"sql-$Vl"."table.html",'cockroach'=>"$Vl-table",'mssql'=>"t-sql/statements/$Vl-table-transact-sql",'sqlite'=>"lang_{$Vl}table.html",'oracle'=>"sqlrf/".strtoupper($Vl)."-TABLE.html",)));if(!$_POST){$qn=driver()->types();$I=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($qn["int"])?"int":(isset($qn["integer"])?"integer":"")),"on_update"=>"")),"partition_names"=>array(""),);if($a!=""){$I=$R;$I["name"]=$a;$I["fields"]=array();if(!$_GET["auto_increment"])$I["Auto_increment"]="";foreach($Pi
as$n){if($n["generated"])$n["default"]=ltrim($n["default"]);$n["generated"]=$n["generated"]?:(isset($n["default"])?($n["default_sql"]?"DEFAULT SQL":"DEFAULT"):"");$I["fields"][]=$n;}if($kj){$I+=$pj;$I["partition_names"][]="";$I["partition_values"][]="";}}}$Eb=flat_collations();$ud=driver()->engines();foreach($ud
as$td){if(!strcasecmp($td,$I["Engine"])){$I["Engine"]=$td;break;}}$Ug=max_input_vars(12,20);if($Ug){$gf=(count($I["fields"])>$Ug?"":" hidden");echo"<p".($gf?" id='max-fields' data-columns='$Ug'":"")." class='error$gf'>".max_input_vars_error()."\n";}echo'
<form action="" method="post" id="form">
<p>
';if(support("columns")||$a==""){echo'Table name'.": <input name='name'".($a==""&&!$_POST?" autofocus":"")." data-maxlength='64' value='".h($I["name"])."' autocapitalize='off'>\n",(!$za?h($R["Engine"])."\n":($ud?html_select("Engine",array(""=>"(".'engine'.")")+$ud,$I["Engine"],on('change','helpClose').on_help_value())."\n":""));if($Eb)echo"<datalist id='collations'>".optionlist($Eb)."</datalist>\n",(preg_match("~sqlite|mssql~",JUSH)?"":"<input list='collations' name='Collation' value='".h($I["Collation"])."' placeholder='(".'collation'.")'>\n");echo"<input type='submit' value='".'Save'."'>\n";}if(support("columns")&&$za){echo"<div class='scrollable'>\n","<table id='edit-fields' class='nowrap'>\n";edit_fields($I["fields"],$Eb,"TABLE",$re);echo"</table>\n",script("editFields();"),"</div>\n<p>\n",'Auto Increment'.": <input type='number' name='Auto_increment' class='size' value='".h($I["Auto_increment"])."'>\n",checkbox("defaults",1,($_POST?$_POST["defaults"]:get_setting("defaults")),'Default values',on('click','columnShowClick',6),"jsonly");$Lb=($_POST?$_POST["comments"]:get_setting("comments"));if(support("comment")){echo
checkbox("comments",1,$Lb,'Comment',on('click','editingCommentsClick',true),"jsonly").' ';$c=" name='Comment' data-maxlength='".(min_version(5.5)?2048:60)."'".($Lb?"":" class='hidden'");echo
adminer()->commentInput('TABLE',$c,$I["Comment"]);}echo'<p>
<input type=\'submit\' value=\'Save\'>
';}echo'
';if($a!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$a)),'>
';if($kj&&(JUSH=='sql'||$a=="")){$lj=preg_match('~RANGE|LIST~',$I["partition_by"]);print_fieldset("partition",'Partition by',$I["partition_by"]);echo"<p>".html_select("partition_by",array_merge(array(""),$kj),$I["partition_by"],on('change','partitionByChange').on_help_value('.','PARTITION BY $&'))."\n","(<input name='partition' value='".h($I["partition"])."'>)\n",'Partitions'.": <input type='number' name='partitions' class='size".($lj||!$I["partition_by"]?" hidden":"")."' value='".h($I["partitions"])."'>\n","<table id='partition-table'".($lj?"":" class='hidden'").">\n","<thead><tr><th>".'Partition name'."<th>".'Values'."<tbody>\n";foreach($I["partition_names"]as$w=>$W)echo'<tr>','<td><input name="partition_names[]" value="'.h($W).'" autocapitalize="off"'.($w==count($I["partition_names"])-1?on('input','partitionNameChange'):'').'>','<td><input name="partition_values[]" value="'.h(idx($I["partition_values"],$w)).'">';echo"</table>\n</div></fieldset>\n";}echo
input_token(),'</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$Ef=array("PRIMARY","UNIQUE","INDEX");$R=table_status1($a,true);$Bf=driver()->indexAlgorithms($R);if(preg_match('~MyISAM|M?aria'.(min_version(5.6,'10.0.5')?'|InnoDB':'').'~i',$R["Engine"]))$Ef[]="FULLTEXT";if(preg_match('~MyISAM|M?aria'.(min_version(5.7,'10.2.2')?'|InnoDB':'').'~i',$R["Engine"]))$Ef[]="SPATIAL";if(min_version('',11.7)&&preg_match('~MyISAM|InnoDB~i',$R["Engine"]))$Ef[]="VECTOR";$v=indexes($a);$o=fields($a);$Rj=array();if(JUSH=="mongo"){$Rj=$v["_id_"];unset($Ef[0]);unset($v["_id_"]);}$I=$_POST;if($I)save_settings(array("index_options"=>$I["options"]));if($_POST&&!$m&&!$_POST["add"]&&!$_POST["drop_col"]){$b=array();foreach($I["indexes"]as$u){$A=$u["name"];if(in_array($u["type"],$Ef)){$e=array();$Cg=array();$Ic=array();$zi=array();$Cf=(support("partial_indexes")?$u["partial"]:"");$Af=(in_array($u["algorithm"],$Bf)?$u["algorithm"]:"");$N=array();ksort($u["columns"]);foreach($u["columns"]as$w=>$d){if($d!=""){$x=idx($u["lengths"],$w);$Gc=idx($u["descs"],$w);$yi=idx($u["opclasses"],$w);$N[]=($o[$d]?idf_escape($d):$d).($x?"(".(+$x).")":"").($yi!=""?" ".idf_escape($yi):"").($Gc?" DESC":"");$e[]=$d;$Cg[]=($x?:null);$Ic[]=$Gc;$zi[]="$yi";}}$Jd=$v[$A];if($Jd){ksort($Jd["columns"]);ksort($Jd["lengths"]);ksort($Jd["descs"]);if($u["type"]==$Jd["type"]&&array_values($Jd["columns"])===$e&&(!$Jd["lengths"]||array_values($Jd["lengths"])===$Cg)&&array_values($Jd["descs"])===$Ic&&(!$Jd["opclasses"]||array_values($Jd["opclasses"])===$zi)&&$Jd["partial"]==$Cf&&(!$Bf||$Jd["algorithm"]==$Af)&&!$Jd["invalid"]){unset($v[$A]);continue;}}if($e)$b[]=array($u["type"],$A,$N,$Af,$Cf);}}foreach($v
as$A=>$Jd)$b[]=array($Jd["type"],$A,"DROP");if(!$b)redirect(ME."table=".url_escape($a));queries_redirect(ME."table=".url_escape($a),'Indexes have been altered.',alter_indexes($a,$b));}page_header('Indexes',$m,array("table"=>$a),h($a),false,doc_link(array('sql'=>"create-index.html",'pgsql'=>"sql-createindex.html",'cockroach'=>"create-index",'mssql'=>"t-sql/statements/create-index-transact-sql",'sqlite'=>"lang_createindex.html",'oracle'=>"sqlrf/CREATE-INDEX.html",)));$ae=array_keys($o);if($_POST["add"]){foreach($I["indexes"]as$w=>$u){if($u["columns"][count($u["columns"])]!="")$I["indexes"][$w]["columns"][]="";}$u=end($I["indexes"]);if($u["type"]||array_filter($u["columns"],'strlen'))$I["indexes"][]=array("columns"=>array(1=>""));}if(!$I){foreach($v
as$w=>$u){$v[$w]["name"]=$w;$v[$w]["columns"][]="";}$v[]=array("columns"=>array(1=>""));$I["indexes"]=$v;}$Cg=(JUSH=="sql"||JUSH=="mssql");$zi=driver()->indexOpclasses();$_l=($_POST?$_POST["options"]:get_setting("index_options"));$Hh=array();foreach($Ef
as$T)$Hh[$T]=str_replace("{table}",$a,adminer()->namePattern($T));echo'
<form action="" method="post">
<div class="scrollable">
<table class="nowrap odds">
<thead><tr>
<th id="label-type">Index Type
';$uf=" class='idxopts".($_l?"":" hidden")."'";if($Bf)echo"<th id='label-algorithm'$uf>".'Algorithm'.doc_link(array('sql'=>'create-index.html#create-index-storage-engine-index-types','mariadb'=>'storage-engine-index-types/','pgsql'=>'indexes-types.html','cockroach'=>'create-index#parameters',));echo'<th><input type="submit" hidden>','Columns'.($Cg?"<span$uf> (".'length'.")</span>":"");if($Cg||support("descidx"))echo
checkbox("options",1,$_l,'Options',on('click','indexOptionsShow'),"jsonly")."\n";echo'<th id="label-name">Name
';if(support("partial_indexes"))echo"<th id='label-condition'$uf>".'Condition';echo'<td><noscript>',icon("plus","add[0]","+",'Add next'),'</noscript>
<tbody>
';if($Rj){echo"<tr><td>PRIMARY<td>";foreach($Rj["columns"]as$w=>$d)echo
select_input(" disabled",array_combine($ae,$ae),$d),"<label><input disabled type='checkbox'>".'descending'."</label> ";echo"<td><td>\n";}$fg=1;foreach($I["indexes"]as$u){if(!$_POST["drop_col"]||$fg!=key($_POST["drop_col"])){echo"<tr><td>".html_select("indexes[$fg][type]",array(-1=>"")+$Ef,$u["type"],on('change','indexesChangeType',$Hh),"label-type");if($Bf)echo"<td$uf>".html_select("indexes[$fg][algorithm]",array_merge(array(""),$Bf),$u['algorithm'],"","label-algorithm");echo"<td>";ksort($u["columns"]);$r=1;foreach($u["columns"]as$w=>$d){echo"<span>".select_input(" name='indexes[$fg][columns][$r]' title='".'Column'."'".on('change','indexesChangeColumn',$Hh),($o&&($d==""||$o[$d])?array_combine($ae,$ae):array()),$d)," <span$uf>",($Cg?"<input type='number' name='indexes[$fg][lengths][$r]' class='size' value='".h(idx($u["lengths"],$w))."' title='".'Length'."'>":"");if($zi){$yi=idx($u["opclasses"],$w);echo
html_select("indexes[$fg][opclasses][$r]",array(""=>"(".'operator class'.")")+array_combine($zi,$zi)+($yi!=""?array($yi=>$yi):array()),$yi),doc_link(array('pgsql'=>'indexes-opclass.html'));}echo(support("descidx")?checkbox("indexes[$fg][descs][$r]",1,idx($u["descs"],$w),'descending'):""),"<br>","</span></span>";$r++;}echo"<td><input name='indexes[$fg][name]' value='".h($u["name"])."' autocapitalize='off' aria-labelledby='label-name'>\n";if(support("partial_indexes"))echo"<td$uf><input name='indexes[$fg][partial]' value='".h($u["partial"])."' autocapitalize='off' aria-labelledby='label-condition'>\n";echo"<td>".icon("cross","drop_col[$fg]","x",'Remove',on('click','editingRemoveRow','indexes$1[type]')),(idx($v[$u["name"]],"invalid")?" <span class='error'>INVALID</span>":""),"\n";}$fg++;}echo'</table>
</div>
<p>
<input type=\'submit\' value=\'Save\'>
';if(support("concurrent_indexes"))echo
checkbox("concurrently",1,$_POST["concurrently"],"concurrently").doc_link(array('pgsql'=>'sql-createindex.html#SQL-CREATEINDEX-CONCURRENTLY'))."\n";echo
input_token(),'</form>
';}elseif(isset($_GET["database"])){$I=$_POST;if($_POST&&!$m&&!$_POST["add"]){$A=trim($I["name"]);if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),'Database has been dropped.',drop_databases(array(DB)));}elseif($A!==DB){if(DB!=""){$_GET["db"]=$A;queries_redirect(preg_replace('~\bdb=[^&]*&~','',ME)."db=".url_escape($A),'Database has been renamed.',rename_database($A,(string)$I["collation"]));}else{$j=explode("\n",str_replace("\r","",$A));$cm=true;$ug="";foreach($j
as$k){if(count($j)==1||$k!=""){if(!create_database($k,(string)$I["collation"]))$cm=false;$ug=$k;}}restart_session();set_session("dbs",null);queries_redirect(preg_replace('~&db=[^&]*~','',ME)."db=".url_escape($ug),'Database has been created.',$cm);}}else{if(!$I["collation"])redirect(substr(ME,0,-1));query_redirect("ALTER DATABASE ".idf_escape($A).(preg_match('~^[a-z0-9_]+$~i',$I["collation"])?" COLLATE $I[collation]":""),substr(ME,0,-1),'Database has been altered.');}}$Vl=(DB!=""?"alter":"create");page_header(DB!=""?'Alter database':'Create database',$m,array(),h(DB),false,doc_link(array('sql'=>"$Vl-database.html",'mariadb'=>(DB!=""?"":"$Vl-database"),'pgsql'=>"sql-$Vl"."database.html",'cockroach'=>"$Vl-database",'mssql'=>"t-sql/statements/$Vl-database-transact-sql",'oracle'=>"sqlrf/".strtoupper($Vl)."-USER.html",)));$Eb=collations();$A=DB;if($_POST)$A=$I["name"];elseif(DB!="")$I["collation"]=db_collation(DB,$Eb);elseif(JUSH=="sql"){foreach(get_vals("SHOW GRANTS")as$He){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~',$He,$_)&&$_[1]){$A=stripcslashes(idf_unescape("`$_[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add"]||strpos($A,"\n")?"<textarea autofocus name='name' rows='10' cols='40'>\n".h($A).'</textarea><br>':'<input name="name" autofocus value="'.h($A).'" data-maxlength="64" autocapitalize="off">')."\n",($Eb?"<datalist id='collations'>".optionlist(flat_collations())."</datalist>"."<input list='collations' name='collation' value='".h($I["collation"])."' placeholder='(".'collation'.")'>".doc_link(array('sql'=>"charset-charsets.html",'mariadb'=>"supported-character-sets-and-collations/",'mssql'=>"relational-databases/system-functions/sys-fn-helpcollations-transact-sql",)):"")."\n",'<input type=\'submit\' value=\'Save\'>
';if(DB!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm(sprintf('Drop %s?',DB)).">\n";elseif(!$_POST["add"]&&$_GET["db"]=="")echo
icon("plus","add[0]","+",'Add next')."\n";echo
input_token(),'</form>
';}elseif(isset($_GET["scheme"])){$I=$_POST;$f=(support("comment")&&$_GET["ns"]!=""?schema_comment($_GET["ns"]):"");if($_POST&&!$m){$z=preg_replace('~&ns=[^&]*~','',ME)."ns=";if($_POST["drop"])query_redirect("DROP SCHEMA ".idf_escape($_GET["ns"]),$z,'Schema has been dropped.');else{$A=trim($I["name"]);$z
.=url_escape($A);$Oh=(support("comment")?$I["comment"]:$f);$G=true;$jh='Schema has been altered.';if($_GET["ns"]==""){$G=queries("CREATE SCHEMA ".idf_escape($A));$jh='Schema has been created.';}elseif($_GET["ns"]!=$A)$G=queries("ALTER SCHEMA ".idf_escape($_GET["ns"])." RENAME TO ".idf_escape($A));elseif($Oh===$f)redirect($z);if($G&&$Oh!==$f)$G=set_schema_comment($A,$Oh);queries_redirect($z,$jh,$G);}}$Vl=($_GET["ns"]!=""?"alter":"create");page_header($_GET["ns"]!=""?'Alter schema':'Create schema',$m,array(),"",false,doc_link(array('pgsql'=>"sql-$Vl"."schema.html",'cockroach'=>"$Vl-schema",'mssql'=>"t-sql/statements/create-schema-transact-sql",)));if(!$I){$I["name"]=$_GET["ns"];$I["comment"]=$f;}echo'
<form action="" method="post">
<p><input name="name" autofocus value="',h($I["name"]),'" autocapitalize="off">
<input type=\'submit\' value=\'Save\'>
';if($_GET["ns"]!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm(sprintf('Drop %s?',$_GET["ns"])).">\n";echo(support("comment")?"<p>".'Comment'.": ".adminer()->commentInput('SCHEMA'," name='comment'",$I["comment"])."\n":""),input_token(),'</form>
';}elseif(isset($_GET["call"])){$ba=($_GET["name"]?:$_GET["call"]);$Nk=(isset($_GET["callf"])?"FUNCTION":"PROCEDURE");$Ik=routine($_GET["call"],$Nk);$cg=(idx($Ik["returns"],"type")=="trigger");page_header(($cg?'Triggers':'Call').": ".h($ba),$m,"#routines","",!$Ik,(isset($_GET["callf"])?"":doc_link(array('sql'=>"call.html",'pgsql'=>"sql-call.html",'cockroach'=>"call",'mssql'=>"t-sql/language-elements/execute-transact-sql",))));$xf=array();$Vi=array();foreach($Ik["fields"]as$r=>$n){if(substr($n["inout"],-3)=="OUT"&&JUSH=='sql')$Vi[$r]="@".idf_escape($n["field"])." AS ".idf_escape($n["field"]);if(!$n["inout"]||preg_match('~^(IN|OUTPUT)~',$n["inout"]))$xf[]=$r;}if(!$m&&$_POST){$hb=array();foreach($Ik["fields"]as$w=>$n){$W="";if(in_array($w,$xf)){$W=process_input($n);if($W===false)$W="''";if(isset($Vi[$w]))connection()->query("SET @".idf_escape($n["field"])." = $W");}if(isset($Vi[$w]))$hb[]="@".idf_escape($n["field"]);elseif(in_array($w,$xf))$hb[]=$W;}$Da=implode(", ",$hb);$F=(isset($_GET["callf"])||JUSH!="mssql"?(isset($_GET["callf"])?"SELECT ":"CALL ").(idx($Ik["returns"],"type")=="record"?"* FROM ":"").table($ba)."($Da)":"EXEC ".table($ba).($Da!=""?" $Da":""));$Tl=microtime(true);$G=connection()->multi_query($F);$ra=connection()->affected_rows;echo
adminer()->selectQuery($F,$Tl,!$G);if(!$G)echo"<p class='error'>".adminer()->error()."\n";else{$h=connect();if($h)$h->select_db(DB);do{$G=connection()->store_result();if(is_object($G))print_select_result($G,$h);else
echo"<p class='message'>".lang_format(array('Routine has been called, %d row affected.','Routine has been called, %d rows affected.'),$ra)." <span class='time'>".@date("H:i:s")."</span>\n";}while(connection()->next_result());if($Vi)print_select_result(connection()->query("SELECT ".implode(", ",$Vi)));}}if($cg){$jn=driver()->routineTriggers($_GET["call"]);if($jn){echo"<table>\n";foreach($jn
as$I){$z=preg_replace('~&ns=[^&]*~',"&ns=".url_escape($I["ns"]),ME);echo"<tr><th>".h($I["trigger"])."<td><a href='".h($z."table=".url_escape($I["table"])."#triggers")."'>".($I["ns"]!=$_GET["ns"]?"<b>".h($I["ns"])."</b>.":"").h($I["table"])."</a>"."<td class='hover'><a href='".h($z."trigger=".url_escape($I["table"])."&name=".url_escape($I["trigger"]))."'>".'Alter'."</a>\n";}echo"</table>\n";}}else{echo'<form action="" method="post">
';if($xf){echo"<table class='layout'>\n";foreach($xf
as$w){$n=$Ik["fields"][$w];$A=$n["field"];echo"<tr><th>".adminer()->fieldName($n);$X=idx($_POST["fields"],$A);if($X!=""){if($n["type"]=="set")$X=implode(",",$X);}input($n,$X,idx($_POST["function"],$A,""));echo"\n";}echo"</table>\n";}echo'<p>
<input type=\'submit\' value=\'Call\'>
',input_token(),'</form>
';}echo'
',adminer()->commentValue($Nk,$Ik['comment']);}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];$A=$_GET["name"];$I=$_POST;if($_POST&&!$m&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){if(!$_POST["drop"]){$I["source"]=array_filter($I["source"],'strlen');ksort($I["source"]);$Cm=array();foreach($I["source"]as$w=>$W)$Cm[$w]=$I["target"][$w];$I["target"]=$Cm;}$Wa=false;if(JUSH=="sqlite")$G=recreate_table($a,$a,array(),array(),array(" $A"=>($I["drop"]?"":" ".format_foreign_key($I))));else{$Ub=$I["constraint"];$b="ALTER TABLE ".table($a);$ad="$b DROP ".(JUSH=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($A?:'');if($I["drop"])$G=queries($ad);else{$ka=" ADD".format_foreign_key($I,$Ub);if($A=="")$G=queries($b.$ka);elseif(JUSH=="pgsql"||(JUSH=="sql"&&$Ub!=$A))$G=queries("$ad,$ka");else{$Wa=support("transaction_ddl")&&driver()->begin();$G=($Wa||idx(foreign_keys($a),$A)?queries($ad):true)&&queries($b.$ka)&&(!$Wa||driver()->commit());}}}queries_redirect(ME."table=".url_escape($a),($I["drop"]?'Foreign key has been dropped.':($A!=""?'Foreign key has been altered.':'Foreign key has been created.')),$G);if($Wa)driver()->rollback();if(!$I["drop"])$m='Source and target columns must have the same data type, there must be an index on the target columns and the referenced data must exist.';}$ai=false;if(!$_POST&&$A!=""){$re=foreign_keys($a);$I=idx($re,$A,array());$ai=!$I;}$Gh=str_replace("{table}",$a,adminer()->namePattern("FOREIGN"));$Jh="^".str_replace('\{columns\}','.*',preg_quote($Gh))."$";page_header(($A!=""?'Alter foreign key':'Create foreign key'),$m,array("table"=>$a),h($A!=""?$A:$a),$ai,doc_link(array('sql'=>"innodb-foreign-key-constraints.html",'mariadb'=>"foreign-keys/",'pgsql'=>"sql-createtable.html#SQL-CREATETABLE-PARMS-REFERENCES",'cockroach'=>"foreign-key",'mssql'=>"t-sql/statements/create-table-transact-sql",'oracle'=>"sqlrf/constraint.html",)));if($_POST){ksort($I["source"]);if($_POST["change"]||$_POST["change-js"])$I["target"]=array();else$I["source"][]="";}elseif($A!=""){$I["source"][]="";$I["constraint"]=$A;}else{$I["table"]=$a;$I["source"]=array("");$I["constraint"]=str_replace("{columns}","",$Gh);}echo'
<form action="" method="post">
';$Il=array_keys(fields($a));if($I["db"]!="")connection()->select_db($I["db"]);$Qi=null;if($I["ns"]!=""){$Qi=get_schema();set_schema($I["ns"]);}$ok=array_keys(array_filter(table_status('',true),function(array$R){return!$R["dependent"]&&fk_support($R);}));$Cm=array_keys(fields(in_array($I["table"],$ok)?$I["table"]:reset($ok)));$c=on('change','foreignChange');echo"<p><label>".'Target table'.": ".html_select("table",$ok,$I["table"],$c)."</label>\n";if(support("scheme")){$bi=($I["ns"]!=""?$I["ns"]:$_GET["ns"]);$Uk=array_filter(adminer()->schemas(),function($K)use($bi){return!driver()->isSystem(DB,$K)||$K==$bi||$K==$_GET["ns"];});echo"<label>".'Schema'.": ".html_select("ns",$Uk,$bi,$c)."</label>";if($Qi!==null)set_schema($Qi);}elseif(JUSH!="sqlite"){$el=($I["db"]!=""?$I["db"]:DB);$wc=array();foreach(adminer()->databases()as$k){if(!driver()->isSystem($k)||$k==$el||$k==DB)$wc[]=$k;}echo"<label>".'DB'.": ".html_select("db",$wc,$el,$c)."</label>";}echo
input_hidden("change-js"),'<noscript><p><input type=\'submit\' name=\'change\' value=\'Change\'></noscript>
<table',on('change','foreignName',$Jh,$Gh),'>
<thead><tr><th id="label-source">Source<th id="label-target">Target<tbody>
';$fg=0;foreach($I["source"]as$w=>$W){echo"<tr>","<td>".html_select("source[".(+$w)."]",array(-1=>"")+$Il,$W,($fg==count($I["source"])-1?on('change','foreignAddRow'):""),"label-source"),"<td>".html_select("target[".(+$w)."]",$Cm,idx($I["target"],$w),"","label-target");$fg++;}echo'</table>
<p>
<label>ON DELETE: ',html_select("on_delete",array(-1=>"")+explode("|",driver()->onActions),$I["on_delete"]),'</label>
<label>ON UPDATE: ',html_select("on_update",array(-1=>"")+explode("|",driver()->onActions),$I["on_update"]),'</label>
',(support("deferrable")?html_select("deferrable",array('NOT DEFERRABLE','DEFERRABLE','DEFERRABLE INITIALLY DEFERRED'),$I["deferrable"]):''),'<p>
<input type=\'submit\' value=\'Save\'>
<noscript><p><input type=\'submit\' name=\'add\' value=\'Add column\'></noscript>
';if($A!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$A)),'>
';if(JUSH!="sqlite"){print_fieldset("options",'Options',$I["constraint"]!=$A&&!preg_match("($Jh)",$I["constraint"]));echo"<label>".'Name'.": <input name='constraint' value='".h($I["constraint"])."' autocapitalize='off'></label>\n","</div></fieldset>\n";}echo
input_token(),'</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$I=$_POST;$Ri="VIEW";if(JUSH=="pgsql"&&$a!=""){$O=table_status1($a);$Ri=strtoupper($O["Engine"]);}if($_POST&&!$m){$A=trim($I["name"]);$Fa=" AS\n$I[select]";$Lg=ME."table=".url_escape($A);$jh='View has been altered.';$T=($_POST["materialized"]?"MATERIALIZED VIEW":"VIEW");if(!$_POST["drop"]&&$a==$A&&JUSH!="sqlite"&&$T=="VIEW"&&$Ri=="VIEW")query_redirect((JUSH=="mssql"?"ALTER":"CREATE OR REPLACE")." VIEW ".table($A).$Fa,$Lg,$jh);else{$Gm="adminer_".uniqid();drop_create("DROP $Ri ".table($a),"CREATE $T ".table($A).$Fa,"DROP $T ".table($A),"CREATE $T ".table($Gm).$Fa,"DROP $T ".table($Gm),($_POST["drop"]?substr(ME,0,-1):$Lg),'View has been dropped.',$jh,'View has been created.',$a,$A);}}$ai=false;if(!$_POST&&$a!=""){$I=view($a);$ai=!$I["select"];$I["name"]=$a;$I["materialized"]=($Ri!="VIEW");if(!$m)$m=adminer()->error();}page_header(($a!=""?'Alter view':'Create view'),$m,array("table"=>$a),h($a),$ai,doc_link(array('sql'=>"create-view.html",'pgsql'=>"sql-createview.html",'cockroach'=>"create-view",'mssql'=>"t-sql/statements/create-view-transact-sql",'sqlite'=>"lang_createview.html",'oracle'=>"sqlrf/CREATE-VIEW.html",)));echo'
<form action="" method="post">
<p>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off">
',(support("materializedview")?" ".checkbox("materialized",1,$I["materialized"],'Materialized view'):""),'<p>';textarea("select",$I["select"]);echo'<p>
<input type=\'submit\' value=\'Save\'>
';if($a!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$a)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$Sf=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$Xl=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");$I=$_POST;if($_POST&&!$m){if($_POST["drop"])query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),'Event has been dropped.');elseif(in_array($I["INTERVAL_FIELD"],$Sf)&&isset($Xl[$I["STATUS"]])){$Sk="\nON SCHEDULE ".($I["INTERVAL_VALUE"]?"EVERY ".q($I["INTERVAL_VALUE"])." $I[INTERVAL_FIELD]".($I["STARTS"]?" STARTS ".q($I["STARTS"]):"").($I["ENDS"]?" ENDS ".q($I["ENDS"]):""):"AT ".q($I["STARTS"]))." ON COMPLETION".($I["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?'Event has been altered.':'Event has been created.'),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$Sk.($aa!=$I["EVENT_NAME"]?"\nRENAME TO ".idf_escape($I["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($I["EVENT_NAME"]).$Sk)."\n".$Xl[$I["STATUS"]]." COMMENT ".q($I["EVENT_COMMENT"]).rtrim(" DO\n$I[EVENT_DEFINITION]",";").";"));}}$ai=false;if(!$I&&$aa!=""){$J=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$ai=!$J;$I=reset($J);}page_header(($aa!=""?'Alter event'.": ".h($aa):'Create event'),$m,"#events","",$ai,doc_link(array('sql'=>"create-event.html")));echo'
<form action="" method="post">
<table class="layout">
<tr><th>Name<td><input name="EVENT_NAME" value="',h($I["EVENT_NAME"]),'" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">Start<td><input name="STARTS" value="',h("$I[EXECUTE_AT]$I[STARTS]"),'">
<tr><th title="datetime">End<td><input name="ENDS" value="',h($I["ENDS"]),'">
<tr><th>Every
<td><input type="number" name="INTERVAL_VALUE" value="',h($I["INTERVAL_VALUE"]),'" class="size"> ',html_select("INTERVAL_FIELD",$Sf,$I["INTERVAL_FIELD"]),'<tr><th>Status<td>',html_select("STATUS",$Xl,$I["STATUS"]),'<tr><th>Comment<td><input name="EVENT_COMMENT" value="',h($I["EVENT_COMMENT"]),'" data-maxlength="64">
<tr><th><td>',checkbox("ON_COMPLETION","PRESERVE",$I["ON_COMPLETION"]=="PRESERVE",'On completion preserve'),'</table>
<p>';textarea("EVENT_DEFINITION",$I["EVENT_DEFINITION"]);echo'<p>
<input type=\'submit\' value=\'Save\'>
';if($aa!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$aa)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["procedure"])){$ba=($_GET["name"]?:$_GET["procedure"]);$Ik=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$I=$_POST;$I["fields"]=(array)$I["fields"];if($_POST&&!process_fields($I["fields"])&&!$m){foreach($I["fields"]as$w=>$n){if($n["field"]=="")unset($I["fields"][$w]);}$ri=routine($_GET["procedure"],$Ik);$pi=($ri?routine_id($ba,$ri):"");$Qh=routine_id($I["name"],$I);$i=create_routine($Ik,$I);$Lg=substr(ME,0,-1);$jh='Routine has been altered.';if(!$_POST["drop"]&&$pi==$Qh&&connection()->flavor!="mysql")queries_redirect($Lg,$jh,queries(substr_replace($i,(JUSH=="mssql"?' OR ALTER':' OR REPLACE'),6,0)));else{$Gm="adminer_".uniqid();drop_create("DROP $Ik $pi",$i,"DROP $Ik $Qh",create_routine($Ik,array("name"=>$Gm)+$I),"DROP $Ik ".routine_id($Gm,$I),$Lg,'Routine has been dropped.',$jh,'Routine has been created.',$ba,$I["name"]);}}$ai=false;if(!$_POST&&$ba!=""){$I=routine($_GET["procedure"],$Ik);$ai=!$I;$I["name"]=$ba;}$Lk=strtolower($Ik);page_header(($ba!=""?(isset($_GET["function"])?'Alter function':'Alter procedure').": ".h($ba):(isset($_GET["function"])?'Create function':'Create procedure')),$m,"#routines","",$ai,doc_link(array('sql'=>"create-procedure.html",'mariadb'=>"create-$Lk/",'pgsql'=>"sql-create$Lk.html",'cockroach'=>"create-$Lk",'mssql'=>"t-sql/statements/create-$Lk-transact-sql",)));if(!$_POST&&$ba=="")$I["language"]="sql";$Eb=(JUSH=="sql"?flat_collations():array());$Jk=routine_languages();echo($Eb?"<datalist id='collations'>".optionlist($Eb)."</datalist>":""),'
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off">
',($Jk?"<label>".'Language'.": ".html_select("language",array_keys($Jk),$I["language"],on('change','routineLanguage',$Jk))."</label>\n":""),'<input type=\'submit\' value=\'Save\'>
<div class="scrollable">
<table id="edit-fields" class="nowrap">
';edit_fields($I["fields"],$Eb,$Ik);if(isset($_GET["function"])){echo"<tr><td>".'Return type';edit_type("returns",(array)$I["returns"],$Eb,array(),(JUSH=="pgsql"?array("void","trigger"):array()));}echo'</table>
',script("editFields();"),'</div>
<p>';textarea("definition",$I["definition"],20,80,($Jk[$I["language"]]?:JUSH));echo'<p>
<input type=\'submit\' value=\'Save\'>
';if($ba!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$ba)),'>
';$Mk=routine_options($Ik);if($Mk){$Fi=false;foreach($Mk
as$w=>$Y){$l=($Y?reset($Y):"");$I["options"][$w]=idx($I["options"],$w,$l);if($I["options"][$w]!=$l)$Fi=true;}print_fieldset("options",'Options',$Fi);echo"<table class='layout'>\n";foreach($Mk
as$w=>$Y){$qg="label-option-$w";$Om=str_replace("_"," ",$w);$L=array();foreach($Y
as$X)$L[$X]=(strpos($X,"$Om ")===0?substr($X,strlen($Om)+1):$X);echo"<tr><th id='$qg'>$Om<td>".($L?html_select("options[$w]",$L,$I["options"][$w],"",$qg):"<input name='options[$w]' value='".h($I["options"][$w])."' aria-labelledby='$qg' autocapitalize='off'>")."\n";}echo"</table>\n</div></fieldset>\n";}echo
input_token(),'</form>
';}elseif(isset($_GET["sequence"])){$da=$_GET["sequence"];$I=$_POST;if($_POST&&!$m){$z=substr(ME,0,-1);$A=trim($I["name"]);if($_POST["drop"])query_redirect("DROP SEQUENCE ".idf_escape($da),$z,'Sequence has been dropped.');elseif($da=="")query_redirect("CREATE SEQUENCE ".idf_escape($A),$z,'Sequence has been created.');elseif($da!=$A)query_redirect("ALTER SEQUENCE ".idf_escape($da)." RENAME TO ".idf_escape($A),$z,'Sequence has been altered.');else
redirect($z);}$ai=(!$_POST&&$da!=""&&!get_val("SELECT relname FROM pg_class WHERE relkind = 'S' AND relnamespace = ".driver()->nsOid." AND relname = ".q($da)));page_header(($da!=""?'Alter sequence'.": ".h($da):'Create sequence'),$m,"#sequences","",$ai,doc_link(array('pgsql'=>"sql-createsequence.html",'cockroach'=>"create-sequence",)));if(!$I)$I["name"]=$da;echo'
<form action="" method="post">
<p><input name="name" value="',h($I["name"]),'" autocapitalize="off">
<input type=\'submit\' value=\'Save\'>
';if($da!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm(sprintf('Drop %s?',$da)).">\n";echo
input_token(),'</form>
';}elseif(isset($_GET["type"])){function
enum_values($Cc){$X="'(?:[^']|'')*'";if(!preg_match('~^AS\s+ENUM\s*\(\s*('.$X.'(?:\s*,\s*'.$X.')*)\s*\)$~i',$Cc,$_))return
null;preg_match_all('~'.$X.'~',$_[1],$Rg);return$Rg[0];}function
add_enum_values($T,$ni,$Nh){$ti=enum_values($ni);$Vh=enum_values($Nh);if($ti===null||$Vh===null)return
null;$H=array();$r=0;foreach($Vh
as$X){if($X===idx($ti,$r))$r++;else$H[]="ALTER TYPE ".idf_escape($T)." ADD VALUE $X".($r<count($ti)?" BEFORE ".$ti[$r]:"");}return($r==count($ti)?$H:null);}$ea=$_GET["type"];$I=$_POST;$nn=($ea!=""?array_search($ea,types(true)):0);$T=($nn?type_definition(+$nn):array());$fi=($T["kind"]=='d'?"DOMAIN":"TYPE");if($_POST&&!$m){$z=substr(ME,0,-1);$A=trim($I["name"]);$Fa=trim(str_replace("\r","",$I["as"]));$Sh=(preg_match('~^AS\s+(?!ENUM\b|RANGE\b|\()~i',$Fa)?"DOMAIN":"TYPE");$jh='Type has been altered.';$b=(!$_POST["drop"]&&$ea!=""&&$Sh==$fi?($Fa==$T["definition"]?array():add_enum_values($ea,$T["definition"],$Fa)):null);if($b!==null){if($ea!=$A)$b[]="ALTER $fi ".idf_escape($ea)." RENAME TO ".idf_escape($A);if(!$b)redirect($z);$Td=false;foreach($b
as$F){if(!queries($F)){$Td=true;break;}}queries_redirect($z,$jh,!$Td);}else{$Gm="adminer_".uniqid();drop_create("DROP $fi ".idf_escape($ea),"CREATE $Sh ".idf_escape($A)." $Fa","DROP $Sh ".idf_escape($A),"CREATE $Sh ".idf_escape($Gm)." $Fa","DROP $Sh ".idf_escape($Gm),$z,'Type has been dropped.',$jh,'Type has been created.',$ea,$A);}}page_header(($ea!=""?'Alter type'.": ".h($ea):'Create type'),$m,"#user-types","",($nn===false),doc_link(array('pgsql'=>"sql-createtype.html",'cockroach'=>"create-type",)));if(!$I){$I["name"]=$ea;$I["as"]=($ea!=""?$T["definition"]:"AS ");}echo'
<form action="" method="post">
<p>
','Name'.": <input name='name' value='".h($I['name'])."' autocapitalize='off'>\n";textarea("as",$I["as"]);echo"<p><input type='submit' value='".'Save'."'>\n";if($ea!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm(sprintf('Drop %s?',$ea)).">\n";echo
input_token(),'</form>
';}elseif(isset($_GET["check"])){$a=$_GET["check"];$A="$_GET[name]";$I=$_POST;if($I&&!$m){$Lg=ME."table=".url_escape($a);$mh='Check has been dropped.';$kh='Check has been altered.';$lh='Check has been created.';if(JUSH=="sqlite"&&!(min_version(3.53)&&($A==""||idx(driver()->checkConstraints($a),$A)!=$A))){$ka=($I["drop"]?"":($I["name"]!=""?"CONSTRAINT ".idf_escape($I["name"])." ":"")."CHECK ($I[clause])");queries_redirect($Lg,($I["drop"]?$mh:($A!=""?$kh:$lh)),recreate_table($a,$a,array(),array(),array(),"",array(),"$A",$ka));}else{$b="ALTER TABLE ".table($a);$pb=" CHECK ($I[clause])";$Gm="adminer_".uniqid();drop_create("$b DROP CONSTRAINT ".idf_escape($A),"$b ADD".($I["name"]!=""?" CONSTRAINT ".idf_escape($I["name"]):"").$pb,"$b DROP CONSTRAINT ".idf_escape($I["name"]),"$b ADD CONSTRAINT ".idf_escape($Gm).$pb,"$b DROP CONSTRAINT ".idf_escape($Gm),$Lg,$mh,$kh,$lh,$A,$I["name"]);}}$ai=false;if(!$I){$tb=driver()->checkConstraints($a);$ai=($A!=""&&!$tb[$A]);$I=array("name"=>($A==""?object_name("CHECK",$a,array()):($tb[$A]!=$A?$A:"")),"clause"=>$tb[$A]);}page_header(($A!=""?'Alter check':'Create check'),$m,array("table"=>$a),h($A!=""?$A:$a),$ai,doc_link(array('sql'=>"create-table-check-constraints.html",'mariadb'=>"constraint/",'pgsql'=>"ddl-constraints.html#DDL-CONSTRAINTS-CHECK-CONSTRAINTS",'cockroach'=>"check",'mssql'=>"relational-databases/tables/create-check-constraints",'sqlite'=>"lang_createtable.html#check_constraints",)));echo'
<form action="" method="post">
<p><label>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off"></label>
<p>';textarea("clause",$I["clause"]);echo'<p><input type=\'submit\' value=\'Save\'>
';if($A!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$A)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$A="$_GET[name]";$in=trigger_options();$I=trigger($A,$a);$ai=($A!=""&&!$I);$Gh=str_replace("{table}",$a,adminer()->namePattern("TRIGGER"));$I+=array("Trigger"=>strtr($Gh,array("{timing}"=>"b","{event}"=>"i","{columns}"=>"","{type}"=>"row")));if($_POST){if(!$m&&in_array($_POST["Timing"],$in["Timing"])&&in_array($_POST["Event"],$in["Event"])&&in_array($_POST["Type"],$in["Type"])){$ui=" ON ".table($a);$ad="DROP TRIGGER ".idf_escape($A).(JUSH=="pgsql"?$ui:"");$Lg=ME."table=".url_escape($a);if($_POST["drop"])query_redirect($ad,$Lg,'Trigger has been dropped.');else{if($A!="")queries($ad);queries_redirect($Lg,($A!=""?'Trigger has been altered.':'Trigger has been created.'),queries(create_trigger($ui,$_POST)));if($A!="")queries(create_trigger($ui,$I+array("Type"=>reset($in["Type"]))));}}$I=$_POST;}page_header(($A!=""?'Alter trigger':'Create trigger'),$m,array("table"=>$a),h($A!=""?$A:$a),$ai,doc_link(array('sql'=>"create-trigger.html",'pgsql'=>"sql-createtrigger.html",'cockroach'=>"create-trigger",'mssql'=>"t-sql/statements/create-trigger-transact-sql",'sqlite'=>"lang_createtrigger.html",'oracle'=>"lnpls/CREATE-TRIGGER-statement.html",)));$Jh=strtr(preg_quote($Gh),array('\{timing\}'=>'[abi]','\{event\}'=>'[iud]*','\{columns\}'=>'.*','\{type\}'=>'(row|statement)'));$gn=on('change','triggerChange',"^$Jh$",$Gh);$ii=on('input','triggerChange',"^$Jh$",$Gh);echo'
<form action="" method="post" id="form">
<table class="layout">
<tr><th>Time
<td>',html_select("Timing",$in["Timing"],$I["Timing"],$gn),'<tr><th>Event<td>',html_select("Event",$in["Event"],$I["Event"],$gn),(in_array("UPDATE OF",$in["Event"])?" <input name='Of' value='".h($I["Of"])."' class='hidden'$ii>":""),'<tr><th>Type<td>',html_select("Type",$in["Type"],$I["Type"],$gn),'<tr><th>Name<td><input name="Trigger" value="',h($I["Trigger"]),'" data-maxlength="64" autocapitalize="off">
</table>
',script("fire(qs('#form')['Timing'], 'change');"),'<p>';textarea("Statement",$I["Statement"]);echo'<p>
<input type=\'submit\' value=\'Save\'>
';if($A!="")echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',$A)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["user"])){function
grant($He,array$Wj,$e,$ui){if(!$Wj)return
true;if($Wj==array("ALL PRIVILEGES","GRANT OPTION"))return($He=="GRANT"?queries("$He ALL PRIVILEGES$ui WITH GRANT OPTION"):queries("$He ALL PRIVILEGES$ui")&&queries("$He GRANT OPTION$ui"));return
queries("$He ".preg_replace('~(GRANT OPTION)\([^)]*\)~','\1',implode("$e, ",$Wj).$e).$ui);}$fa=$_GET["user"];$Wj=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$I){foreach(explode(",",($I["Privilege"]=="Grant option"?"":$I["Context"]))as$Zb)$Wj[$Zb=="File access on server"?"Server Admin":$Zb][$I["Privilege"]]=$I["Comment"];}unset($Wj["Server Admin"]["Usage"]);foreach($Wj["Tables"]as$w=>$W)unset($Wj["Databases"][$w]);$Ph=array();if($_POST){foreach($_POST["objects"]as$w=>$W)$Ph[$W]=(array)$Ph[$W]+idx($_POST["grants"],$w,array());}$Ie=array();$G=(isset($_GET["host"])?connection()->query("SHOW GRANTS FOR ".q($fa)."@".q($_GET["host"])):null);$ai=(isset($_GET["host"])&&!$G);if($G){while($I=$G->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$I[0],$_)&&preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~',$_[1],$Rg,PREG_SET_ORDER)){foreach($Rg
as$W){if($W[1]!="USAGE")$Ie["$_[2]$W[2]"][$W[1]]=true;if(preg_match('~ WITH GRANT OPTION~',$I[0]))$Ie["$_[2]$W[2]"]["GRANT OPTION"]=true;}}}}if($_POST&&!$m){$si=(isset($_GET["host"])?q($fa)."@".q($_GET["host"]):"''");if($_POST["drop"])query_redirect("DROP USER $si",ME."privileges=",'User has been dropped.');else{$Uh=q($_POST["user"])."@".q($_POST["host"]);$rj=$_POST["pass"];$hc=false;$G=true;if($si!=$Uh){$hc=queries("CREATE USER $Uh IDENTIFIED BY ".($_POST["hashed"]?"PASSWORD ":"").q($rj));$G=$hc;}elseif($rj!="")$G=queries("SET PASSWORD FOR $Uh = ".(min_version(8,99)||$_POST["hashed"]?q($rj):"PASSWORD(".q($rj).")"));if($G){$Ek=array();foreach($Ph
as$fi=>$He){if(isset($_GET["grant"]))$He=array_filter($He);$He=array_keys($He);if(isset($_GET["grant"]))$Ek=array_diff(array_keys(array_filter($Ph[$fi],'strlen')),$He);elseif($si==$Uh){$oi=array_keys((array)$Ie[$fi]);$Ek=array_diff($oi,$He);$He=array_diff($He,$oi);unset($Ie[$fi]);}if(preg_match('~^(.+)\s*(\(.*\))?$~U',$fi,$_)&&(!grant("REVOKE",$Ek,$_[2]," ON $_[1] FROM $Uh")||!grant("GRANT",$He,$_[2]," ON $_[1] TO $Uh"))){$G=false;break;}}}if($G&&isset($_GET["host"])){if($si!=$Uh)queries("DROP USER $si");elseif(!isset($_GET["grant"])){foreach($Ie
as$fi=>$Ek){if(preg_match('~^(.+)(\(.*\))?$~U',$fi,$_))grant("REVOKE",array_keys($Ek),$_[2]," ON $_[1] FROM $Uh");}}}if($G&&!Queries::$queries)redirect(ME."privileges=");queries_redirect(ME."privileges=",(isset($_GET["host"])?'User has been altered.':'User has been created.'),$G);if($hc)connection()->query("DROP USER $Uh");}}page_header((isset($_GET["host"])?'Username'.": ".h("$fa@$_GET[host]"):'Create user'),$m,array("privileges"=>array('','Privileges')),"",$ai,doc_link(array('sql'=>"grant.html",'mariadb'=>"grant")));$I=$_POST;if($I)$Ie=$Ph;else{$I=$_GET+array("host"=>get_val("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$Ie[(DB==""||$Ie?"":idf_escape(addcslashes(DB,"%_\\"))).".*"]=array();}echo'<form action="" method="post">
<table class="layout">
<tr><th>Server<td><input name="host" data-maxlength="60" value="',h($I["host"]),'" autocapitalize="off">
<tr><th>Username<td><input name="user" data-maxlength="80" value="',h($I["user"]),'" autocapitalize="off">
<tr><th>Password<td><input name="pass" id="pass" value="',h($I["pass"]),'" autocomplete="new-password">
',($I["hashed"]?"":script("typePassword(qs('#pass'));")),(min_version(8,99)?"":checkbox("hashed",1,$I["hashed"],'Hashed',on('click','hashedClick'))),'</table>

',"<table class='odds'>\n","<thead><tr><th colspan='2'>".'Privileges';$r=0;foreach($Ie
as$fi=>$He){echo'<th>'.($fi!="*.*"?"<input name='objects[$r]' value='".h($fi)."' size='10' autocapitalize='off'>":input_hidden("objects[$r]","*.*")."*.*");$r++;}echo"<tbody>\n";foreach(array(""=>"","Server Admin"=>'Server',"Databases"=>'Database',"Tables"=>'Table',"Procedures"=>'Routine',)as$Zb=>$Gc){foreach((array)$Wj[$Zb]as$Vj=>$f){echo"<tr><td".($Gc?">$Gc<td":" colspan='2'").' lang="en" title="'.h($f).'">'.h($Vj);$r=0;foreach($Ie
as$fi=>$He){$A="'grants[$r][".h(strtoupper($Vj))."]'";$X=$He[strtoupper($Vj)];if($Zb=="Server Admin"&&$fi!=(isset($Ie["*.*"])?"*.*":".*"))echo"<td>";elseif(isset($_GET["grant"]))echo"<td><select name=$A><option><option value='1'".($X?" selected":"").">".'Grant'."<option value='0'".($X=="0"?" selected":"").">".'Revoke'."</select>";else
echo"<td align='center'><label class='block'>","<input type='checkbox' name=$A value='1'".($X?" checked":"").($Vj=="All privileges"?" id='grants-$r-all'":($Vj=="Grant option"?"":on('click','grantsClick',"grants-$r-all"))).">","</label>";$r++;}}}echo"</table>\n",'<p>
<input type=\'submit\' value=\'Save\'>
';if(isset($_GET["host"]))echo'<input type=\'submit\' name=\'drop\' value=\'Drop\'',confirm(sprintf('Drop %s?',"$fa@$_GET[host]")),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")){if($_POST&&!$m){$ng=0;foreach((array)$_POST["kill"]as$W){if(adminer()->killProcess($W))$ng++;}queries_redirect(ME."processlist=",lang_format(array('%d process has been killed.','%d processes have been killed.'),$ng),$ng||!$_POST["kill"]);}}page_header('Process list',$m);echo'
<form action="" method="post">
<div class="scrollable">
<table class="nowrap checkable odds"',on('click','tableClick').on('dblclick','tableClick'),'>
';$r=-1;foreach(adminer()->processList()as$r=>$I){if(!$r){echo"<thead><tr lang='en'>".(support("kill")?"<td class='hover'>":"");foreach($I
as$w=>$W)echo"<th>$w".doc_link(array('sql'=>"show-processlist.html#processlist_".strtolower($w),'pgsql'=>"monitoring-stats.html#PG-STAT-ACTIVITY-VIEW",'oracle'=>"refrn/V-SESSION.html",));echo"<tbody>\n";}echo"<tr>".(support("kill")?"<td class='hover'>".checkbox("kill[]",$I[JUSH=="sql"?"Id":"pid"],0):"");foreach($I
as$w=>$W)echo"<td>".($W!=""&&((JUSH=="sql"&&$w=="Info"&&preg_match("~Query|Killed~",$I["Command"]))||(JUSH=="pgsql"&&$w=="query")||(JUSH=="oracle"&&$w=="sql_text"))?"<code class='sql jush-".JUSH."' data-full='".h($W)."'>".shorten_utf8($W,100,"</code>").' <a href="'.h(($I["db"]!=""?preg_replace('~&db=[^&]*~','',ME)."db=".url_escape($I["db"])."&":ME)."sql=".url_escape($W)).'">'.'Clone'.'</a>'.' '.copy_icon():h($W));echo"\n";}echo'</table>
</div>
<p>
',script("copyCode(qsl('table'));");if(support("kill"))echo
format_number($r+1)."/".sprintf('%d in total',max_connections()),"<p><input type='submit' value='".'Kill'."'>\n";echo
input_token(),'</form>
',script("tableCheck();");}elseif($_GET["select"]!=""){$a=$_GET["select"];$R=table_status1($a);$v=indexes($a);$o=fields($a);$re=column_foreign_keys($a);$mi=$R["Oid"];$Gk=array();$e=array();$Yk=array();$Ii=array();$Jm=null;foreach($o
as$w=>$n){$A=adminer()->fieldName($n);$Ih=html_entity_decode(strip_tags($A),ENT_QUOTES);if(isset($n["privileges"]["select"])&&$A!=""){$e[$w]=$Ih;if(is_shortable($n))$Jm=adminer()->selectLengthProcess();}if(isset($n["privileges"]["where"])&&$A!="")$Yk[$w]=$Ih;if(isset($n["privileges"]["order"])&&$A!="")$Ii[$w]=$Ih;$Gk+=$n["privileges"];}list($L,$Ke)=adminer()->selectColumnsProcess($e,$v);$L=array_unique($L);$Ke=array_unique($Ke);$Zf=count($Ke)<count($L);$Z=adminer()->selectSearchProcess($o,$v,$R);$Hi=adminer()->selectOrderProcess($o,$v);$y=adminer()->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$wn=>$I){$Fa=convert_field($o[key($I)]);$L=array($Fa?:idf_escape(key($I)));$Z[]=where_check(bracket_escape($wn,true),$o);$H=driver()->select($a,$L,$Z,$L);if($H)echo
first($H->fetch_row());}exit;}$Rj=$zn=array();foreach($v
as$u){if($u["type"]=="PRIMARY"){$Rj=array_flip($u["columns"]);$zn=($L?$Rj:array());foreach($zn
as$w=>$W){if(in_array(idf_escape($w),$L))unset($zn[$w]);}break;}}if($mi&&!$Rj){$Rj=$zn=array($mi=>0);$v[]=array("type"=>"PRIMARY","columns"=>array($mi));}if($_POST&&!$m){$go=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$tb=array();foreach($_POST["check"]as$pb)$tb[]=where_check($pb,$o);$go[]="((".implode(") OR (",$tb)."))";}$io=$go;$go=($go?"\nWHERE ".implode(" AND ",$go):"");if($_POST["export"]){save_settings(array("output"=>$_POST["output"],"format"=>$_POST["format"]),"adminer_import");dump_headers($a);adminer()->dumpTable($a,"");$cl=($L?:array("*"));$cc=convert_fields($e,$o,$L);if($cc)$cl[]=substr($cc,2);$F="";if(is_array($_POST["check"])&&!$Rj){$ye=implode(", ",$cl)."\nFROM ".table($a);$Me=($Ke&&$Zf?"\nGROUP BY ".implode(", ",$Ke):"").($Hi?"\nORDER BY ".implode(", ",$Hi):"");$tn=array();foreach($_POST["check"]as$W)$tn[]="(SELECT".limit($ye,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($W,$o).$Me,1).")";$F=implode(" UNION ALL ",$tn);}adminer()->dumpData($a,"table",$F,$cl,$io,($Zf?$Ke:array()),$Hi);adminer()->dumpFooter();exit;}if(!adminer()->selectEmailProcess($Z,$re)){if($_POST["save"]||$_POST["delete"]){$G=true;$ra=0;$Wa=false;$N=array();if(!$_POST["delete"]){foreach($o
as$A=>$W){$t=bracket_escape($A);if(isset($_POST["fields"][$t])||$_FILES["fields-$t"]){$W=process_input($o[$A]);if($W!==null&&($_POST["clone"]||$W!==false))$N[idf_escape($A)]=($W!==false?$W:idf_escape($A));}}}if($_POST["delete"]||$N){$F=($_POST["clone"]?"INTO ".table($a)." (".implode(", ",array_keys($N)).")\nSELECT ".implode(", ",$N)."\nFROM ".table($a):"");if($_POST["all"]||($Rj&&is_array($_POST["check"]))||$Zf){$G=($_POST["delete"]?driver()->delete($a,$go):($_POST["clone"]?queries("INSERT $F$go".driver()->insertReturning($a)):driver()->update($a,$N,$go)));$ra=connection()->affected_rows;if(is_object($G))$ra+=$G->num_rows;}else{$Wa=count((array)$_POST["check"])>1&&driver()->begin();foreach((array)$_POST["check"]as$W){$fo="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($W,$o);$G=($_POST["delete"]?driver()->delete($a,$fo,1):($_POST["clone"]?queries("INSERT".limit1($a,$F,$fo)):driver()->update($a,$N,$fo,1)));if(!$G)break;$ra+=connection()->affected_rows;}if($Wa&&$G&&!driver()->commit())$G=false;}}$jh=lang_format(array('%d item has been affected.','%d items have been affected.'),$ra);if($_POST["clone"]&&$G&&$ra==1){$wg=last_id($G);if($wg)$jh=sprintf('Item%s has been inserted.'," $wg");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page|next":""),$jh,$G);if($Wa)driver()->rollback();if(!$_POST["delete"]){$Hj=(array)$_POST["fields"];edit_form($a,array_intersect_key($o,$Hj),$Hj,!$_POST["clone"],$m);page_footer();exit;}}elseif(!$_POST["import"]){$G=true;$ra=0;$Wa=count((array)$_POST["val"])>1&&driver()->begin();foreach((array)$_POST["val"]as$wn=>$I){$N=array();foreach($I
as$w=>$W){$w=bracket_escape($w,true);$N[idf_escape($w)]=(preg_match('~char|text~',$o[$w]["type"])||$W!=""?adminer()->processInput($o[$w],$W):"NULL");}$G=driver()->update($a,$N," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check(bracket_escape($wn,true),$o),($Zf||$Rj?0:1)," ");if(!$G)break;$ra+=connection()->affected_rows;}if($Wa)$G=$G&&driver()->commit();queries_redirect(remove_from_uri(),lang_format(array('%d item has been affected.','%d items have been affected.'),$ra),$G);if($Wa)driver()->rollback();}else{save_settings(array("format"=>$_POST["separator"]),"adminer_import");$be=get_file("csv_file",true);if(!is_string($be))$m=upload_error($be);elseif(!preg_match('~~u',$be))$m='File must be in UTF-8 encoding.';else{$Fb=array_keys($o);$il=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$lc=parse_csv($be,$il);$ra=count($lc);driver()->begin();$J=array();foreach($lc
as$w=>$Y){if(!$w&&!array_diff($Y,$Fb)){$Fb=$Y;$ra--;}else{$N=array();foreach($Y
as$r=>$Bb)$N[idf_escape($Fb[$r])]=($Bb==""&&$o[$Fb[$r]]["null"]?"NULL":q(csv_value($Bb)));$J[]=$N;}}$G=(!$J||driver()->insertUpdate($a,$J,$Rj));if($G)driver()->commit();queries_redirect(remove_from_uri("page|next"),lang_format(array('%d row has been imported.','%d rows have been imported.'),$ra),$G);driver()->rollback();}}}}$rm=adminer()->tableName($R);if(is_ajax()){page_headers();ob_start();}else
page_header('Select'.": $rm",$m,array(),"",(!$o&&support("table")),($o?doc_link(array(JUSH=>driver()->tableHelp($a,is_view($R)))):""));$N=null;if(isset($Gk["insert"])||!support("table")){$N="";foreach((array)$_GET["where"]as$W){$X=$W["val"];if(is_array($X))$X=(count($X)==1&&preg_match('~^val-(.*)~s',reset($X),$_)?$_[1]:"");if($W["col"]!=""&&$X!=""&&($W["op"]=="="||(!$W["op"]&&(is_array($W["val"])||!preg_match('~[_%]~',$X)))))$N
.="&set[".url_escape(bracket_escape($W["col"]))."]=".url_escape($X);}}adminer()->selectLinks($R,$N);if(!$e&&support("table"))echo"<p class='error'>".'Unable to select the table.'."\n";else{echo"<form action='' id='form'>\n","<div hidden>";hidden_fields_get();echo(DB!=""?input_hidden("db",DB).(isset($_GET["ns"])?input_hidden("ns",$_GET["ns"]):""):""),input_hidden("select",$a),"</div>\n";adminer()->selectColumnsPrint($L,$e);adminer()->selectSearchPrint($Z,$Yk,$v,$R);adminer()->selectOrderPrint($Hi,$Ii,$v);adminer()->selectLimitPrint($y);if($Jm!==null)adminer()->selectLengthPrint($Jm);adminer()->selectActionPrint($v);echo"</form>\n";foreach((array)$_GET["where"]as$W){if($W["op"]=="SQL"&&!in_array($_SERVER["HTTP_SEC_FETCH_SITE"],array("","same-origin"))){echo"<p class='error'>".'Invalid CSRF token. Submit the form again.'.' '.'If you did not send this request from Adminer, close this page.'."\n";page_footer();exit;}}$C=$_GET["page"];$ue=null;if($C=="last"){$ue=get_val(count_rows($a,$Z,$Zf,$Ke));$C=floor(max(0,intval($ue)-1)/$y);}$bl=$L;$Le=$Ke;if(!$bl){$bl[]="*";$cc=convert_fields($e,$o,$L);if($cc)$bl[]=substr($cc,2);}foreach($L
as$w=>$W){$n=$o[idf_unescape($W)];if($n&&($Fa=convert_field($n)))$bl[$w]="$Fa AS $W";}if(JUSH=="pgsql"||JUSH=="mssql"){foreach((array)$_GET["columns"]as$w=>$W){if(isset($bl[$w])&&$W["fun"])$bl[$w].=" AS ".idf_escape(apply_sql_function($W["fun"],($W["col"]!=""?$W["col"]:"*")));}}if(!$Zf&&$zn){foreach($zn
as$w=>$W){$bl[]=idf_escape($w);if($Le)$Le[]=idf_escape($w);}}$G=driver()->select($a,$bl,$Z,$Le,$Hi,$y,$C,true);if(!is_object($G))echo"<p class='error'>".(adminer()->error()?:'Unknown error.')."\n";else{if(JUSH=="mssql"&&$C)$G->seek($y*$C);$pd=array();$J=array();while($I=$G->fetch_assoc()){if($C&&JUSH=="oracle")unset($I["RNUM"]);$J[]=$I;}$Xe=($y&&(support("cursor")?$_GET["next"]!="":count($J)>=$y));if(is_ajax()&&$Xe)header("X-Next-Page: ".pagination_href($C+1));if($_GET["modify"]&&$J){$ah=max_input_vars(count($J[0])+1,20);echo($ah&&count($J)>$ah?"<p class='error'>".max_input_vars_error()."\n":"");}echo"<form action='' method='post' enctype='multipart/form-data'".on_upload_progress($En).">\n";if($_GET["page"]!="last"&&$y&&$Ke&&$Zf&&JUSH=="sql")$ue=get_val(" SELECT FOUND_ROWS()");if(!$J)echo"<p class='message'>".'No rows.'."\n";else{$Sa=adminer()->backwardKeys($a,$rm);$Ck=array();reset($L);foreach($J[0]as$w=>$W){if(!isset($zn[$w])){$W=idx($_GET["columns"],key($L))?:array();$Ck[$w]=array("fun"=>$W["fun"],"col"=>($L?$W["col"]:$w));next($L);}}echo"<div class='scrollable'>","<table id='table' class='nowrap checkable odds'".on('click','tableClick').on('dblclick','tableClick').on('keydown','editingKeydown').">\n","<thead><tr>".(!$Ke&&$L?"":"<td class='hover check sticky'><input type='checkbox' id='all-page' class='jsonly' title='".'All rows on this page'."'".on('click','formCheck','^check').">");$Kh=array();$lk=1;foreach($Ck
as$w=>$W){$n=$o[$W["col"]];$A=($n?adminer()->fieldName($n,$lk):($W["fun"]?($W["col"]!=""?h($W["col"]):"*"):h($w)));if($A!=""){$lk++;$Kh[$w]=$A;$d=idf_escape($w);$nf=remove_from_uri('(order|desc)[^=]*|page|next').'&order[0]='.url_escape($w);$Gc="&desc[0]=1";$Fl=preg_replace('~ DESC( NULLS LAST)?$~','',$Hi[0]);$Hl=($Fl==$d||$Fl==$w);echo"<th id='th[".h(bracket_escape($w))."]'".($Hl?" aria-sort='".($Fl==$Hi[0]?"ascending":"descending")."'":"").">";$Ce=apply_sql_function(h($W["fun"]),$A);$Gl=isset($n["privileges"]["order"])||$W["fun"];echo($Gl?"<a href='".h($nf.($Hl&&$Fl==$Hi[0]?$Gc:''))."'>$Ce</a>":$Ce),doc_link(array(JUSH=>$n["doc"]));$ih=($Gl?"<a href='".h($nf.$Gc)."' title='".'descending'."' class='text'> ↓</a>":'');if(!$W["fun"]&&isset($n["privileges"]["where"]))$ih
.="<a href='#fieldset-search' title='".'Search'."' class='text jsonly'".on('click','selectSearch',$w)."> =</a>";echo($ih?"<span class='column'>$ih</span>":"");}}$Cg=array();if($_GET["modify"]){foreach($J
as$I){foreach($I
as$w=>$W)$Cg[$w]=max($Cg[$w],min(40,utf8_length((string)$W)));}}$if=array();$hf=array();foreach((array)$_GET["where"]as$W){$W+=array("col"=>"","op"=>"","val"=>"");$Bb=$W["col"];$Xk=$W["val"];if(!is_array($Xk)&&($Xk!=""||preg_match('~NULL$~',$W["op"]))&&(!$W["op"]||in_array($W["op"],adminer()->operators($R)))){$Eg=strtr(preg_quote($Xk),array("%"=>".*?","_"=>"."));$xj=array("LIKE %%"=>$Eg,"ILIKE %%"=>$Eg,"REGEXP"=>$Xk)+(JUSH=="pgsql"?array("~"=>$Xk,"~*"=>$Xk):array())+($Bb!=""?array():array("="=>'^'.preg_quote($Xk).'\z',"IN"=>'^(?:'.implode("|",array_map('preg_quote',array_map('trim',explode(",",$Xk)))).')\z',"LIKE"=>"^$Eg\\z","ILIKE"=>"^$Eg\\z","FIND_IN_SET"=>'(?<=^|,)'.preg_quote($Xk).'(?=,|\z)',));foreach(($Bb!=""?array($Bb=>$o[$Bb]):$o)as$A=>$n){if($Bb!=""||is_searchable($n,$W)){$xi=$W["op"]?:(!preg_match('~'.text_type().'~',$n["type"])?"IN":(preg_match('~%~',$Xk)?"LIKE":"LIKE %%"));if(isset($xj[$xi])){$wb=preg_match('~^ILIKE|\*$~',$xi)||($xi!="~"&&preg_match('~^(sql|mssql|sqlite)$~',JUSH));$if[$A][]="(?".($wb?"i":"").":$xj[$xi])";}elseif($xi=="IS NULL"&&$Bb=="")$hf[$A]=true;}}}}echo($Sa?"<th>".'Relations':"")."<tbody>\n";if(is_ajax())ob_end_clean();foreach(adminer()->rowDescriptions($J,$re)as$Eh=>$I){$vn=unique_array($J[$Eh],$v);if(!$vn){$vn=array();foreach($J[$Eh]as$w=>$W){if(!in_array(idx(idx($Ck,$w,array()),"fun"),driver()->grouping))$vn[$w]=$W;}}$wn="";$r=0;foreach($vn
as$w=>$W){$Bk=idx($Ck,$w,array());if($L&&$Bk&&!isset($o[$Bk["col"]]))continue;$Ce=idx($Bk,"fun","");$Bb=($Ce?$Bk["col"]:$w);$n=(array)$o[$Bb];$Yf=is_blob($n);if(!$Ce&&strlen($W)>64&&driver()->md5(idf_escape($Bb),$n)){$Ce="md5";$W=md5($Yf?(string)driver()->value($W,$n):$W);}if($Ce){$wn
.="&fun[$r]=".url_escape($Ce)."&col[$r]=".url_escape($Bb).($W!==null?"&val[$r]=".url_escape($W===false?"f":$W):"");$r++;}else$wn
.="&".($W!==null?"where[".url_escape(bracket_escape($Bb))."]=".url_escape($W===false?"f":$W):"null[]=".url_escape($Bb));}echo"<tr>".(!$Ke&&$L?"":"<td class='hover check sticky'>".($Zf||information_schema(DB)?"":"<a href='".h(ME."edit=".url_escape($a).$wn)."' class='edit'>".'edit'."</a> ").checkbox("check[]",substr($wn,1),in_array(substr($wn,1),(array)$_POST["check"])));foreach($I
as$w=>$W){if(isset($Kh[$w])){$Ce=$Ck[$w]["fun"];$Bb=$Ck[$w]["col"];$n=(array)$o[$w];if($W!=""&&(!isset($pd[$w])||$pd[$w]!=""))$pd[$w]=(is_mail($W)?$Kh[$w]:"");$z="";if(is_blob($n)&&$W!="")$z=ME.'download='.url_escape($a).'&field='.url_escape($w).$wn;if(!$z&&$W!==null){foreach((array)$re[$w]as$q){if(count($re[$w])==1||end($q["source"])==$w){$z="";foreach($q["source"]as$r=>$Il)$z
.=where_link($r,$q["target"][$r],$J[$Eh][$Il]);$z=($q["db"]!=""?preg_replace('~&db=[^&]*~',"&db=".url_escape($q["db"]),ME):ME).'select='.url_escape($q["table"]).$z;if($q["ns"]!="")$z=preg_replace('~&ns=[^&]*~',"&ns=".url_escape($q["ns"]),$z);if(count($q["source"])==1)break;}}}if($Ce=="count"&&$Bb==""){$z=ME."select=".url_escape($a);$r=0;foreach((array)$_GET["where"]as$V){if(!array_key_exists($V["col"],$vn))$z
.=where_link($r++,$V["col"],$V["val"],$V["op"]);}foreach($vn
as$ig=>$V){if(idx(idx($Ck,$ig,array()),"fun")){$z="";break;}$z
.=where_link($r++,$ig,$V);}}$of=select_value($W,$z,$n,$Jm,($Ce?array():idx($if,$w,array())));if($W===null&&!$Ce&&isset($hf[$w]))$of="<mark>$of</mark>";$t=bracket_escape($wn);$s=h("val[$t][".bracket_escape($w)."]");$Jj=idx(idx($_POST["val"],$t),bracket_escape($w));$Bn=idx($n["privileges"],"update")&&!is_identity_always($n);$kd=$J[$Eh][$w];$ld=!is_array($I[$w])&&!is_blob($n)&&is_utf8($kd)&&!$Ce&&!$n["generated"]&&$Bn;$T=($Ce=="min"||$Ce=="max"?$o[$Bb]["type"]:$n["type"]);$Im=preg_match('~text|json|lob~',$T);$ag=preg_match(number_type(),$T)||preg_match('~^(avg|ceil|char_length|count|count distinct|floor|len|length|round|sum|time_to_sec)$~',$Ce);echo"<td id='$s'".($ag&&($W===null||is_numeric(strip_tags($of))||$T=="money")?" class='number'":"");if(($_GET["modify"]&&$ld&&$kd!==null)||$Jj!==null){$Se=h($Jj!==null?$Jj:$kd);echo">".($Im?"<textarea name='$s' cols='30' rows='".(substr_count($kd,"\n")+1)."'>\n$Se</textarea>":"<input name='$s' value='$Se' size='$Cg[$w]'>");}else{$Ng=strpos($of,"<i>…</i>");$jd=adminer()->editVal($kd,$n);echo($Bn?" data-text='".($Ng?2:($Im?1:0))."'".($ld?"":" data-warning='".'Use the edit link to modify this value.'."'").($ld&&!$Ng&&html_entity_decode(strip_tags($of),ENT_QUOTES,"UTF-8")!==$jd?" data-value='".h($jd)."'":""):"").">$of";}}}if($Sa)echo"<td>";adminer()->backwardKeysPrint($Sa,$J[$Eh]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){$qa=get_settings("adminer_import");if($J||$C||$Xe){$Hd=true;if($_GET["page"]!="last"){if(!$y||(count($J)<$y&&($J||!$C)))$ue=($C?$C*$y:0)+count($J);elseif(JUSH!="sql"||!$Zf){$ue=($Zf?null:found_rows($R,$Z));$Hd=!driver()->hasEstimatedRows();if($ue===null||(!$Hd&&$ue<max(1e4,2*($C+1)*$y))){$ue=first(slow_query(count_rows($a,$Z,$Zf,$Ke)));$Hd=true;}}}if(!support("cursor"))$Xe=(($ue===false?count($J)+1:$ue-$C*$y)>$y);$aj=($y&&($Xe||$C));if($aj)echo($Xe?'<p><a href="'.h(pagination_href($C+1)).'" class="loadmore"'.on('click','selectLoadMore','Loading…').'>'.'Load more data'.'</a>':''),"\n";echo"<div class='footer'><div>\n";if($aj){$Yg=($ue===false?$C+($J?(count($J)>=$y?2:1):0):floor(($ue-1)/$y));echo"<fieldset><legend>".'Page'."</legend>";if(!support("cursor")){echo
pagination(0,$C).($C>5?" …":"");for($r=max(1,$C-4);$r<min($Yg,$C+5);$r++)echo
pagination($r,$C);if($Yg>0)echo($C+5<$Yg?" …":""),($Hd&&$ue!==false?pagination($Yg,$C):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Yg'>".'last'."</a>");}else
echo
pagination(0,$C).($C>1?" …":""),($C?pagination($C,$C):""),($Xe?pagination($C+1,$C)." …":"");echo"</fieldset>\n";}echo"<fieldset>","<legend>".'Whole result'."</legend>";$Oc=($Hd?"":"~ ").$ue;$qg=($ue!==false?($Hd?"":"~ ").lang_format(array('%d row','%d rows'),$ue):"");echo
checkbox("all",1,0,$qg,on('click','countRows',$Oc))."\n","</fieldset>\n";if(adminer()->selectCommandPrint())echo'<fieldset',($_GET["modify"]?'':" title='".'Ctrl+click on a value to modify it.'."'"),'>
<legend><a href=\'',h($_GET["modify"]?remove_from_uri("modify"):relative_uri()."&modify=1"),'\'>Modify</a></legend><div>
<input type=\'submit\' id=\'save\' value=\'Save\'',($_GET["modify"]||$_POST["val"]?'':" class='jsonly' disabled"),'>
</div></fieldset>

<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type=\'submit\' name=\'edit\' value=\'Edit\'>
<input type=\'submit\' name=\'clone\' value=\'Clone\'>
<input type=\'submit\' name=\'delete\' value=\'Delete\'',confirm(),'>
</div></fieldset>
';$se=adminer()->dumpFormat();foreach((array)$_GET["columns"]as$d){if($d["fun"]||($d["col"]!=""&&!isset($o[$d["col"]]))){unset($se['sql']);break;}}if($se){print_fieldset("export",'Export'." <span id='selected2'></span>");$Wi=adminer()->dumpOutput();echo($Wi?html_select("output",$Wi,$qa["output"])." ":""),html_select("format",$se,$qa["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}adminer()->selectEmailPrint(array_filter($pd,'strlen'),$e);echo"</div></div>\n";}if(adminer()->selectImportPrint())echo"<p>","<a href='#import' class='toggle'>".'Import'."</a>","<span id='import'".($_POST["import"]?"":" class='hidden'").">: ",($En?input_hidden(ini_get("session.upload_progress.name"),$En):""),file_input(" name='csv_file'"," ".html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$qa["format"])." <input type='submit' name='import' value='".'Import'."'>".($En?" <progress class='jsonly hidden' max='1' value='0'></progress>":"")),"</span>";echo
input_token(),"</form>\n",(!$Ke&&$L?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["variables"])){$O=isset($_GET["status"]);page_header($O?'Status':'Variables');$Vn=($O?adminer()->showStatus():adminer()->showVariables());if(!$Vn)echo"<p class='message'>".'No rows.'."\n";else{echo"<table>\n";foreach($Vn
as$I){echo"<tr>";$w=array_shift($I);echo"<th><code class='jush-".JUSH.($O?"status":"set")."'>".h($w)."</code>";foreach($I
as$W)echo"<td>".nl_br(h($W));}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: application/json; charset=utf-8");if($_GET["script"]=="db"){$fm=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$A=>$R){json_row("Comment-$A",h($R["Comment"]).($R["Error"]?" <span class='error'>".h($R["Error"])."</span>":""));if(!is_view($R)||preg_match('~materialized~i',$R["Engine"])){foreach(array("Engine","Collation")as$w)json_row("$w-$A",h($R[$w]));foreach(array_keys($fm+array("Auto_increment"=>0,"Rows"=>0))as$w){if(array_key_exists($w,$R))json_row("$w-$A",format_status($R,$w));if($R[$w]!=""&&isset($fm[$w]))$fm[$w]+=($R["Engine"]!="InnoDB"||$w!="Data_free"?$R[$w]:0);}}}if(function_exists('Adminer\db_status'))$fm=db_status();foreach($fm
as$w=>$W)json_row("sum-$w",adminer()->formatSizeValue($W));json_row("");}elseif($_GET["script"]=="kill"){if(!$m)connection()->query("KILL ".number($_POST["kill"]));}else{foreach(count_tables(adminer()->databases(false))as$k=>$W){json_row("tables-$k",format_number($W));json_row("size-$k",db_size($k));}json_row("");}exit;}else{if(!isset($_GET["select"])&&support("single_table")){$S=tables_list();if($S)redirect(ME.(support("table")?"table=":"select=").url_escape(key($S)));}$fh=ME.(isset($_GET["select"])?"select=&":"");$Am=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($Am&&!$m&&!$_POST["search"]){$G=true;$jh="";if(JUSH=="sql"&&$_POST["tables"]&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))queries("SET foreign_key_checks = 0");if($_POST["truncate"]){if($_POST["tables"])$G=truncate_tables($_POST["tables"]);$jh='Tables have been truncated.';}elseif($_POST["move"]){$G=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$jh='Tables have been moved.';}elseif($_POST["copy"]){$G=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$jh='Tables have been copied.';}elseif($_POST["drop"]){if($_POST["views"])$G=drop_views($_POST["views"]);if($G&&$_POST["tables"])$G=drop_tables($_POST["tables"]);$jh='Tables have been dropped.';}elseif(JUSH=="sqlite"&&$_POST["check"]){foreach((array)$_POST["tables"]as$Q){foreach(get_rows("PRAGMA integrity_check(".q($Q).")")as$I)$jh
.="<b>".h($Q)."</b>: ".h($I["integrity_check"])."<br>";}}elseif(JUSH=="mssql"&&$_POST["check"]){foreach((array)$_POST["tables"]as$Q){foreach(get_rows("DBCC CHECKTABLE (".q(table($Q)).") WITH TABLERESULTS")as$I)$jh
.="<b>".h($Q)."</b>: ".h($I["MessageText"])."<br>";}}elseif(JUSH!="sql"){$G=(JUSH=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?" ANALYZE":""),(array)$_POST["tables"]));$jh='Tables have been optimized.';}elseif(!$_POST["tables"])$jh='No tables.';elseif($G=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('Adminer\idf_escape',$_POST["tables"])))){while($I=$G->fetch_assoc())$jh
.="<b>".h($I["Table"])."</b>: ".h($I["Msg_text"])."<br>";}queries_redirect(relative_uri(),$jh,$G);}page_header(($_GET["ns"]==""?'Database'.": ".h(DB):'Schema'.": ".h($_GET["ns"])),$m,true);if(adminer()->homepage()){if($_GET["ns"]!==""){if(support("scheme")&&support("comment")){$f=schema_comment($_GET["ns"]);if($f!="")echo"<p class='nowrap'>".'Comment'.": ".adminer()->commentValue('SCHEMA',$f)."\n";}$Hi=$_GET["order"];$_e=($Hi||support("fast_status"));echo"<div>\n","<h3 id='tables-views'>".'Tables and views'."</h3>\n";$_m=($_e?table_status():tables_list());if(!$_m)echo"<p class='message'>".'No tables.'."\n";else{echo"<form action='' method='post'>\n";if(support("table")){echo"<fieldset><legend>".'Search data in tables'." <span id='selected2'></span></legend><div>",html_select("op",adminer()->operators(),idx($_POST,"op",JUSH=="elastic"?"should":"LIKE %%"))," <input type='search' name='query' value='".h($_POST["query"])."'".on('keydown','submitKeydown','search').">"," <input type='submit' name='search' value='".'Search'."'>\n","</div></fieldset>\n";if(!$m&&$_POST["search"]&&$_POST["query"]!=""){$_GET["where"][0]["op"]=$_POST["op"];search_tables();}}echo"<div class='scrollable'>\n","<table class='nowrap checkable odds'".on('click','tableClick').on('dblclick','tableClick').">\n",'<thead><tr>','<td class="hover"><input id="check-all" type="checkbox" class="jsonly" title="'.'All'.'"'.on('click','formCheck','^(tables|views)\[').'>','<th class="sticky"'.(!$Hi&&JUSH!='sqlite'?" aria-sort='ascending'":'').'><a href="'.h(substr($fh,0,-1)).'">'.'Table'.'</a>';$e=array("Engine"=>array('Engine'.doc_link(array('sql'=>'storage-engines.html'))));if(collations())$e["Collation"]=array('Collation'.doc_link(array('sql'=>'charset-charsets.html','mariadb'=>'supported-character-sets-and-collations/')));if(function_exists('Adminer\alter_table'))$e["Data_length"]=array('Data Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT','oracle'=>'refrn/ALL_TABLES.html')),"create",'Alter table',);if(support("indexes"))$e["Index_length"]=array('Index Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT')),"indexes",'Alter indexes',);$e["Data_free"]=array('Data Free'.doc_link(array('sql'=>'show-table-status.html')),"edit",'New item');if(function_exists('Adminer\alter_table'))$e["Auto_increment"]=array('Auto Increment'.doc_link(array('sql'=>'example-auto-increment.html','mariadb'=>'auto_increment/')),"auto_increment=1&create",'Alter table',);$e["Rows"]=array('Rows'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'catalog-pg-class.html#CATALOG-PG-CLASS','oracle'=>'refrn/ALL_TABLES.html')),"select",'Select data',);if(support("comment"))$e["Comment"]=array('Comment'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-info.html#FUNCTIONS-INFO-COMMENT-TABLE','cockroach'=>'comment-on')),);$Ga=array('Engine','Collation','Comment');foreach($e
as$w=>$d)echo"<th".($Hi==$w?" aria-sort='".(in_array($w,$Ga)?"ascending":"descending")."'":"")."><a href='".h($fh)."order=$w'>$d[0]</a>";echo"<tbody>\n";if($Hi){uasort($_m,function($ha,$Pa)use($Hi,$Ga){$H=($ha[$Hi]<$Pa[$Hi]?-1:($ha[$Hi]>$Pa[$Hi]?1:0));return(in_array($Hi,$Ga)?$H:-$H);});}$S=0;$fm=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach($_m
as$A=>$O){$Yn=($_e?is_view($O):$O!==null&&!preg_match('~table|sequence~i',$O));$O=($_e?$O:array('Engine'=>$O));$s=h("Table-".$A);echo'<tr><td class="hover">'.checkbox(($Yn?"views[]":"tables[]"),$A,in_array("$A",$Am,true),"","","",$s),'<th class="sticky">'.(support("table")||support("indexes")?"<a href='".h(ME)."table=".url_escape($A)."' title='".'Show structure'."' id='$s'>".h($A).'</a>':h($A));if($Yn&&!preg_match('~materialized~i',$O['Engine'])){$Om='View';echo'<td colspan="'.(count($e)-(support("comment")?2:1)).'">'.(support("view")?"<a href='".h(ME)."view=".url_escape($A)."' title='".'Alter view'."'>$Om</a>":$Om),"<td align='right'><a href='".h(ME)."select=".url_escape($A)."' title='".'Select data'."'>?</a>";if(support("comment"))echo'<td>'.h($O['Comment']);}else{if($_e){foreach(array_keys($fm)as$w)$fm[$w]+=($O["Engine"]!="InnoDB"||$w!="Data_free"?idx($O,$w):0);}foreach($e
as$w=>$d){$s=" id='$w-".h($A)."'";echo($d[1]?"<td align='right'>".($w=="Index_length"&&$O["invalid_index"]?"<span class='error'>INVALID</span> ":"")."<a href='".h(ME."$d[1]=").url_escape($A)."'$s title='$d[2]'>".format_status($O,$w)."</a>":"<td$s>".h(idx($O,$w,'?')).($w=="Comment"&&$O["Error"]?" <span class='error'>".h($O["Error"])."</span>":""));}$S++;}echo"\n";}echo"<tr><td class='hover'><th class='sticky'>".sprintf('%d in total',count($_m)),"<td>".h(JUSH=="sql"?get_val("SELECT @@default_storage_engine"):""),(collations()?"<td>".h(db_collation(DB,collations())):'');if($_e&&function_exists('Adminer\db_status'))$fm=db_status();foreach($fm
as$w=>$em)echo($e[$w]?"<td align='right' id='sum-$w'>".($_e?adminer()->formatSizeValue($em):""):"");echo"\n","</table>\n",($_e?'':script("ajaxSetHtml('".js_escape(ME)."script=db');")),"</div>\n";if(!information_schema(DB)){$Rn="<input type='submit' value='".'Vacuum'."'".on_help("VACUUM")."> ";$Bi="<input type='submit' name='optimize' value='".'Optimize'."'".on_help(JUSH=="sql"?"OPTIMIZE TABLE":"VACUUM ANALYZE")."> ";$Tj=(JUSH=="sqlite"?$Rn."<input type='submit' name='check' value='".'Check'."'".on_help("PRAGMA integrity_check")."> ":(JUSH=="pgsql"?$Rn.$Bi:(JUSH=="mssql"?"<input type='submit' name='check' value='".'Check'."'".on_help("DBCC CHECKTABLE")."> ":(JUSH=="sql"?"<input type='submit' value='".'Analyze'."'".on_help("ANALYZE TABLE")."> ".$Bi."<input type='submit' name='check' value='".'Check'."'".on_help("CHECK TABLE")."> "."<input type='submit' name='repair' value='".'Repair'."'".on_help("REPAIR TABLE")."> ":"")))).(function_exists('Adminer\truncate_tables')?"<input type='submit' name='truncate' value='".'Truncate'."'".confirm().on_help(JUSH=="sqlite"?"DELETE":"TRUNCATE".(JUSH=="pgsql"?"":" TABLE"))."> ":"").(function_exists('Adminer\drop_tables')?"<input type='submit' name='drop' value='".'Drop'."'".confirm().on_help("DROP TABLE").">":"");echo($Tj?"<div class='footer'><div>\n<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>$Tj\n</div></fieldset>\n":"");$j=(support("scheme")?adminer()->schemas():adminer()->databases());if(count($j)!=1&&function_exists('Adminer\move_tables')){echo"<fieldset><legend>".'Move to another database'." <span id='selected3'></span></legend><div>";$k=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo($j?html_select("target",$j,$k):'<input name="target" value="'.h($k).'" autocapitalize="off">'),"</label> <input type='submit' name='move' value='".'Move'."'>",(support("copy")?" <input type='submit' name='copy' value='".'Copy'."'> ".checkbox("overwrite",1,$_POST["overwrite"],'overwrite'):""),"</div></fieldset>\n";}echo"<input type='hidden' name='all' value=''".on('click','countTables',$S).">\n",input_token(),"</div></div>\n";}echo"</form>\n",script("tableCheck();");}echo(function_exists('Adminer\alter_table')?"<p class='links hover'><a href='".h(ME)."create='>".'Create table'."</a>\n":''),(support("view")?"<a href='".h(ME)."view='>".'Create view'."</a>\n":""),"</div>\n";if(support("routine")){echo"<div>\n","<h3 id='routines'>".'Routines'."</h3>\n";$Ok=routines();if($Ok){echo"<table class='odds'>\n",'<thead><tr><th>'.'Name'.'<th>'.'Type'.'<th>'.'Return type'."<td class='hover'><tbody>\n";foreach($Ok
as$I){$A=($I["SPECIFIC_NAME"]==$I["ROUTINE_NAME"]?"":"&name=".url_escape($I["ROUTINE_NAME"]));echo'<tr>','<th><a href="'.h(ME.($I["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').url_escape($I["SPECIFIC_NAME"]).$A).'"'.' title="'.($I["DTD_IDENTIFIER"]=="trigger"?'Triggers':'Call').'">'.h($I["ROUTINE_NAME"]).'</a>','<td>'.h($I["ROUTINE_TYPE"]),'<td>'.h($I["DTD_IDENTIFIER"]),'<td class="hover"><a href="'.h(ME.($I["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').url_escape($I["SPECIFIC_NAME"]).$A).'">'.'Alter'."</a>";}echo"</table>\n";}echo'<p class="links hover">'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.'Create procedure'.'</a>':'').'<a href="'.h(ME).'function=">'.'Create function'."</a>\n","</div>\n";}if(support("sequence")){echo"<div>\n","<h3 id='sequences'>".'Sequences'."</h3>\n";$ml=get_vals("SELECT relname FROM pg_class WHERE relkind = 'S' AND relnamespace = ".driver()->nsOid." ORDER BY relname");if($ml){echo"<table class='odds'>\n","<thead><tr><th>".'Name'."<tbody>\n";foreach($ml
as$W)echo"<tr><th><a href='".h(ME)."sequence=".url_escape($W)."'>".h($W)."</a>\n";echo"</table>\n";}echo"<p class='links hover'><a href='".h(ME)."sequence='>".'Create sequence'."</a>\n","</div>\n";}if(support("type")){echo"<div>\n","<h3 id='user-types'>".'User types'."</h3>\n";$On=types();if($On){echo"<table class='odds'>\n","<thead><tr><th>".'Name'."<tbody>\n";foreach($On
as$W)echo"<tr><th><a href='".h(ME)."type=".url_escape($W)."'>".h($W)."</a>\n";echo"</table>\n";}echo"<p class='links hover'><a href='".h(ME)."type='>".'Create type'."</a>\n","</div>\n";}if(support("event")){echo"<div>\n","<h3 id='events'>".'Events'."</h3>\n";$J=get_rows("SHOW EVENTS");if($J){echo"<table>\n","<thead><tr><th>".'Name'."<th>".'Schedule'."<th>".'Start'."<th>".'End'."<td class='hover'><tbody>\n";foreach($J
as$I)echo"<tr>","<th>".h($I["Name"]),"<td>".($I["Execute at"]?'At given time'."<td>".h($I["Execute at"]):'Every'." ".h($I["Interval value"])." ".h($I["Interval field"])."<td>".h($I["Starts"])),"<td>".h($I["Ends"]),'<td class="hover"><a href="'.h(ME).'event='.url_escape($I["Name"]).'">'.'Alter'.'</a>';echo"</table>\n";$Ed=get_val("SELECT @@event_scheduler");if($Ed&&$Ed!="ON")echo"<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($Ed)."\n";}echo'<p class="links hover"><a href="'.h(ME).'event=">'.'Create event'."</a>\n","</div>\n";}}elseif(support("extension")){$Qd=get_rows("SELECT e.extname, e.extversion, n.nspname, obj_description(e.oid, 'pg_extension') AS comment
FROM pg_extension e
JOIN pg_namespace n ON n.oid = e.extnamespace
ORDER BY e.extname");if($Qd){echo"<div>\n","<h3 id='extensions'>".'Extensions'."</h3>\n","<table class='odds'>\n","<thead><tr><th>".'Name'."<th>".'Version'."<th>".'Schema'."<th>".'Comment'."<tbody>\n";foreach($Qd
as$I)echo"<tr><th><code class='jush-pgsqlext'>".h($I["extname"])."</code>","<td>".h($I["extversion"]),"<td><a href='".h(substr(ME,0,-1).url_escape($I["nspname"]))."'>".h($I["nspname"])."</a>","<td>".h($I["comment"]),"\n";echo"</table>\n","</div>\n";}}}}page_footer();