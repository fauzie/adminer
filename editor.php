<?php
/** Adminer Editor - Compact database editor
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2009 Jakub Vrana
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 4.3.1
*/error_reporting(6135);$Hb=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($Hb||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$V){$Be=filter_input_array(constant("INPUT$V"),FILTER_UNSAFE_RAW);if($Be)$$V=$Be;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
lzw_decompress("\0\0\0` \0„\0\n @\0´C„è\"\0`EãQ¸àÿ‡?ÀtvM'”JdÁd\\Œb0\0Ä\"™ÀfÓˆ¤îs5›ÏçÑAXPaJ“0„¥‘8„#RŠT©‘z`ˆ#.©ÇcíXÃşÈ€?À-\0¡Im? .«M¶€\0È¯(Ì‰ıÀ/(%Œ\0");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("\n1Ì‡“ÙŒŞl7œ‡B1„4vb0˜Ífs‘¼ên2BÌÑ±Ù˜Şn:‡#(¼b.\rDc)ÈÈa7E„‘¤Âl¦Ã±”èi1Ìs˜´ç-4™‡fÓ	ÈÎi7†³é†„ŒFÃ©”vt2‚Ó!–r0Ïãã£t~½U'3M€ÉW„B¦'cÍPÂ:6T\rc£A¾zr_îWK¶\r-¼VNFS%~Ãc²Ùí&›\\^ÊrÀ›­æu‚ÅÃôÙ‹4'7k¶è¯ÂãQÔæhš'g\rFB\ryT7SS¥PĞ1=Ç¤cIèÊ:d”ºm>£S8L†Jœt.M¢Š	Ï‹`'C¡¼ÛĞ889¤È QØıŒî2#8Ğ­£’˜6mú²†ğjˆ¢h«<…Œ°«Œ9/ë˜ç:Jê)Ê‚¤\0d>!\0Z‡ˆvì»në¾ğ¼o(Úó¥ÉkÔ7½sàù>Œî†!ĞR\"*nSı\0@P\"Áè’(‹#[¶¥£@g¹oü­’znş9k¤8†nš™ª1´I*ˆô=Ín²¤ª¸è0«c(ö;¾Ã Ğè!°üë*cì÷>Î¬E7DñLJ© 1ÊJ=ÓÚŞ1L‚û?Ğs=#`Ê3\$4ì€úÈuÈ±ÌÎzGÑC YAt«?;×QÒk&ÇïYP¿uèåÇ¯}UaHV%G;ƒs¼”<A\0\\¼ÔPÑ\\Âœ&ÂªóV¦ğ\n£SUÃtíÅÇrŒêˆÆ2¤	l^íZ6˜ej…Á­³A·dó[İsÕ¶ˆJP”ªÊóˆÒŒŠ8è=»ƒ˜à6#Ë‚74*óŸ¨#eÈÀŞ!Õ7{Æ6“¿<oÍCª9v[–MôÅ-`Óõkö>lÙÚ´‹åIªƒHÚ3xú€›äw0t6¾Ã%MR%³½jhÚB˜<´\0ÉAQ<P<:šãu/¤;\\> Ë-¹„ÊˆÍÁQH\nv¡L+vÖÃ¦ì<ï\rèåvàöî¹\\* àÉçÓ´İ¢gŒnË©¸¹TĞ©2P•\r¨øß‹\"+z 8£ ¶:#€ÊèÃÎ2‹ºJ[i—‚£¨;z˜ûÑô¡rÊ3#¨Ù‰ :ãní\rã½ƒeÙpdİİ è2cˆê4²k¿Š£\rG•æE6_³¢ú=î·SZUÇ·ãŒO—ğÅ?¡éÃ¾27£cİĞÅhnÆ‹Üùu3…E>\$J[Áq[\räIŠ6.ÆJÑ\"EPrèGÌŠGA İW¡³\rº´6Ík†¢½`.-¡ªB2>#ìhØÀˆXµøu\r¡¸=‡Z  b€Å(¡â•ƒ!JZÈ”uªyO’×Z¥M˜Õ6lM[0©ä–€àß!ImñyÂ+pÉ#ag¡ŞŒvW˜:qp\"4ÅôòŸãheî…0 dÆAq-\"¡Êƒ§ÆÂ\"2ßÍÒ@‡)o‘,,”¤”×Rb`@©B@ĞÊÊ¯¤Q\n†èŠ·˜Z§„Â™=(r~‰l©~¯ÄhˆsAllÖ\n7»!1! Ü#é\0KË…A“LH(½!ÔÊ˜agH\0ÄT\ni˜/È\$ôöœ4GaÎIÉ!¸.—Å˜5§ÅM\rÑ2‘‚Ï	Ù;ƒ,öLIJ†äÃd?“ÒºÅí%Õˆ:çN@b.âª2í5’«ôt:FAw²B£E,Ç-\$ù£'ê:Ó©u©?¨tK;kÍàĞ¸¨ä\0ouMD)k_Ph˜Ó5MC}7‚…È2‡w.QB¦8)ìÀ†8(DIù=©éy`Øed\0s,`É•jŒHÄ\"(b³¢Ä\\ÙÖnl’\"Ù‚^Ëì€­eE½\nèáë±X!SqXÔÀ\r©Œ€7A±†0ê£y7pPìºğçaüA˜4‡ƒ(yÖJwm…2…òª.¯ó‰†¬fp°ÏË;Æ„5ÂJÍcÜqŒQz\\\0[Hÿ 3‘f'b¼µFğøÆY¨\nAà9_§IŞà(›fÎÓq‘VÑÅ¨äõ³4µÜò¹‚„RIÂYå&J’ºFñ}£{FTëh9[7‚h\0à‹TÖ^ö´jËÔq×j‹õÕ”§­€cÂWIğ@`_ÑsVDçÃ[¾\"{1áÈ3‡•	Úô»÷¨<…l¼l.±éĞ[¨»Ş#Ä¯º¤b°Şu­¶/Ÿ\0ä3ævaå«‘Dp>‚2½IDWÕš¢kKAŒ»hHš]¨FÆ•ã€W–!]‰Ê÷ltÜÉ•RÌ­4L[äĞÅYC cTj<c;s‡q¸p€’ Ä5ÅtóJ§m6—%J”-\\õÍeB=iß-ğ*%´·¦÷¢TV‹[&M8ó*\r™bÄY\rihˆ	„ÙPŒ9T×-VÉ°ZÔúüÛ³ù49Î²™”ƒp-´`ÙÿÜÌÇGÉÙ›' ì¹ĞôM²:§Å™')0ƒYuÚcí:!«x#×¦è¦-l*®TÉ\nYläù†š³‹*D ÉXë V\\îËØÚ®ó]y¯ƒ\nÖ2r,É†åç,ÎdĞ×~Å³İ÷s³-ç+Ö»uÛ]£\\BÀ¶¥²Iw€Ô!ƒOsØÔ¯lò YCÁĞ‚È:À@ÆœEUË._)Ë9uÿzœµvÏˆSÎ´¬1ï—é_(Sõéqé½¡r¾yuî+¥Z*ê6€uy¿<ÉÇõz\\|ØZK;áe›×–úoYåÀ;°óÃl‘´xöà-7×ô÷4rkYY?ÔÕGWt¡¼÷[KÚšÃåzoØ<¿€Íà	têÏô†¶¾ù—É€gçıjğ‡_!ào…êÊ\$ Iã¹ÀI¿.&Ü5½P\\—›]¥Àè†Æ\nCØ.ïÖ_¹ø;¿çs«iíS/gÖ:ŞPëÉ³auNÍ¨|Æaáå¯á™º¬±¢µÓâ«6ØÓÙ3Ö|÷¾‡Ä{©ceîXòù<°e«p>Ní}´í~âÿO¾¡÷Ò™Bl¿ÂjÊ/¢óKø¼Hdch-Ë¾ıºØšğ/ûîÜşÎäùÈ¶·hÔ0ÀĞŒÈĞ‹ÌúÎH©8 ");}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("f:›ŒgCI¼Ü\n0›†S‘Øa9œÅS`°Çˆ“Œ&Ó(°Ên0˜†QIìÒf‰›\$±At^ sG²Étf6eŒ§yŒÊ()LäSÁÀP'…ÂáÌR'Ífq]\"˜s>	)â‘`œH2ŠEq9ˆÊ?ˆ*)‰”t'°Ï§Ø\n	\ræs<ŒPi2INÆ*(=2ÌgXá¸è.3™N„Y4èB<’L—üîi©Ì¥2İ´z=š0HøĞ'·êŒšÃuÆtt:œÂ¡Èêe¹]`pX9ŒŞo5šgòóIœÜ,2O4ãŞÑ…MÆS¸(ˆa…Š#¾Äàç’ïø|¹G‚bèôüxœ^Z[Çä™G¼ÎuTvª(Òm@Vò¸(†¼ÈbN<ŠÈ`æâXä1É+Œä9J8Â2\r£K¶9ğhå	 Áè`…‹ÆëI8ä›±S±ãt÷2ƒ+,£ÆIºã £pæ9m@Ğ:ƒ€æáxï)…ĞüC…Ãxä3…ñ4P7áü-4Çr\"p3Fhà…-5ƒ”U4Í‰¸\\6°ƒ<D\$®l—9ÍR4t7ƒdD3µpŞÎ“kÌ:)\\;° ĞÔğ\r@t…\$4O£<ş†!pdÇÔÚQJ\rÌHî}:&Œ¨ˆÂÈ„Á5YWJ­˜‹±Â`ÓN£èbKNSÉÀÉa§•ƒ´d>2WñÅ…bDj:9[21c„»È€:Xé@ËqË#“›4íL™'J”©+DHeÒ3¬.«O ÇKË°“ˆ…pV…át2Œwp;Æ“…íÿ\r?èOzDq.ª°Ğ-†\"ìZñ®cèX3!/>PúFìsØÉ²±Ã0Í(òóˆ°Ê£€àŒ‚T63sVQo¸€SÎ‘ b²ß…^r\$É@C© r2)©Œ£ “VÀ)+nÜ·zÃÁúålÚè{³K#…À9‹{†Û¯lÀºìmĞQ¨ëh»*É—PÄ:¡c˜]´7ãàø=¡LŸŒi;”2û¿§­ÜÒ<\\Jí¤Øb¥n”…ƒ¥nÁ_iÓ´îJ\n†¢¨âòõC:ª„‘`N4¶Ì–È'Aw:4}ÊÛ£ÁW\080‘ÇL3õÊJ;èiú)\\„=/NŠu=ZV6&ceaè±ÂpŞÖ.[ëvŠtPZŞèX`Ö”õŒ+zú'¦ê9½.\$\$…Ó@\n\ré]_ïÙ®¢Âh¨kk¬Ms>`Ì–ƒj¹%\\9Ğ¶ÆÔ('°jAˆ>BCd\"K\$	CAÆ ä„¤.Â².`‰â.EÑæ´–ÌÃyy\0‹D2Ï8t	Ğ6†Ã8¬FL«´×ŞíâŒB*¬ğ,Ò|\nx\\@ °@¸Ø3r ¬­ğÎWKQb,%…¯´DBfØÈ³D|ÍŒËE0/2>£Y!Ä†'õ™`æf™mHº<BãB0\r*\0Gxò‰nêY4‚¶¾Œ,L²©º–öÅ%SÆ,ıv‡0ê‘–XòQÄ1†HId`‡!.ÔVÊ›H/ÅúÃ—ÀHãù0ÆUÁ¸0Â™©`îLI©8ÖÃkŠ”2Œ4JYNÅ&8xä¥JØk:AKã¡nWØ!¦¿Iï;'ô³\":2ğê‹4Í~óJ„8ô£á’‘¨âG‡™\"MÊ=\rZ'nÇi9F§œ“™rÆ’RÊt‚3\0Ÿ”Ò²Â2µy‚B^òèb'´ÒzÈÉ²(­#”d9Itµ&WØjNa¨ÚC(¥ j”Ä–?h‰ÂØj†¡™©Ö„Z\$0«¡Ò¯´J	A_\n†!TOó4Œ<{aôú?˜æo ú‚-¹–ÃÏ?Hlÿ\"2ƒy™=Úë¨ R©ğœÑ„àš°–ÍŠëP&åG›ÀÁ4ƒË%()¤\r5Mª‚‰ÓLTí\0ÀºxBIç=ltvÄ2Jhvû´~/:èpı×:8\"Ğ´5¡«‰0î#*ì7ªøúÜ\nàq×>è¡G\$°â…):	ƒ»\"ù#ë¦KfI‡!vö+?{¡Íÿ¾Qg¥{ÏR÷Q øCäª}Õ#¸éiIbgà„ÔXàÄÃÂù}ÅË`‹}3—%@îÁ{_kø}0ä±şÈ—Öp !°aï—<7«e•‰ÖF‡?¦¸¡î½XüDù­Ñ, ØÊCk‰ƒíU™ØL>£1‹§ÜÜ‡¥ã‡Œp0#Ä\$²ÅâV)pYs5A˜:°ÊUÈ(9…5×™,F+&Ÿ*{âŒ-£Íìç:÷Ší :7¦ş:Ê™yPãè—´ŠÀXÏ+¤’\nŞI;üş\\s„÷Pà÷1‘‘ìÈr©¦NJËAT'-£”òk?ƒÙY@“¡Ïö±fÇÍbñ’”RîJÏiömÖB~ò©”K\rK«œtª4à÷;OŠKc”9%Hì5àÍd¢3ÙÀe8j¿P÷±[sğ™9,ƒÄ˜—bzK‰µÁòW&e¢d8­ú§)ÄùĞuP°¿¾œ>‘#	P&„ÃP	ƒpbaÀ¨Í¨yñ£æß\$3}ïĞ{»áİhyÊ(ÖdWø±ŠÅÙËĞ_±:°'AØ‚‡PæÃI\"Ù!ï[`ûn8å»i/@ÈäğP	ĞfœĞå†©ˆV	À£•sÑCß8¡˜°Ny‰hÜñtEnAj.-åÄ6£ÀqwJÜ?œÃ¹”AÌhu	è™Ôsé¤AíáO7“·j›æ\n	]¿0›^Œ	ƒ\nYÁš\$„Î–_\rş\$…u*÷Ş¡ÒEx/d¼pdRİdÂõ:¤IÀoDÁ›®‡sQÉ™fàÜI¤öâ8Ñ,óêKÑÏIsM@aq\n/™†ÌM˜R¹ ¢®Cş-aÇa£¾™/·Hº!å4F…óIÿÉÅpÅ”MÏ«Šş_Ø‡HÀ9{‚.´\$WÄûò#{ÌúÒ®Šü·:‰Súƒ£(À'lÕMY»:lÊ¤mD\$°\0¦¬×\0©ê´èº'¢~à¶ Z@º€¶ŒàVâº€L\"ãjnæ¾5€ğNlŠÌşşKšfj&›Mí•OøÓdbÓ°NÓğ´O\$i)ŞNĞ(¿Ğ!P)Ğ0+Ğ6HpN¦¢ØF‚àîĞ–¬?L\nìÁ-h0˜Í,.e­¤\"‡6m#õ	é’ıo&ò°ÒŞë¶gåZÅ@Pşk­&Ìº_ì¼%\\\\'ÀíÌ\0]\$(€5ƒN fqÎ|Ñp¨ `…â<ğîRîÎú úìÍ¸ş˜ìI\0Ñ¥şãL¦|Ç\$ı(Şê¸Å¬TkQ6k°B@0HõŒ˜Pƒ\rÀšˆ#Îušš+ï€Ü²pTşĞZÑ±/ü\r y´Pp%\0^8ÆÒ\r¤Àµ‘˜4¬İ\0‡¡1²¦ğQn*+B8qÂà ÏF§\0Ğ´ÌÜ±Šâ¢\næş«.®âûH’±%Ââ3ñÀ&PF–Ñ„İX¢Ïğ¨`O ±£Ë9R­B´’\r ì10Îì½‡O ¬X«Ì^+¯öïæÌiĞÈArD¬4ëÙ`Ê-š.i`4ò'Å,\$²VÅ,_c~;Bn<’1\$,]%Èlù‰D=âå\$Ñ).1b%gœ»z‰Ğü}ËÊG2¯1]8uPòïìD]	/z îä¼g‘+'„7D\0]ú²¾aäpV’ÒL€ó0÷+`Xpä˜ ÊîdË-hû+h(ÀÔäÀĞ\n„¿²fª“§s2,µ2‡@z Â.I``‡*óÌ1l?±“RËñ±W.ï.c%\$‹¢¿s+4òÑëã6Ã\$Cr‡F)0‚ô\rÓ1-ˆ`„ÓŒ ÈjÆL\r­8–²©l—0È©*.L‡KpÃ\r¤·\r£/rûLÄa8â2KÅ1nêb‚ÿ4“LíÒêË,¶Ë¬¾ïó©\nij–érói#Ç©8»1èbxÓ2à¾\$Nú\re‰ ¨\r\"8ˆ'‘³’³ª\r´-ƒPàÀYñ0°£Yb”S\0¹°ø\\jK+q6V hê1óU€z`pïò¬R±E“CÓî”X»Åöõ%”F	5ñF4f-¶tPåID6\0NFä®Nå4’_Ş0ó©\riL@Ên´¸Pé^‚¬Âîëê¾%'ËLÔ¨êG”€ïôàò\\‘Fèâ€ÊâÄ&ã4ÂĞI*5ÉO†‰OÀ·Pã)8¾)­*L;ğ½4EÌ]´\$óğÌ\0L3ËE ïEk„ÑÃÎIt%eÆ\nbô(ÎëSMª}²Ş7sÇ‹Û¯)gi¤ØFà†&êº-XH° ¼ìğBÃM5~jrPjÌ¾-|Ö¤´9 ¨–p¨¢5Š¸;o–5²õ¶–‰c\ndÒÕuÊ	 ÂÔ\rLÕhú'\nå''ò< O\0ğœe,. ú–\"t\r¯k^Ã{_Ã_gTµı`\0Ñ	Mk?ˆ2®\r:Db%È]UÍ[²1óùcuµ[¡[9]Õà´/ EV>k@éa\r_\"b6]ö E–DöQ^)È™•@Ps€ITr vT\0ØVR™W@ ëiR”2/¢b…,Xr€¬	jç^µï0•õ«ÿk’’üCÇlˆ“fl'8E–É©‘oµW¶¨ûI¤şmpë£¶ı/&ï´+´òıéX¯çk.şğ\\ÿ4Ó0d“r\"“°kb\nH\$Ğ¢†±KCâåo§OoíõG%\r¦äûÏl²–ùëÒö Õ]àP7\"*hPP€\rc¡_€X[`æêöî â7å:`‹˜Uà°ß¨–I¢ƒwêdã¶;·Š	—“x t†À‚8d\0@Ôjw˜v¶ —»{÷Â b	¨pü æñcyíö\n€ , u<	âÕ\"uyE:í÷Z`<LF£ë¨ü2ÑğcwşS¸d†%uw€÷u•ß€#pqNNßÀN’\n·#@ E‚#\"@|d%kwc\"* xò„àw‚˜\0uX.¦Âl&Xe‚ÎM†ÃB'“ @6ChÂ»`S¥‚wÿ‡G Êé¢ÓˆC[V×ø1àß\rÆşb\"Ğ\n\0\n`©JÀ¸º+—a1¦\"lW}z–]zjdO„>!‚ˆG\0[\\å¢ïF|…®¾ À^\0ZJ`î¨b·`#ãŒ5€É`W÷“E;„â(à°¹!`È¯`\"»~Eß’gƒŠhVGrı‚_ï±uåj¢Q‘*d'2g/Ø-\n€h¤ ^Àda)×•E:HØhäËãvEvˆs—Bàí­‡9wƒ\0ÜßMøßĞãŒùW”NLù…• Ë3Àè-ø=#@%øD!ÊXL*ô…êV…¹‰¸;…Ò1„ãÇ˜Ø‡ƒMk‰™X‹ØŠÀñÒâ&ù™Ùr<å[%Uøeq˜WW‰#\$ÛèŒlIVàA†W_GÆVú„šF\"&fĞ(çošdV1ƒšó*wr0±F\"Œ¯¤‰H˜‰ÑvçTq hw*†“†@Œƒˆƒs….¯0g8ú1_€zfö»äA)À¢—+<¯Òu\"­F_lO#Œ®Õân{‚XYwv,ÕÇ–½ HàÓÇ“cÌ{n7á<8ÌYfB°¬Á\0øFe–\ràù:érŠ¸\$gy¬šÌÀ6=pÍ;4ó›9\0öb%a2BÉ Ê\n ¤	(€\rº@GFª@ÙÃû%²€ß²Å 1ÓâĞ“qbÂs+£Zg´%@t%–à š3±€Ô\räD(àLÒ÷v.šğ€\\\rR ^ã€é°	×A2Â\rÖ¾;yvïˆÀ€~ÀUâÂ*¢Ğ@õø<š‰Ëq¢Wa¢·S¢úW¡„=yEš<Vº@<ù±1·³3€Ğ\$»äì\$üPÅD€¾w2UÏØ;Ï]ù¡Ç%!\n¯ó²ÃD)‘¦ó„‚I/h~Çàè‚<Â+ø€î0€ÜåsÓ34‡-´ŠèåGÓØ4ïÂòŒÅT\nÏóu3ëü<TôckşÊÅWÃÎW»âGU—±Çfme÷\\¤D!*vÅx3ƒiû2ªw2§Œ1ª|Œ\$&Ô‰+€†bG\$v!rò*-ù4­quÈ\rÑyLã0üÊtXÆ…Æ·²çÚßI“b dúµSÓï<öaòÎËç¹G´~G¬Ïà„m äg¢x-T’¶ÏÉY’á™–¼ª!(wHÃ\nãš4aäg)`ñƒ%Ó@rüY%’¬(qÜX¿˜à!cÕ\$Dy±]mjöbpR4RõÜRÄïuÜX†äpó/6 h€eâªÕ+ùnëš@ cHĞÉ¹ğ ¹Ÿ{Å”æÅÚ.\\bmVşpPé·`bQrãP€ê\0`\$WÖWÑRq2x%bY—1ÜÛû€¬²º³±>ùÍÌ1æÌìu&b\nVÌo°Nïj\n€ŞâÄEÄ†ØC3âåG\0²ış ep–£Â<~B^ A¾%/9°;åv\\¥[â›Hşl^U»ÔûÇÓh¿š¢.\\YÉ}+	üYtŞÅıqÆ‰6¢\nsüã\0‘¾äü¦c:–3¹*}ÉÜñŒÚØ7z\$ë·d\\Âç\"«· W³£Y³û+²ûIèã³e½¢Ú\0çùSï9©½ºDt[rû×šºCÖ©bÍ~é¦³DÕw/½l—¿ÉL`Õ~ ØU‡øV_àŞ\0\rŞwó)¶bÆ¦8–ï”¿Gò Ü%¼º¾WŞUáöA¾v(ùHFg¨ X©cº¢n9ö2—ô—Õn12lÀ˜\rä?\"tï\0XPÉsùÿ”Ymf±‚‚F?mñÒx5™}Hì_´ìXcûy”áÎºCåÕ.Ä\$¯`¶köd5.rx>Ç¢7şæîsÛn3¼“Ó<¼´g„ˆğ§åO(\\@èWò:PáÏƒ{ó­_Fà†”hgLÓ >°<¦6é~'²K„0Õ?@ãìEAå_Ü Ô8H.LG<øÄíd  Y¬oú¡ÒÜü«€­ÚkF<Òıp‘¾(Ûj\$9ò¬ª˜Ä?¥ÙV P?)ÍòØ¤DŠuŠ°Lb¨­àj¹Áâç}	ğ\0„Kp³ì7ÆÒZsÔ€àõDì)ë\rù:°™JQÖ“}¥”\$¨€bÁ³AØu)»Ü‡\"XÅÁMŞ%pQPÑQÂÍ\$@³¦\\’\0ÚVõ7ªÁ¨TMøX×É*ôò #)G\\ ĞK—«ÂMë0–=¯JÜ&½`¿\"x‹_ÒËb™B`–C?/ˆ´ÅªÚBUuë«83ûNR¤Îñ_]Nî‰TèÜ¿D„ª…òwI¥\n‘2«„€D:Yî‚	ôì­q1°Ğ`B‚à—F!]W5‰,:˜1Ã(­0ÇtQôFÙ	ˆŠpÕ7’'!\"@€Õ8Õ0Ü`œ7ˆ\rhC’\nXÓ¡¥\rA–CÖ	m	Q€Ìß&l&€|cåL‚d¢\"#·\"ø’¨ÂPK‚‚ôß56HÊ„bÊ—&åÄ+Î#f¨V¹/Søˆh(Td±uÀ€ô‹<=ˆxdª8Š:ám!ğ6ü8€,JP~RP–DüZÂÃu€±‘O¡`Å0X¦šŠA¢ˆwØ£0eh^Câ’\$Ä ¢ãÈ8A‚¡PçÅ(ê#+K×N4¡%\n•BÇÄ>âÜ2‡%ìüë{0ùÆ‹¾ò0ÏŸ¢/¦Ñzˆğ Ï	P¢‘“Êš @Égæ¥Dªzö¤D§ÔŠşYà¥“4œ¥¯,%l3WâÍUş²¢ÃúLr[º°øÔ…9H¥ÌgT`@7È\r‚¹N£ï– èÛ€û°>™÷ÖèñÈÁi\0pzŒ”¢Hå¢ääÑr;`IV]EÈöÖGYz\rôó˜EnÈò/{˜Ä¶™Ç	dQsbTj\$g>™~=îËŠ“Jâƒ·ÄûKYˆ£ªZ1Ò…KçRÒ”Ô»ÀÖ6Aî\r¡{”CÒt¸ 5º€=n²şcö¥N)œ”Lº=/ğ<‹<;bŒU¡†VáŸši¼1ã¤~ÕÊ`HıµªEË‡Šé_¹œ‘R<\$b9Æ¿Õ¼\n>{h@›<Ù7¸¶Bçë[„\r€<4IbM),”@Ù%082é¢¹‘˜sCO#ô2A¼ır‘ä†à|oé+Iaï¬Öu¬…Ó+&P3ÈpRM\\lˆR/³¸I¹JR“ŒŒ^j0Šäğ2~JCFdjV!¡K2;“ä‚\$vĞ6ˆ;m¤iÎd\"F		ÈUìòBtP„zµ£Ì");}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress(compile_file('','minify_js'));}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo"GIF89a\0\0\0001îîî\0\0€™™™\0\0\0!ù\0\0\0,\0\0\0\0\0\0!„©ËíMñÌ*)¾oú¯) q•¡eˆµî#ÄòLË\0;";break;case"cross.gif":echo"GIF89a\0\0\0001îîî\0\0€™™™\0\0\0!ù\0\0\0,\0\0\0\0\0\0#„©Ëí#\naÖFo~yÃ._wa”á1ç±JîGÂL×6]\0\0;";break;case"up.gif":echo"GIF89a\0\0\0001îîî\0\0€™™™\0\0\0!ù\0\0\0,\0\0\0\0\0\0 „©ËíMQN\nï}ôa8ŠyšaÅ¶®\0Çò\0;";break;case"down.gif":echo"GIF89a\0\0\0001îîî\0\0€™™™\0\0\0!ù\0\0\0,\0\0\0\0\0\0 „©ËíMñÌ*)¾[Wş\\¢ÇL&ÙœÆ¶•\0Çò\0;";break;case"arrow.gif":echo"GIF89a\0\n\0€\0\0€€€ÿÿÿ!ù\0\0\0,\0\0\0\0\0\n\0\0‚i–±‹”ªÓ²Ş»\0\0;";break;}}exit;}function
connection(){global$f;return$f;}function
adminer(){global$b;return$b;}function
idf_unescape($gc){$wc=substr($gc,-1);return
str_replace($wc.$wc,$wc,substr($gc,1,-1));}function
escape_string($V){return
substr(q($V),1,-1);}function
number($V){return
preg_replace('~[^0-9]+~','',$V);}function
remove_slashes($qd,$Hb=false){if(get_magic_quotes_gpc()){while(list($w,$V)=each($qd)){foreach($V
as$qc=>$U){unset($qd[$w][$qc]);if(is_array($U)){$qd[$w][stripslashes($qc)]=$U;$qd[]=&$qd[$w][stripslashes($qc)];}else$qd[$w][stripslashes($qc)]=($Hb?$U:stripslashes($U));}}}}function
bracket_escape($gc,$ua=false){static$se=array(':'=>':1',']'=>':2','['=>':3','"'=>':4');return
strtr($gc,($ua?array_flip($se):$se));}function
charset($f){return(version_compare($f->server_info,"5.5.3")>=0?"utf8mb4":"utf8");}function
h($Q){return
str_replace("\0","&#0;",htmlspecialchars($Q,ENT_QUOTES,'utf-8'));}function
nbsp($Q){return(trim($Q)!=""?h($Q):"&nbsp;");}function
nl_br($Q){return
str_replace("\n","<br>",$Q);}function
checkbox($C,$W,$Fa,$tc="",$Vc="",$Ia="",$uc=""){$K="<input type='checkbox' name='$C' value='".h($W)."'".($Fa?" checked":"").($uc?" aria-labelledby='$uc'":"").($Vc?' onclick="'.h($Vc).'"':'').">";return($tc!=""||$Ia?"<label".($Ia?" class='$Ia'":"").">$K".h($tc)."</label>":$K);}function
optionlist($D,$Jd=null,$Ge=false){$K="";foreach($D
as$qc=>$U){$Zc=array($qc=>$U);if(is_array($U)){$K.='<optgroup label="'.h($qc).'">';$Zc=$U;}foreach($Zc
as$w=>$V)$K.='<option'.($Ge||is_string($w)?' value="'.h($w).'"':'').(($Ge||is_string($w)?(string)$w:$V)===$Jd?' selected':'').'>'.h($V);if(is_array($U))$K.='</optgroup>';}return$K;}function
html_select($C,$D,$W="",$Uc=true,$uc=""){if($Uc)return"<select name='".h($C)."'".(is_string($Uc)?' onchange="'.h($Uc).'"':"").($uc?" aria-labelledby='$uc'":"").">".optionlist($D,$W)."</select>";$K="";foreach($D
as$w=>$V)$K.="<label><input type='radio' name='".h($C)."' value='".h($w)."'".($w==$W?" checked":"").">".h($V)."</label>";return$K;}function
select_input($c,$D,$W="",$id=""){return($D?"<select$c><option value=''>$id".optionlist($D,$W,true)."</select>":"<input$c size='10' value='".h($W)."' placeholder='$id'>");}function
confirm(){return" onclick=\"return confirm('".'Are you sure?'."');\"";}function
print_fieldset($r,$yc,$Ke=false,$Vc=""){echo"<fieldset><legend><a href='#fieldset-$r' onclick=\"".h($Vc)."return !toggle('fieldset-$r');\">$yc</a></legend><div id='fieldset-$r'".($Ke?"":" class='hidden'").">\n";}function
bold($Aa,$Ia=""){return($Aa?" class='active $Ia'":($Ia?" class='$Ia'":""));}function
odd($K=' class="odd"'){static$q=0;if(!$K)$q=-1;return($q++%2?$K:'');}function
js_escape($Q){return
addcslashes($Q,"\r\n'\\/");}function
json_row($w,$V=null){static$Ib=true;if($Ib)echo"{";if($w!=""){echo($Ib?"":",")."\n\t\"".addcslashes($w,"\r\n\t\"\\/").'": '.($V!==null?'"'.addcslashes($V,"\r\n\"\\/").'"':'null');$Ib=false;}else{echo"\n}\n";$Ib=true;}}function
ini_bool($kc){$V=ini_get($kc);return(preg_match('~^(on|true|yes)$~i',$V)||(int)$V);}function
sid(){static$K;if($K===null)$K=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$K;}function
set_password($X,$O,$T,$G){$_SESSION["pwds"][$X][$O][$T]=($_COOKIE["adminer_key"]&&is_string($G)?array(encrypt_string($G,$_COOKIE["adminer_key"])):$G);}function
get_password(){$K=get_session("pwds");if(is_array($K))$K=($_COOKIE["adminer_key"]?decrypt_string($K[0],$_COOKIE["adminer_key"]):false);return$K;}function
q($Q){global$f;return$f->quote($Q);}function
get_vals($I,$d=0){global$f;$K=array();$J=$f->query($I);if(is_object($J)){while($L=$J->fetch_row())$K[]=$L[$d];}return$K;}function
get_key_vals($I,$g=null,$le=0){global$f;if(!is_object($g))$g=$f;$K=array();$g->timeout=$le;$J=$g->query($I);$g->timeout=0;if(is_object($J)){while($L=$J->fetch_row())$K[$L[0]]=$L[1];}return$K;}function
get_rows($I,$g=null,$k="<p class='error'>"){global$f;$Sa=(is_object($g)?$g:$f);$K=array();$J=$Sa->query($I);if(is_object($J)){while($L=$J->fetch_assoc())$K[]=$L;}elseif(!$J&&!is_object($g)&&$k&&defined("PAGE_HEADER"))echo$k.error()."\n";return$K;}function
unique_array($L,$t){foreach($t
as$s){if(preg_match("~PRIMARY|UNIQUE~",$s["type"])){$K=array();foreach($s["columns"]as$w){if(!isset($L[$w]))continue
2;$K[$w]=$L[$w];}return$K;}}}function
escape_key($w){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$w,$_))return$_[1].idf_escape(idf_unescape($_[2])).$_[3];return
idf_escape($w);}function
where($Y,$m=array()){global$f,$v;$K=array();foreach((array)$Y["where"]as$w=>$V){$w=bracket_escape($w,1);$d=escape_key($w);$K[]=$d.($v=="sql"&&preg_match('~^[0-9]*\\.[0-9]*$~',$V)?" LIKE ".q(addcslashes($V,"%_\\")):($v=="mssql"?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$V)):" = ".unconvert_field($m[$w],q($V))));if($v=="sql"&&preg_match('~char|text~',$m[$w]["type"])&&preg_match("~[^ -@]~",$V))$K[]="$d = ".q($V)." COLLATE ".charset($f)."_bin";}foreach((array)$Y["null"]as$w)$K[]=escape_key($w)." IS NULL";return
implode(" AND ",$K);}function
where_check($V,$m=array()){parse_str($V,$Ea);remove_slashes(array(&$Ea));return
where($Ea,$m);}function
where_link($q,$d,$W,$Xc="="){return"&where%5B$q%5D%5Bcol%5D=".urlencode($d)."&where%5B$q%5D%5Bop%5D=".urlencode(($W!==null?$Xc:"IS NULL"))."&where%5B$q%5D%5Bval%5D=".urlencode($W);}function
convert_fields($e,$m,$N=array()){$K="";foreach($e
as$w=>$V){if($N&&!in_array(idf_escape($w),$N))continue;$oa=convert_field($m[$w]);if($oa)$K.=", $oa AS ".idf_escape($w);}return$K;}function
cookie($C,$W,$Ac=2592000){global$aa;return
header("Set-Cookie: $C=".urlencode($W).($Ac?"; expires=".gmdate("D, d M Y H:i:s",time()+$Ac)." GMT":"")."; path=".preg_replace('~\\?.*~','',$_SERVER["REQUEST_URI"]).($aa?"; secure":"")."; HttpOnly; SameSite=lax",false);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function
stop_session(){if(!ini_bool("session.use_cookies"))session_write_close();}function&get_session($w){return$_SESSION[$w][DRIVER][SERVER][$_GET["username"]];}function
set_session($w,$V){$_SESSION[$w][DRIVER][SERVER][$_GET["username"]]=$V;}function
auth_url($X,$O,$T,$h=null){global$jb;preg_match('~([^?]*)\\??(.*)~',remove_from_uri(implode("|",array_keys($jb))."|username|".($h!==null?"db|":"").session_name()),$_);return"$_[1]?".(sid()?SID."&":"").($X!="server"||$O!=""?urlencode($X)."=".urlencode($O)."&":"")."username=".urlencode($T).($h!=""?"&db=".urlencode($h):"").($_[2]?"&$_[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($z,$A=null){if($A!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($z!==null?$z:$_SERVER["REQUEST_URI"]))][]=$A;}if($z!==null){if($z=="")$z=".";header("Location: $z");exit;}}function
query_redirect($I,$z,$A,$yd=true,$zb=true,$Bb=false,$ke=""){global$f,$k,$b;if($zb){$Ud=microtime(true);$Bb=!$f->query($I);$ke=format_time($Ud);}$Td="";if($I)$Td=$b->messageQuery($I,$ke);if($Bb){$k=error().$Td;return
false;}if($yd)redirect($z,$A.$Td);return
true;}function
queries($I){global$f;static$td=array();static$Ud;if(!$Ud)$Ud=microtime(true);if($I===null)return
array(implode("\n",$td),format_time($Ud));$td[]=(preg_match('~;$~',$I)?"DELIMITER ;;\n$I;\nDELIMITER ":$I).";";return$f->query($I);}function
apply_queries($I,$fe,$wb='table'){foreach($fe
as$R){if(!queries("$I ".$wb($R)))return
false;}return
true;}function
queries_redirect($z,$A,$yd){list($td,$ke)=queries(null);return
query_redirect($td,$z,$A,$yd,false,!$yd,$ke);}function
format_time($Ud){return
sprintf('%.3f s',max(0,microtime(true)-$Ud));}function
remove_from_uri($ed=""){return
substr(preg_replace("~(?<=[?&])($ed".(SID?"":"|".session_name()).")=[^&]*&~",'',"$_SERVER[REQUEST_URI]&"),0,-1);}function
pagination($F,$Ya){return" ".($F==$Ya?$F+1:'<a href="'.h(remove_from_uri("page").($F?"&page=$F".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($F+1)."</a>");}function
get_file($w,$bb=false){$Eb=$_FILES[$w];if(!$Eb)return
null;foreach($Eb
as$w=>$V)$Eb[$w]=(array)$V;$K='';foreach($Eb["error"]as$w=>$k){if($k)return$k;$C=$Eb["name"][$w];$qe=$Eb["tmp_name"][$w];$Ta=file_get_contents($bb&&preg_match('~\\.gz$~',$C)?"compress.zlib://$qe":$qe);if($bb){$Ud=substr($Ta,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$Ud,$zd))$Ta=iconv("utf-16","utf-8",$Ta);elseif($Ud=="\xEF\xBB\xBF")$Ta=substr($Ta,3);$K.=$Ta."\n\n";}else$K.=$Ta;}return$K;}function
upload_error($k){$Gc=($k==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($k?'Unable to upload a file.'.($Gc?" ".sprintf('Maximum allowed file size is %sB.',$Gc):""):'File does not exist.');}function
repeat_pattern($H,$zc){return
str_repeat("$H{0,65535}",$zc/65535)."$H{0,".($zc%65535)."}";}function
is_utf8($V){return(preg_match('~~u',$V)&&!preg_match('~[\\0-\\x8\\xB\\xC\\xE-\\x1F]~',$V));}function
shorten_utf8($Q,$zc=80,$ae=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$zc).")($)?)u",$Q,$_))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$zc).")($)?)",$Q,$_);return
h($_[1]).$ae.(isset($_[2])?"":"<i>...</i>");}function
format_number($V){return
strtr(number_format($V,0,".",','),preg_split('~~u','0123456789',-1,PREG_SPLIT_NO_EMPTY));}function
friendly_url($V){return
preg_replace('~[^a-z0-9_]~i','-',$V);}function
hidden_fields($qd,$ic=array()){while(list($w,$V)=each($qd)){if(!in_array($w,$ic)){if(is_array($V)){foreach($V
as$qc=>$U)$qd[$w."[$qc]"]=$U;}else
echo'<input type="hidden" name="'.h($w).'" value="'.h($V).'">';}}}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
table_status1($R,$Cb=false){$K=table_status($R,$Cb);return($K?$K:array("Name"=>$R));}function
column_foreign_keys($R){global$b;$K=array();foreach($b->foreignKeys($R)as$Nb){foreach($Nb["source"]as$V)$K[$V][]=$Nb;}return$K;}function
enum_input($ve,$c,$l,$W,$sb=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$l["length"],$Dc);$K=($sb!==null?"<label><input type='$ve'$c value='$sb'".((is_array($W)?in_array($sb,$W):$W===0)?" checked":"")."><i>".'empty'."</i></label>":"");foreach($Dc[1]as$q=>$V){$V=stripcslashes(str_replace("''","'",$V));$Fa=(is_int($W)?$W==$q+1:(is_array($W)?in_array($q+1,$W):$W===$V));$K.=" <label><input type='$ve'$c value='".($q+1)."'".($Fa?' checked':'').'>'.h($b->editVal($V,$l)).'</label>';}return$K;}function
input($l,$W,$o){global$f,$xe,$b,$v;$C=h(bracket_escape($l["field"]));echo"<td class='function'>";if(is_array($W)&&!$o){$na=array($W);if(version_compare(PHP_VERSION,5.4)>=0)$na[]=JSON_PRETTY_PRINT;$W=call_user_func_array('json_encode',$na);$o="json";}$Bd=($v=="mssql"&&$l["auto_increment"]);if($Bd&&!$_POST["save"])$o=null;$Tb=(isset($_GET["select"])||$Bd?array("orig"=>'original'):array())+$b->editFunctions($l);$c=" name='fields[$C]'";if($l["type"]=="enum")echo
nbsp($Tb[""])."<td>".$b->editInput($_GET["edit"],$l,$c,$W);else{$Ib=0;foreach($Tb
as$w=>$V){if($w===""||!$V)break;$Ib++;}$Uc=($Ib?" onchange=\"var f = this.form['function[".h(js_escape(bracket_escape($l["field"])))."]']; if ($Ib > f.selectedIndex) f.selectedIndex = $Ib;\" onkeyup='keyupChange.call(this);'":"");$c.=$Uc;$Yb=(in_array($o,$Tb)||isset($Tb[$o]));echo(count($Tb)>1?"<select name='function[$C]' onchange='functionChange(this);'".on_help("getTarget(event).value.replace(/^SQL\$/, '')",1).">".optionlist($Tb,$o===null||$Yb?$o:"")."</select>":nbsp(reset($Tb))).'<td>';$mc=$b->editInput($_GET["edit"],$l,$c,$W);if($mc!="")echo$mc;elseif(preg_match('~bool~',$l["type"]))echo"<input type='hidden'$c value='0'>"."<input type='checkbox'".(in_array(strtolower($W),array('1','t','true','y','yes','on'))?" checked='checked'":"")."$c value='1'>";elseif($l["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$l["length"],$Dc);foreach($Dc[1]as$q=>$V){$V=stripcslashes(str_replace("''","'",$V));$Fa=(is_int($W)?($W>>$q)&1:in_array($V,explode(",",$W),true));echo" <label><input type='checkbox' name='fields[$C][$q]' value='".(1<<$q)."'".($Fa?' checked':'')."$Uc>".h($b->editVal($V,$l)).'</label>';}}elseif(preg_match('~blob|bytea|raw|file~',$l["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$C'$Uc>";elseif(($he=preg_match('~text|lob~',$l["type"]))||preg_match("~\n~",$W)){if($he&&$v!="sqlite")$c.=" cols='50' rows='12'";else{$M=min(12,substr_count($W,"\n")+1);$c.=" cols='30' rows='$M'".($M==1?" style='height: 1.2em;'":"");}echo"<textarea$c>".h($W).'</textarea>';}elseif($o=="json"||preg_match('~^jsonb?$~',$l["type"]))echo"<textarea$c cols='50' rows='12' class='jush-js'>".h($W).'</textarea>';else{$Ic=(!preg_match('~int~',$l["type"])&&preg_match('~^(\\d+)(,(\\d+))?$~',$l["length"],$_)?((preg_match("~binary~",$l["type"])?2:1)*$_[1]+($_[3]?1:0)+($_[2]&&!$l["unsigned"]?1:0)):($xe[$l["type"]]?$xe[$l["type"]]+($l["unsigned"]?0:1):0));if($v=='sql'&&$f->server_info>=5.6&&preg_match('~time~',$l["type"]))$Ic+=7;echo"<input".((!$Yb||$o==="")&&preg_match('~(?<!o)int~',$l["type"])&&!preg_match('~\[\]~',$l["full_type"])?" type='number'":"")." value='".h($W)."'".($Ic?" data-maxlength='$Ic'":"").(preg_match('~char|binary~',$l["type"])&&$Ic>20?" size='40'":"")."$c>";}}}function
process_input($l){global$b;$gc=bracket_escape($l["field"]);$o=$_POST["function"][$gc];$W=$_POST["fields"][$gc];if($l["type"]=="enum"){if($W==-1)return
false;if($W=="")return"NULL";return+$W;}if($l["auto_increment"]&&$W=="")return
null;if($o=="orig")return($l["on_update"]=="CURRENT_TIMESTAMP"?idf_escape($l["field"]):false);if($o=="NULL")return"NULL";if($l["type"]=="set")return
array_sum((array)$W);if($o=="json"){$o="";$W=json_decode($W,true);if(!is_array($W))return
false;return$W;}if(preg_match('~blob|bytea|raw|file~',$l["type"])&&ini_bool("file_uploads")){$Eb=get_file("fields-$gc");if(!is_string($Eb))return
false;return
q($Eb);}return$b->processInput($l,$W,$o);}function
fields_from_edit(){global$i;$K=array();foreach((array)$_POST["field_keys"]as$w=>$V){if($V!=""){$V=bracket_escape($V);$_POST["function"][$V]=$_POST["field_funs"][$w];$_POST["fields"][$V]=$_POST["field_vals"][$w];}}foreach((array)$_POST["fields"]as$w=>$V){$C=bracket_escape($w,1);$K[$C]=array("field"=>$C,"privileges"=>array("insert"=>1,"update"=>1),"null"=>1,"auto_increment"=>($w==$i->primary),);}return$K;}function
search_tables(){global$b,$f;$_GET["where"][0]["op"]="LIKE %%";$_GET["where"][0]["val"]=$_POST["query"];$Qb=false;foreach(table_status('',true)as$R=>$S){$C=$b->tableName($S);if(isset($S["Engine"])&&$C!=""&&(!$_POST["tables"]||in_array($R,$_POST["tables"]))){$J=$f->query("SELECT".limit("1 FROM ".table($R)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($R),array())),1));if(!$J||$J->fetch_row()){if(!$Qb){echo"<ul>\n";$Qb=true;}echo"<li>".($J?"<a href='".h(ME."select=".urlencode($R)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$C</a>\n":"$C: <span class='error'>".error()."</span>\n");}}}echo($Qb?"</ul>":"<p class='message'>".'No tables.')."\n";}function
dump_headers($fc,$Lc=false){global$b;$K=$b->dumpHeaders($fc,$Lc);$cd=$_POST["output"];if($cd!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($fc).".$K".($cd!="file"&&!preg_match('~[^0-9a-z]~',$cd)?".$cd":""));session_write_close();ob_flush();flush();return$K;}function
dump_csv($L){foreach($L
as$w=>$V){if(preg_match("~[\"\n,;\t]~",$V)||$V==="")$L[$w]='"'.str_replace('"','""',$V).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$L)."\r\n";}function
apply_sql_function($o,$d){return($o?($o=="unixepoch"?"DATETIME($d, '$o')":($o=="count distinct"?"COUNT(DISTINCT ":strtoupper("$o("))."$d)"):$d);}function
get_temp_dir(){$K=ini_get("upload_tmp_dir");if(!$K){if(function_exists('sys_get_temp_dir'))$K=sys_get_temp_dir();else{$Fb=@tempnam("","");if(!$Fb)return
false;$K=dirname($Fb);unlink($Fb);}}return$K;}function
password_file($Va){$Fb=get_temp_dir()."/adminer.key";$K=@file_get_contents($Fb);if($K||!$Va)return$K;$Rb=@fopen($Fb,"w");if($Rb){chmod($Fb,0660);$K=rand_string();fwrite($Rb,$K);fclose($Rb);}return$K;}function
rand_string(){return
md5(uniqid(mt_rand(),true));}function
select_value($V,$y,$l,$ie){global$b,$aa;if(is_array($V)){$K="";foreach($V
as$qc=>$U)$K.="<tr>".($V!=array_values($V)?"<th>".h($qc):"")."<td>".select_value($U,$y,$l,$ie);return"<table cellspacing='0'>$K</table>";}if(!$y)$y=$b->selectLink($V,$l);if($y===null){if(is_mail($V))$y="mailto:$V";if($rd=is_url($V))$y=(($rd=="http"&&$aa)||preg_match('~WebKit|Firefox~i',$_SERVER["HTTP_USER_AGENT"])?$V:"https://www.adminer.org/redirect/?url=".urlencode($V));}$K=$b->editVal($V,$l);if($K!==null){if($K==="")$K="&nbsp;";elseif(!is_utf8($K))$K="\0";elseif($ie!=""&&is_shortable($l))$K=shorten_utf8($K,max(0,+$ie));else$K=h($K);}return$b->selectVal($K,$y,$l,$V);}function
is_mail($pb){$pa='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$ib='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$H="$pa+(\\.$pa+)*@($ib?\\.)+$ib";return
is_string($pb)&&preg_match("(^$H(,\\s*$H)*\$)i",$pb);}function
is_url($Q){$ib='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return(preg_match("~^(https?)://($ib?\\.)+$ib(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$Q,$_)?strtolower($_[1]):"");}function
is_shortable($l){return
preg_match('~char|text|lob|geometry|point|linestring|polygon|string|bytea~',$l["type"]);}function
count_rows($R,$Y,$u,$p){global$v;$I=" FROM ".table($R).($Y?" WHERE ".implode(" AND ",$Y):"");return($u&&($v=="sql"||count($p)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$p).")$I":"SELECT COUNT(*)".($u?" FROM (SELECT 1$I$Ub) x":$I));}function
slow_query($I){global$b,$re;$h=$b->database();$le=$b->queryTimeout();if(support("kill")&&is_object($g=connect())&&($h==""||$g->select_db($h))){$sc=$g->result(connection_id());echo'<script type="text/javascript">
var timeout = setTimeout(function () {
	ajax(\'',js_escape(ME),'script=kill\', function () {
	}, \'token=',$re,'&kill=',$sc,'\');
}, ',1000*$le,');
</script>
';}else$g=null;ob_flush();flush();$K=@get_key_vals($I,$g,$le);if($g){echo"<script type='text/javascript'>clearTimeout(timeout);</script>\n";ob_flush();flush();}return
array_keys($K);}function
get_token(){$wd=rand(1,1e6);return($wd^$_SESSION["token"]).":$wd";}function
verify_token(){list($re,$wd)=explode(":",$_POST["token"]);return($wd^$_SESSION["token"])==$re;}function
lzw_decompress($za){$gb=256;$_a=8;$Ka=array();$Cd=0;$Dd=0;for($q=0;$q<strlen($za);$q++){$Cd=($Cd<<8)+ord($za[$q]);$Dd+=8;if($Dd>=$_a){$Dd-=$_a;$Ka[]=$Cd>>$Dd;$Cd&=(1<<$Dd)-1;$gb++;if($gb>>$_a)$_a++;}}$fb=range("\0","\xFF");$K="";foreach($Ka
as$q=>$Ja){$ob=$fb[$Ja];if(!isset($ob))$ob=$Oe.$Oe[0];$K.=$ob;if($q)$fb[]=$Oe.$ob[0];$Oe=$ob;}return$K;}function
on_help($Pa,$Qd=0){return" onmouseover='helpMouseover(this, event, ".h($Pa).", $Qd);' onmouseout='helpMouseout(this, event);'";}function
edit_form($a,$m,$L,$Ee){global$b,$v,$re,$k;$ee=$b->tableName(table_status1($a,true));page_header(($Ee?'Edit':'Insert'),$k,array("select"=>array($a,$ee)),$ee);if($L===false)echo"<p class='error'>".'No rows.'."\n";echo'<form action="" method="post" enctype="multipart/form-data" id="form">
';if(!$m)echo"<p class='error'>".'You have no privileges to update this table.'."\n";else{echo"<table cellspacing='0' onkeydown='return editingKeydown(event);'>\n";foreach($m
as$C=>$l){echo"<tr><th>".$b->fieldName($l);$cb=$_GET["set"][bracket_escape($C)];if($cb===null){$cb=$l["default"];if($l["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$cb,$zd))$cb=$zd[1];}$W=($L!==null?($L[$C]!=""&&$v=="sql"&&preg_match("~enum|set~",$l["type"])?(is_array($L[$C])?array_sum($L[$C]):+$L[$C]):$L[$C]):(!$Ee&&$l["auto_increment"]?"":(isset($_GET["select"])?false:$cb)));if(!$_POST["save"]&&is_string($W))$W=$b->editVal($W,$l);$o=($_POST["save"]?(string)$_POST["function"][$C]:($Ee&&$l["on_update"]=="CURRENT_TIMESTAMP"?"now":($W===false?null:($W!==null?'':'NULL'))));if(preg_match("~time~",$l["type"])&&$W=="CURRENT_TIMESTAMP"){$W="";$o="now";}input($l,$W,$o);echo"\n";}if(!support("table"))echo"<tr>"."<th><input name='field_keys[]' onkeyup='keyupChange.call(this);' onchange='fieldChange(this);' value=''>"."<td class='function'>".html_select("field_funs[]",$b->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>"."\n";echo"</table>\n";}echo"<p>\n";if($m){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"]))echo"<input type='submit' name='insert' value='".($Ee?'Save and continue edit'."' onclick='return !ajaxForm(this.form, \"".'Saving'.'...", this)':'Save and insert next')."' title='Ctrl+Shift+Enter'>\n";}echo($Ee?"<input type='submit' name='delete' value='".'Delete'."'".confirm().">\n":($_POST||!$m?"":"<script type='text/javascript'>focus(document.getElementById('form').getElementsByTagName('td')[1].firstChild);</script>\n"));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$re,'">
</form>
';}global$b,$f,$jb,$mb,$ub,$k,$Tb,$Vb,$aa,$lc,$v,$ba,$vc,$Tc,$hd,$Xd,$Zb,$re,$ue,$xe,$De,$ca;if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";$aa=$_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off");@ini_set("session.use_trans_sid",false);session_cache_limiter("");if(!defined("SID")){session_name("adminer_sid");$fd=array(0,preg_replace('~\\?.*~','',$_SERVER["REQUEST_URI"]),"",$aa);if(version_compare(PHP_VERSION,'5.2.0')>=0)$fd[]=true;call_user_func_array('session_set_cookie_params',$fd);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$Hb);if(get_magic_quotes_runtime())set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",20);function
get_lang(){return'en';}function
lang($te,$Qc=null){if(is_array($te)){$kd=($Qc==1?0:1);$te=$te[$kd];}$te=str_replace("%d","%s",$te);$Qc=format_number($Qc);return
sprintf($te,$Qc);}if(extension_loaded('pdo')){class
Min_PDO
extends
PDO{var$_result,$server_info,$affected_rows,$errno,$error;function
__construct(){global$b;$kd=array_search("SQL",$b->operators);if($kd!==false)unset($b->operators[$kd]);}function
dsn($kb,$T,$G){try{parent::__construct($kb,$T,$G);}catch(Exception$xb){auth_error(h($xb->getMessage()));}$this->setAttribute(13,array('Min_PDOStatement'));$this->server_info=@$this->getAttribute(4);}function
query($I,$ye=false){$J=parent::query($I);$this->error="";if(!$J){list(,$this->errno,$this->error)=$this->errorInfo();return
false;}$this->store_result($J);return$J;}function
multi_query($I){return$this->_result=$this->query($I);}function
store_result($J=null){if(!$J){$J=$this->_result;if(!$J)return
false;}if($J->columnCount()){$J->num_rows=$J->rowCount();return$J;}$this->affected_rows=$J->rowCount();return
true;}function
next_result(){if(!$this->_result)return
false;$this->_result->_offset=0;return@$this->_result->nextRowset();}function
result($I,$l=0){$J=$this->query($I);if(!$J)return
false;$L=$J->fetch();return$L[$l];}}class
Min_PDOStatement
extends
PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(2);}function
fetch_row(){return$this->fetch(3);}function
fetch_field(){$L=(object)$this->getColumnMeta($this->_offset++);$L->orgtable=$L->table;$L->orgname=$L->name;$L->charsetnr=(in_array("blob",(array)$L->flags)?63:0);return$L;}}}$jb=array();class
Min_SQL{var$_conn;function
__construct($f){$this->_conn=$f;}function
select($R,$N,$Y,$p,$E=array(),$x=1,$F=0,$od=false){global$b,$v;$u=(count($p)<count($N));$I=$b->selectQueryBuild($N,$Y,$p,$E,$x,$F);if(!$I)$I="SELECT".limit(($_GET["page"]!="last"&&+$x&&$p&&$u&&$v=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$N)."\nFROM ".table($R),($Y?"\nWHERE ".implode(" AND ",$Y):"").($p&&$u?"\nGROUP BY ".implode(", ",$p):"").($E?"\nORDER BY ".implode(", ",$E):""),($x!=""?+$x:null),($F?$x*$F:0),"\n");$Ud=microtime(true);$K=$this->_conn->query($I);if($od)echo$b->selectQuery($I,format_time($Ud));return$K;}function
delete($R,$ud,$x=0){$I="FROM ".table($R);return
queries("DELETE".($x?limit1($I,$ud):" $I$ud"));}function
update($R,$P,$ud,$x=0,$Ld="\n"){$He=array();foreach($P
as$w=>$V)$He[]="$w = $V";$I=table($R)." SET$Ld".implode(",$Ld",$He);return
queries("UPDATE".($x?limit1($I,$ud):" $I$ud"));}function
insert($R,$P){return
queries("INSERT INTO ".table($R).($P?" (".implode(", ",array_keys($P)).")\nVALUES (".implode(", ",$P).")":" DEFAULT VALUES"));}function
insertUpdate($R,$M,$nd){return
false;}function
begin(){return
queries("BEGIN");}function
commit(){return
queries("COMMIT");}function
rollback(){return
queries("ROLLBACK");}}$jb=array("server"=>"MySQL")+$jb;if(!defined("DRIVER")){$ld=array("MySQLi","MySQL","PDO_MySQL");define("DRIVER","server");if(extension_loaded("mysqli")){class
Min_DB
extends
MySQLi{var$extension="MySQLi";function
__construct(){parent::init();}function
connect($O="",$T="",$G="",$Za=null,$jd=null,$Rd=null){mysqli_report(MYSQLI_REPORT_OFF);list($dc,$jd)=explode(":",$O,2);$K=@$this->real_connect(($O!=""?$dc:ini_get("mysqli.default_host")),($O.$T!=""?$T:ini_get("mysqli.default_user")),($O.$T.$G!=""?$G:ini_get("mysqli.default_pw")),$Za,(is_numeric($jd)?$jd:ini_get("mysqli.default_port")),(!is_numeric($jd)?$jd:$Rd));return$K;}function
set_charset($Da){if(parent::set_charset($Da))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $Da");}function
result($I,$l=0){$J=$this->query($I);if(!$J)return
false;$L=$J->fetch_array();return$L[$l];}function
quote($Q){return"'".$this->escape_string($Q)."'";}}}elseif(extension_loaded("mysql")&&!(ini_get("sql.safe_mode")&&extension_loaded("pdo_mysql"))){class
Min_DB{var$extension="MySQL",$server_info,$affected_rows,$errno,$error,$_link,$_result;function
connect($O,$T,$G){$this->_link=@mysql_connect(($O!=""?$O:ini_get("mysql.default_host")),("$O$T"!=""?$T:ini_get("mysql.default_user")),("$O$T$G"!=""?$G:ini_get("mysql.default_password")),true,131072);if($this->_link)$this->server_info=mysql_get_server_info($this->_link);else$this->error=mysql_error();return(bool)$this->_link;}function
set_charset($Da){if(function_exists('mysql_set_charset')){if(mysql_set_charset($Da,$this->_link))return
true;mysql_set_charset('utf8',$this->_link);}return$this->query("SET NAMES $Da");}function
quote($Q){return"'".mysql_real_escape_string($Q,$this->_link)."'";}function
select_db($Za){return
mysql_select_db($Za,$this->_link);}function
query($I,$ye=false){$J=@($ye?mysql_unbuffered_query($I,$this->_link):mysql_query($I,$this->_link));$this->error="";if(!$J){$this->errno=mysql_errno($this->_link);$this->error=mysql_error($this->_link);return
false;}if($J===true){$this->affected_rows=mysql_affected_rows($this->_link);$this->info=mysql_info($this->_link);return
true;}return
new
Min_Result($J);}function
multi_query($I){return$this->_result=$this->query($I);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($I,$l=0){$J=$this->query($I);if(!$J||!$J->num_rows)return
false;return
mysql_result($J->_result,0,$l);}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
__construct($J){$this->_result=$J;$this->num_rows=mysql_num_rows($J);}function
fetch_assoc(){return
mysql_fetch_assoc($this->_result);}function
fetch_row(){return
mysql_fetch_row($this->_result);}function
fetch_field(){$K=mysql_fetch_field($this->_result,$this->_offset++);$K->orgtable=$K->table;$K->orgname=$K->name;$K->charsetnr=($K->blob?63:0);return$K;}function
__destruct(){mysql_free_result($this->_result);}}}elseif(extension_loaded("pdo_mysql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_MySQL";function
connect($O,$T,$G){$this->dsn("mysql:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\\d)~',';port=\\1',$O)),$T,$G);return
true;}function
set_charset($Da){$this->query("SET NAMES $Da");}function
select_db($Za){return$this->query("USE ".idf_escape($Za));}function
query($I,$ye=false){$this->setAttribute(1000,!$ye);return
parent::query($I,$ye);}}}class
Min_Driver
extends
Min_SQL{function
insert($R,$P){return($P?parent::insert($R,$P):queries("INSERT INTO ".table($R)." ()\nVALUES ()"));}function
insertUpdate($R,$M,$nd){$e=array_keys(reset($M));$md="INSERT INTO ".table($R)." (".implode(", ",$e).") VALUES\n";$He=array();foreach($e
as$w)$He[$w]="$w = VALUES($w)";$ae="\nON DUPLICATE KEY UPDATE ".implode(", ",$He);$He=array();$zc=0;foreach($M
as$P){$W="(".implode(", ",$P).")";if($He&&(strlen($md)+$zc+strlen($W)+strlen($ae)>1e6)){if(!queries($md.implode(",\n",$He).$ae))return
false;$He=array();$zc=0;}$He[]=$W;$zc+=strlen($W)+2;}return
queries($md.implode(",\n",$He).$ae);}}function
idf_escape($gc){return"`".str_replace("`","``",$gc)."`";}function
table($gc){return
idf_escape($gc);}function
connect(){global$b,$xe,$Xd;$f=new
Min_DB;$Xa=$b->credentials();if($f->connect($Xa[0],$Xa[1],$Xa[2])){$f->set_charset(charset($f));$f->query("SET sql_quote_show_create = 1, autocommit = 1");if(version_compare($f->server_info,'5.7.8')>=0){$Xd['Strings'][]="json";$xe["json"]=4294967295;}return$f;}$K=$f->error;if(function_exists('iconv')&&!is_utf8($K)&&strlen($Fd=iconv("windows-1250","utf-8",$K))>strlen($K))$K=$Fd;return$K;}function
get_databases($Jb){global$f;$K=get_session("dbs");if($K===null){$I=($f->server_info>=5?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA":"SHOW DATABASES");$K=($Jb?slow_query($I):get_vals($I));restart_session();set_session("dbs",$K);stop_session();}return$K;}function
limit($I,$Y,$x,$Rc=0,$Ld=" "){return" $I$Y".($x!==null?$Ld."LIMIT $x".($Rc?" OFFSET $Rc":""):"");}function
limit1($I,$Y){return
limit($I,$Y,1);}function
db_collation($h,$Na){global$f;$K=null;$Va=$f->result("SHOW CREATE DATABASE ".idf_escape($h),1);if(preg_match('~ COLLATE ([^ ]+)~',$Va,$_))$K=$_[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$Va,$_))$K=$Na[$_[1]][-1];return$K;}function
engines(){$K=array();foreach(get_rows("SHOW ENGINES")as$L){if(preg_match("~YES|DEFAULT~",$L["Support"]))$K[]=$L["Engine"];}return$K;}function
logged_user(){global$f;return$f->result("SELECT USER()");}function
tables_list(){global$f;return
get_key_vals($f->server_info>=5?"SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME":"SHOW TABLES");}function
count_tables($ab){$K=array();foreach($ab
as$h)$K[$h]=count(get_vals("SHOW TABLES IN ".idf_escape($h)));return$K;}function
table_status($C="",$Cb=false){global$f;$K=array();foreach(get_rows($Cb&&$f->server_info>=5?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($C!=""?"AND TABLE_NAME = ".q($C):"ORDER BY Name"):"SHOW TABLE STATUS".($C!=""?" LIKE ".q(addcslashes($C,"%_\\")):""))as$L){if($L["Engine"]=="InnoDB")$L["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\\1',$L["Comment"]);if(!isset($L["Engine"]))$L["Comment"]="";if($C!="")return$L;$K[$L["Name"]]=$L;}return$K;}function
is_view($S){return$S["Engine"]===null;}function
fk_support($S){global$f;return
preg_match('~InnoDB|IBMDB2I~i',$S["Engine"])||(preg_match('~NDB~i',$S["Engine"])&&version_compare($f->server_info,'5.6')>=0);}function
fields($R){$K=array();foreach(get_rows("SHOW FULL COLUMNS FROM ".table($R))as$L){preg_match('~^([^( ]+)(?:\\((.+)\\))?( unsigned)?( zerofill)?$~',$L["Type"],$_);$K[$L["Field"]]=array("field"=>$L["Field"],"full_type"=>$L["Type"],"type"=>$_[1],"length"=>$_[2],"unsigned"=>ltrim($_[3].$_[4]),"default"=>($L["Default"]!=""||preg_match("~char|set~",$_[1])?$L["Default"]:null),"null"=>($L["Null"]=="YES"),"auto_increment"=>($L["Extra"]=="auto_increment"),"on_update"=>(preg_match('~^on update (.+)~i',$L["Extra"],$_)?$_[1]:""),"collation"=>$L["Collation"],"privileges"=>array_flip(preg_split('~, *~',$L["Privileges"])),"comment"=>$L["Comment"],"primary"=>($L["Key"]=="PRI"),);}return$K;}function
indexes($R,$g=null){$K=array();foreach(get_rows("SHOW INDEX FROM ".table($R),$g)as$L){$C=$L["Key_name"];$K[$C]["type"]=($C=="PRIMARY"?"PRIMARY":($L["Index_type"]=="FULLTEXT"?"FULLTEXT":($L["Non_unique"]?($L["Index_type"]=="SPATIAL"?"SPATIAL":"INDEX"):"UNIQUE")));$K[$C]["columns"][]=$L["Column_name"];$K[$C]["lengths"][]=($L["Index_type"]=="SPATIAL"?null:$L["Sub_part"]);$K[$C]["descs"][]=null;}return$K;}function
foreign_keys($R){global$f,$Tc;static$H='`(?:[^`]|``)+`';$K=array();$Wa=$f->result("SHOW CREATE TABLE ".table($R),1);if($Wa){preg_match_all("~CONSTRAINT ($H) FOREIGN KEY ?\\(((?:$H,? ?)+)\\) REFERENCES ($H)(?:\\.($H))? \\(((?:$H,? ?)+)\\)(?: ON DELETE ($Tc))?(?: ON UPDATE ($Tc))?~",$Wa,$Dc,PREG_SET_ORDER);foreach($Dc
as$_){preg_match_all("~$H~",$_[2],$Sd);preg_match_all("~$H~",$_[5],$ge);$K[idf_unescape($_[1])]=array("db"=>idf_unescape($_[4]!=""?$_[3]:$_[4]),"table"=>idf_unescape($_[4]!=""?$_[4]:$_[3]),"source"=>array_map('idf_unescape',$Sd[0]),"target"=>array_map('idf_unescape',$ge[0]),"on_delete"=>($_[6]?$_[6]:"RESTRICT"),"on_update"=>($_[7]?$_[7]:"RESTRICT"),);}}return$K;}function
view($C){global$f;return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\\s+AS\\s+~isU','',$f->result("SHOW CREATE VIEW ".table($C),1)));}function
collations(){$K=array();foreach(get_rows("SHOW COLLATION")as$L){if($L["Default"])$K[$L["Charset"]][-1]=$L["Collation"];else$K[$L["Charset"]][]=$L["Collation"];}ksort($K);foreach($K
as$w=>$V)asort($K[$w]);return$K;}function
information_schema($h){global$f;return($f->server_info>=5&&$h=="information_schema")||($f->server_info>=5.5&&$h=="performance_schema");}function
error(){global$f;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$f->error));}function
create_database($h,$Ma){return
queries("CREATE DATABASE ".idf_escape($h).($Ma?" COLLATE ".q($Ma):""));}function
drop_databases($ab){$K=apply_queries("DROP DATABASE",$ab,'idf_escape');restart_session();set_session("dbs",null);return$K;}function
rename_database($C,$Ma){$K=false;if(create_database($C,$Ma)){$_d=array();foreach(tables_list()as$R=>$ve)$_d[]=table($R)." TO ".idf_escape($C).".".table($R);$K=(!$_d||queries("RENAME TABLE ".implode(", ",$_d)));if($K)queries("DROP DATABASE ".idf_escape(DB));restart_session();set_session("dbs",null);}return$K;}function
auto_increment(){$ta=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$s){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$s["columns"],true)){$ta="";break;}if($s["type"]=="PRIMARY")$ta=" UNIQUE";}}return" AUTO_INCREMENT$ta";}function
alter_table($R,$C,$m,$Kb,$Qa,$tb,$Ma,$sa,$gd){$ma=array();foreach($m
as$l)$ma[]=($l[1]?($R!=""?($l[0]!=""?"CHANGE ".idf_escape($l[0]):"ADD"):" ")." ".implode($l[1]).($R!=""?$l[2]:""):"DROP ".idf_escape($l[0]));$ma=array_merge($ma,$Kb);$Vd=($Qa!==null?" COMMENT=".q($Qa):"").($tb?" ENGINE=".q($tb):"").($Ma?" COLLATE ".q($Ma):"").($sa!=""?" AUTO_INCREMENT=$sa":"");if($R=="")return
queries("CREATE TABLE ".table($C)." (\n".implode(",\n",$ma)."\n)$Vd$gd");if($R!=$C)$ma[]="RENAME TO ".table($C);if($Vd)$ma[]=ltrim($Vd);return($ma||$gd?queries("ALTER TABLE ".table($R)."\n".implode(",\n",$ma).$gd):true);}function
alter_indexes($R,$ma){foreach($ma
as$w=>$V)$ma[$w]=($V[2]=="DROP"?"\nDROP INDEX ".idf_escape($V[1]):"\nADD $V[0] ".($V[0]=="PRIMARY"?"KEY ":"").($V[1]!=""?idf_escape($V[1])." ":"")."(".implode(", ",$V[2]).")");return
queries("ALTER TABLE ".table($R).implode(",",$ma));}function
truncate_tables($fe){return
apply_queries("TRUNCATE TABLE",$fe);}function
drop_views($Je){return
queries("DROP VIEW ".implode(", ",array_map('table',$Je)));}function
drop_tables($fe){return
queries("DROP TABLE ".implode(", ",array_map('table',$fe)));}function
move_tables($fe,$Je,$ge){$_d=array();foreach(array_merge($fe,$Je)as$R)$_d[]=table($R)." TO ".idf_escape($ge).".".table($R);return
queries("RENAME TABLE ".implode(", ",$_d));}function
copy_tables($fe,$Je,$ge){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($fe
as$R){$C=($ge==DB?table("copy_$R"):idf_escape($ge).".".table($R));if(!queries("\nDROP TABLE IF EXISTS $C")||!queries("CREATE TABLE $C LIKE ".table($R))||!queries("INSERT INTO $C SELECT * FROM ".table($R)))return
false;}foreach($Je
as$R){$C=($ge==DB?table("copy_$R"):idf_escape($ge).".".table($R));$Ie=view($R);if(!queries("DROP VIEW IF EXISTS $C")||!queries("CREATE VIEW $C AS $Ie[select]"))return
false;}return
true;}function
trigger($C){if($C=="")return
array();$M=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($C));return
reset($M);}function
triggers($R){$K=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_\\")))as$L)$K[$L["Trigger"]]=array($L["Timing"],$L["Event"]);return$K;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
routine($C,$ve){global$f,$ub,$lc,$xe;$la=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$we="((".implode("|",array_merge(array_keys($xe),$la)).")\\b(?:\\s*\\(((?:[^'\")]|$ub)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";$H="\\s*(".($ve=="FUNCTION"?"":$lc).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$we";$Va=$f->result("SHOW CREATE $ve ".idf_escape($C),2);preg_match("~\\(((?:$H\\s*,?)*)\\)\\s*".($ve=="FUNCTION"?"RETURNS\\s+$we\\s+":"")."(.*)~is",$Va,$_);$m=array();preg_match_all("~$H\\s*,?~is",$_[1],$Dc,PREG_SET_ORDER);foreach($Dc
as$ed){$C=str_replace("``","`",$ed[2]).$ed[3];$m[]=array("field"=>$C,"type"=>strtolower($ed[5]),"length"=>preg_replace_callback("~$ub~s",'normalize_enum',$ed[6]),"unsigned"=>strtolower(preg_replace('~\\s+~',' ',trim("$ed[8] $ed[7]"))),"null"=>1,"full_type"=>$ed[4],"inout"=>strtoupper($ed[1]),"collation"=>strtolower($ed[9]),);}if($ve!="FUNCTION")return
array("fields"=>$m,"definition"=>$_[11]);return
array("fields"=>$m,"returns"=>array("type"=>$_[12],"length"=>$_[13],"unsigned"=>$_[15],"collation"=>$_[16]),"definition"=>$_[17],"language"=>"SQL",);}function
routines(){return
get_rows("SELECT ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));}function
routine_languages(){return
array();}function
last_id(){global$f;return$f->result("SELECT LAST_INSERT_ID()");}function
explain($f,$I){return$f->query("EXPLAIN ".($f->server_info>=5.1?"PARTITIONS ":"").$I);}function
found_rows($S,$Y){return($Y||$S["Engine"]!="InnoDB"?null:$S["Rows"]);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($Gd){return
true;}function
create_sql($R,$sa){global$f;$K=$f->result("SHOW CREATE TABLE ".table($R),1);if(!$sa)$K=preg_replace('~ AUTO_INCREMENT=\\d+~','',$K);return$K;}function
truncate_sql($R){return"TRUNCATE ".table($R);}function
use_sql($Za){return"USE ".idf_escape($Za);}function
trigger_sql($R,$Yd){$K="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_\\")),null,"-- ")as$L)$K.="\n".($Yd=='CREATE+ALTER'?"DROP TRIGGER IF EXISTS ".idf_escape($L["Trigger"]).";;\n":"")."CREATE TRIGGER ".idf_escape($L["Trigger"])." $L[Timing] $L[Event] ON ".table($L["Table"])." FOR EACH ROW\n$L[Statement];;\n";return$K;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
replication_status($ve){return
get_rows("SHOW $ve STATUS");}function
convert_field($l){if(preg_match("~binary~",$l["type"]))return"HEX(".idf_escape($l["field"]).")";if($l["type"]=="bit")return"BIN(".idf_escape($l["field"])." + 0)";if(preg_match("~geometry|point|linestring|polygon~",$l["type"]))return"AsWKT(".idf_escape($l["field"]).")";}function
unconvert_field($l,$K){if(preg_match("~binary~",$l["type"]))$K="UNHEX($K)";if($l["type"]=="bit")$K="CONV($K, 2, 10) + 0";if(preg_match("~geometry|point|linestring|polygon~",$l["type"]))$K="GeomFromText($K)";return$K;}function
support($Db){global$f;return!preg_match("~scheme|sequence|type|view_trigger|materializedview".($f->server_info<5.1?"|event|partitioning".($f->server_info<5?"|routine|trigger|view":""):"")."~",$Db);}function
kill_process($V){return
queries("KILL ".number($V));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){global$f;return$f->result("SELECT @@max_connections");}$v="sql";$xe=array();$Xd=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Geometry'=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),)as$w=>$V){$xe+=$V;$Xd[$w]=array_keys($V);}$De=array("unsigned","zerofill","unsigned zerofill");$Yc=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");$Tb=array("char_length","date","from_unixtime","lower","round","sec_to_time","time_to_sec","upper");$Vb=array("avg","count","count distinct","group_concat","max","min","sum");$mb=array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",),array("(^|[^o])int|float|double|decimal"=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",));}define("SERVER",$_GET[DRIVER]);define("DB",$_GET["db"]);define("ME",preg_replace('~^[^?]*/([^?]*).*~','\\1',$_SERVER["REQUEST_URI"]).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));$ca="4.3.1";class
Adminer{var$operators=array("<=",">=");var$_values=array();function
name(){return"<a href='https://www.adminer.org/editor/' target='_blank' id='h1'>".'Editor'."</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
permanentLogin($Va=false){return
password_file($Va);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
database(){global$f;if($f){$ab=$this->databases(false);return(!$ab?$f->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1)"):$ab[(information_schema($ab[0])?1:0)]);}}function
schemas(){return
schemas();}function
databases($Jb=true){return
get_databases($Jb);}function
queryTimeout(){return
5;}function
headers(){return
true;}function
head(){return
true;}function
loginForm(){echo'<table cellspacing="0">
<tr><th>Username<td><input type="hidden" name="auth[driver]" value="server"><input name="auth[username]" id="username" value="',h($_GET["username"]),'" autocapitalize="off">
<tr><th>Password<td><input type="password" name="auth[password]">
</table>
<script type="text/javascript">
focus(document.getElementById(\'username\'));
</script>
',"<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
login($Bc,$G){global$f;$f->query("SET time_zone = ".q(substr_replace(@date("O"),":",-2,0)));return
true;}function
tableName($de){return
h($de["Comment"]!=""?$de["Comment"]:$de["Name"]);}function
fieldName($l,$E=0){return
h($l["comment"]!=""?$l["comment"]:$l["field"]);}function
selectLinks($de,$P=""){$a=$de["Name"];if($P!==null)echo'<p class="tabs"><a href="'.h(ME.'edit='.urlencode($a).$P).'">'.'New item'."</a>\n";}function
foreignKeys($R){return
foreign_keys($R);}function
backwardKeys($R,$ce){$K=array();foreach(get_rows("SELECT TABLE_NAME, CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = ".q($this->database())."
AND REFERENCED_TABLE_SCHEMA = ".q($this->database())."
AND REFERENCED_TABLE_NAME = ".q($R)."
ORDER BY ORDINAL_POSITION",null,"")as$L)$K[$L["TABLE_NAME"]]["keys"][$L["CONSTRAINT_NAME"]][$L["COLUMN_NAME"]]=$L["REFERENCED_COLUMN_NAME"];foreach($K
as$w=>$V){$C=$this->tableName(table_status($w,true));if($C!=""){$Hd=preg_quote($ce);$Ld="(:|\\s*-)?\\s+";$K[$w]["name"]=(preg_match("(^$Hd$Ld(.+)|^(.+?)$Ld$Hd\$)iu",$C,$_)?$_[2].$_[3]:$C);}else
unset($K[$w]);}return$K;}function
backwardKeysPrint($wa,$L){foreach($wa
as$R=>$va){foreach($va["keys"]as$Oa){$y=ME.'select='.urlencode($R);$q=0;foreach($Oa
as$d=>$V)$y.=where_link($q++,$d,$L[$V]);echo"<a href='".h($y)."'>".h($va["name"])."</a>";$y=ME.'edit='.urlencode($R);foreach($Oa
as$d=>$V)$y.="&set".urlencode("[".bracket_escape($d)."]")."=".urlencode($L[$V]);echo"<a href='".h($y)."' title='".'New item'."'>+</a> ";}}}function
selectQuery($I,$ke){return"<!--\n".str_replace("--","--><!-- ",$I)."\n($ke)\n-->\n";}function
rowDescription($R){foreach(fields($R)as$l){if(preg_match("~varchar|character varying~",$l["type"]))return
idf_escape($l["field"]);}return"";}function
rowDescriptions($M,$Mb){$K=$M;foreach($M[0]as$w=>$V){if(list($R,$r,$C)=$this->_foreignColumn($Mb,$w)){$hc=array();foreach($M
as$L)$hc[$L[$w]]=q($L[$w]);$eb=$this->_values[$R];if(!$eb)$eb=get_key_vals("SELECT $r, $C FROM ".table($R)." WHERE $r IN (".implode(", ",$hc).")");foreach($M
as$B=>$L){if(isset($L[$w]))$K[$B][$w]=(string)$eb[$L[$w]];}}}return$K;}function
selectLink($V,$l){}function
selectVal($V,$y,$l,$bd){$K=($V===null?"&nbsp;":$V);$y=h($y);if(preg_match('~blob|bytea~',$l["type"])&&!is_utf8($V)){$K=lang(array('%d byte','%d bytes'),strlen($bd));if(preg_match("~^(GIF|\xFF\xD8\xFF|\x89PNG\x0D\x0A\x1A\x0A)~",$bd))$K="<img src='$y' alt='$K'>";}if(like_bool($l)&&$K!="&nbsp;")$K=($V?'yes':'no');if($y)$K="<a href='$y'".(is_url($y)?" rel='noreferrer'":"").">$K</a>";if(!$y&&!like_bool($l)&&preg_match('~int|float|double|decimal~',$l["type"]))$K="<div class='number'>$K</div>";elseif(preg_match('~date~',$l["type"]))$K="<div class='datetime'>$K</div>";return$K;}function
editVal($V,$l){if(preg_match('~date|timestamp~',$l["type"])&&$V!==null)return
preg_replace('~^(\\d{2}(\\d+))-(0?(\\d+))-(0?(\\d+))~','$1-$3-$5',$V);return$V;}function
selectColumnsPrint($N,$e){}function
selectSearchPrint($Y,$e,$t){$Y=(array)$_GET["where"];echo'<fieldset id="fieldset-search"><legend>'.'Search'."</legend><div>\n";$rc=array();foreach($Y
as$w=>$V)$rc[$V["col"]]=$w;$q=0;$m=fields($_GET["select"]);foreach($e
as$C=>$db){$l=$m[$C];if(preg_match("~enum~",$l["type"])||like_bool($l)){$w=$rc[$C];$q--;echo"<div>".h($db)."<input type='hidden' name='where[$q][col]' value='".h($C)."'>:",(like_bool($l)?" <select name='where[$q][val]'>".optionlist(array(""=>"",'no','yes'),$Y[$w]["val"],true)."</select>":enum_input("checkbox"," name='where[$q][val][]'",$l,(array)$Y[$w]["val"],($l["null"]?0:null))),"</div>\n";unset($e[$C]);}elseif(is_array($D=$this->_foreignKeyOptions($_GET["select"],$C))){if($m[$C]["null"])$D[0]='('.'empty'.')';$w=$rc[$C];$q--;echo"<div>".h($db)."<input type='hidden' name='where[$q][col]' value='".h($C)."'><input type='hidden' name='where[$q][op]' value='='>: <select name='where[$q][val]'>".optionlist($D,$Y[$w]["val"],true)."</select></div>\n";unset($e[$C]);}}$q=0;foreach($Y
as$V){if(($V["col"]==""||$e[$V["col"]])&&"$V[col]$V[val]"!=""){echo"<div><select name='where[$q][col]'><option value=''>(".'anywhere'.")".optionlist($e,$V["col"],true)."</select>",html_select("where[$q][op]",array(-1=>"")+$this->operators,$V["op"]),"<input type='search' name='where[$q][val]' value='".h($V["val"])."' onkeydown='selectSearchKeydown(this, event);' onsearch='selectSearchSearch(this);'></div>\n";$q++;}}echo"<div><select name='where[$q][col]' onchange='this.nextSibling.nextSibling.onchange();'><option value=''>(".'anywhere'.")".optionlist($e,null,true)."</select>",html_select("where[$q][op]",array(-1=>"")+$this->operators),"<input type='search' name='where[$q][val]' onchange='selectAddRow(this);' onsearch='selectSearch(this);'></div>\n","</div></fieldset>\n";}function
selectOrderPrint($E,$e,$t){$ad=array();foreach($t
as$w=>$s){$E=array();foreach($s["columns"]as$V)$E[]=$e[$V];if(count(array_filter($E,'strlen'))>1&&$w!="PRIMARY")$ad[$w]=implode(", ",$E);}if($ad){echo'<fieldset><legend>'.'Sort'."</legend><div>","<select name='index_order'>".optionlist(array(""=>"")+$ad,($_GET["order"][0]!=""?"":$_GET["index_order"]),true)."</select>","</div></fieldset>\n";}if($_GET["order"])echo"<div style='display: none;'>".hidden_fields(array("order"=>array(1=>reset($_GET["order"])),"desc"=>($_GET["desc"]?array(1=>1):array()),))."</div>\n";}function
selectLimitPrint($x){echo"<fieldset><legend>".'Limit'."</legend><div>";echo
html_select("limit",array("","50","100"),$x),"</div></fieldset>\n";}function
selectLengthPrint($ie){}function
selectActionPrint($t){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>","</div></fieldset>\n";}function
selectCommandPrint(){return
true;}function
selectImportPrint(){return
true;}function
selectEmailPrint($qb,$e){if($qb){print_fieldset("email",'E-mail',$_POST["email_append"]);echo"<div onkeydown=\"eventStop(event); return bodyKeydown(event, 'email');\">\n","<p>".'From'.": <input name='email_from' value='".h($_POST?$_POST["email_from"]:$_COOKIE["adminer_email"])."'>\n",'Subject'.": <input name='email_subject' value='".h($_POST["email_subject"])."'>\n","<p><textarea name='email_message' rows='15' cols='75'>".h($_POST["email_message"].($_POST["email_append"]?'{$'."$_POST[email_addition]}":""))."</textarea>\n","<p onkeydown=\"eventStop(event); return bodyKeydown(event, 'email_append');\">".html_select("email_addition",$e,$_POST["email_addition"])."<input type='submit' name='email_append' value='".'Insert'."'>\n";echo"<p>".'Attachments'.": <input type='file' name='email_files[]' onchange=\"this.onchange = function () { }; var el = this.cloneNode(true); el.value = ''; this.parentNode.appendChild(el);\">","<p>".(count($qb)==1?'<input type="hidden" name="email_field" value="'.h(key($qb)).'">':html_select("email_field",$qb)),"<input type='submit' name='email' value='".'Send'."' onclick=\"return this.form['delete'].onclick();\">\n","</div>\n","</div></fieldset>\n";}}function
selectColumnsProcess($e,$t){return
array(array(),array());}function
selectSearchProcess($m,$t){$K=array();foreach((array)$_GET["where"]as$w=>$Y){$La=$Y["col"];$Wc=$Y["op"];$V=$Y["val"];if(($w<0?"":$La).$V!=""){$Ra=array();foreach(($La!=""?array($La=>$m[$La]):$m)as$C=>$l){if($La!=""||is_numeric($V)||!preg_match('~int|float|double|decimal~',$l["type"])){$C=idf_escape($C);if($La!=""&&$l["type"]=="enum")$Ra[]=(in_array(0,$V)?"$C IS NULL OR ":"")."$C IN (".implode(", ",array_map('intval',$V)).")";else{$je=preg_match('~char|text|enum|set~',$l["type"]);$W=$this->processInput($l,(!$Wc&&$je&&preg_match('~^[^%]+$~',$V)?"%$V%":$V));$Ra[]=$C.($W=="NULL"?" IS".($Wc==">="?" NOT":"")." $W":(in_array($Wc,$this->operators)||$Wc=="="?" $Wc $W":($je?" LIKE $W":" IN (".str_replace(",","', '",$W).")")));if($w<0&&$V=="0")$Ra[]="$C IS NULL";}}}$K[]=($Ra?"(".implode(" OR ",$Ra).")":"0");}}return$K;}function
selectOrderProcess($m,$t){$jc=$_GET["index_order"];if($jc!="")unset($_GET["order"][1]);if($_GET["order"])return
array(idf_escape(reset($_GET["order"])).($_GET["desc"]?" DESC":""));foreach(($jc!=""?array($t[$jc]):$t)as$s){if($jc!=""||$s["type"]=="INDEX"){$Xb=array_filter($s["descs"]);$db=false;foreach($s["columns"]as$V){if(preg_match('~date|timestamp~',$m[$V]["type"])){$db=true;break;}}$K=array();foreach($s["columns"]as$w=>$V)$K[]=idf_escape($V).(($Xb?$s["descs"][$w]:$db)?" DESC":"");return$K;}}return
array();}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"50");}function
selectLengthProcess(){return"100";}function
selectEmailProcess($Y,$Mb){if($_POST["email_append"])return
true;if($_POST["email"]){$Kd=0;if($_POST["all"]||$_POST["check"]){$l=idf_escape($_POST["email_field"]);$Zd=$_POST["email_subject"];$A=$_POST["email_message"];preg_match_all('~\\{\\$([a-z0-9_]+)\\}~i',"$Zd.$A",$Dc);$M=get_rows("SELECT DISTINCT $l".($Dc[1]?", ".implode(", ",array_map('idf_escape',array_unique($Dc[1]))):"")." FROM ".table($_GET["select"])." WHERE $l IS NOT NULL AND $l != ''".($Y?" AND ".implode(" AND ",$Y):"").($_POST["all"]?"":" AND ((".implode(") OR (",array_map('where_check',(array)$_POST["check"]))."))"));$m=fields($_GET["select"]);foreach($this->rowDescriptions($M,$Mb)as$L){$Ad=array('{\\'=>'{');foreach($Dc[1]as$V)$Ad['{$'."$V}"]=$this->editVal($L[$V],$m[$V]);$pb=$L[$_POST["email_field"]];if(is_mail($pb)&&send_mail($pb,strtr($Zd,$Ad),strtr($A,$Ad),$_POST["email_from"],$_FILES["email_files"]))$Kd++;}}cookie("adminer_email",$_POST["email_from"]);redirect(remove_from_uri(),lang(array('%d e-mail has been sent.','%d e-mails have been sent.'),$Kd));}return
false;}function
selectQueryBuild($N,$Y,$p,$E,$x,$F){return"";}function
messageQuery($I,$ke){return" <span class='time'>".@date("H:i:s")."</span><!--\n".str_replace("--","--><!-- ",$I)."\n".($ke?"($ke)\n":"")."-->";}function
editFunctions($l){$K=array();if($l["null"]&&preg_match('~blob~',$l["type"]))$K["NULL"]='empty';$K[""]=($l["null"]||$l["auto_increment"]||like_bool($l)?"":"*");if(preg_match('~date|time~',$l["type"]))$K["now"]='now';if(preg_match('~_(md5|sha1)$~i',$l["field"],$_))$K[]=strtolower($_[1]);return$K;}function
editInput($R,$l,$c,$W){if($l["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$c value='-1' checked><i>".'original'."</i></label> ":"").enum_input("radio",$c,$l,($W||isset($_GET["select"])?$W:0),($l["null"]?"":null));$D=$this->_foreignKeyOptions($R,$l["field"],$W);if($D!==null)return(is_array($D)?"<select$c>".optionlist($D,$W,true)."</select>":"<input value='".h($W)."'$c class='hidden'><input value='".h($D)."' class='jsonly' onkeyup=\"whisper('".h(ME."script=complete&source=".urlencode($R)."&field=".urlencode($l["field"]))."&value=', this);\"><div onclick='return whisperClick(event, this.previousSibling);'></div>");if(like_bool($l))return'<input type="checkbox" value="'.h($W?$W:1).'"'.($W?' checked':'')."$c>";$cc="";if(preg_match('~time~',$l["type"]))$cc='HH:MM:SS';if(preg_match('~date|timestamp~',$l["type"]))$cc='[yyyy]-mm-dd'.($cc?" [$cc]":"");if($cc)return"<input value='".h($W)."'$c> ($cc)";if(preg_match('~_(md5|sha1)$~i',$l["field"]))return"<input type='password' value='".h($W)."'$c>";return'';}function
processInput($l,$W,$o=""){if($o=="now")return"$o()";$K=$W;if(preg_match('~date|timestamp~',$l["type"])&&preg_match('(^'.str_replace('\\$1','(?P<p1>\\d*)',preg_replace('~(\\\\\\$([2-6]))~','(?P<p\\2>\\d{1,2})',preg_quote('$1-$3-$5'))).'(.*))',$W,$_))$K=($_["p1"]!=""?$_["p1"]:($_["p2"]!=""?($_["p2"]<70?20:19).$_["p2"]:gmdate("Y")))."-$_[p3]$_[p4]-$_[p5]$_[p6]".end($_);$K=($l["type"]=="bit"&&preg_match('~^[0-9]+$~',$W)?$K:q($K));if($W==""&&like_bool($l))$K="0";elseif($W==""&&($l["null"]||!preg_match('~char|text~',$l["type"])))$K="NULL";elseif(preg_match('~^(md5|sha1)$~',$o))$K="$o($K)";return
unconvert_field($l,$K);}function
dumpOutput(){return
array();}function
dumpFormat(){return
array('csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpDatabase($h){}function
dumpTable(){echo"\xef\xbb\xbf";}function
dumpData($R,$Yd,$I){global$f;$J=$f->query($I,1);if($J){while($L=$J->fetch_assoc()){if($Yd=="table"){dump_csv(array_keys($L));$Yd="INSERT";}dump_csv($L);}}}function
dumpFilename($fc){return
friendly_url($fc);}function
dumpHeaders($fc,$Lc=false){$_b="csv";header("Content-Type: text/csv; charset=utf-8");return$_b;}function
homepage(){return
true;}function
navigation($Kc){global$ca;echo'<h1>
',$this->name(),' <span class="version">',$ca,'</span>
<a href="https://www.adminer.org/editor/#download" target="_blank" id="version">',(version_compare($ca,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</h1>
';if($Kc=="auth"){$Ib=true;foreach((array)$_SESSION["pwds"]as$X=>$Od){foreach($Od[""]as$T=>$G){if($G!==null){if($Ib){echo"<p id='logins' onmouseover='menuOver(this, event);' onmouseout='menuOut(this);'>\n";$Ib=false;}echo"<a href='".h(auth_url($X,"",$T))."'>".($T!=""?h($T):"<i>".'empty'."</i>")."</a><br>\n";}}}}else{$this->databasesPrint($Kc);if($Kc!="db"&&$Kc!="ns"){$S=table_status('',true);if(!$S)echo"<p class='message'>".'No tables.'."\n";else$this->tablesPrint($S);}}}function
databasesPrint($Kc){}function
tablesPrint($fe){echo"<p id='tables' onmouseover='menuOver(this, event);' onmouseout='menuOut(this);'>\n";foreach($fe
as$L){$C=$this->tableName($L);if(isset($L["Engine"])&&$C!="")echo"<a href='".h(ME).'select='.urlencode($L["Name"])."'".bold($_GET["select"]==$L["Name"]||$_GET["edit"]==$L["Name"],"select")." title='".'Select data'."'>$C</a><br>\n";}}function
_foreignColumn($Mb,$d){foreach((array)$Mb[$d]as$Lb){if(count($Lb["source"])==1){$C=$this->rowDescription($Lb["table"]);if($C!=""){$r=idf_escape($Lb["target"][0]);return
array($Lb["table"],$r,$C);}}}}function
_foreignKeyOptions($R,$d,$W=null){global$f;if(list($ge,$r,$C)=$this->_foreignColumn(column_foreign_keys($R),$d)){$K=&$this->_values[$ge];if($K===null){$S=table_status($ge);$K=($S["Rows"]>1000?"":array(""=>"")+get_key_vals("SELECT $r, $C FROM ".table($ge)." ORDER BY 2"));}if(!$K&&$W!==null)return$f->result("SELECT $C FROM ".table($ge)." WHERE $r = ".q($W));return$K;}}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);function
page_header($me,$k="",$Ca=array(),$ne=""){global$ba,$ca,$b,$jb,$v;page_headers();if(is_ajax()&&$k){page_messages($k);exit;}$oe=$me.($ne!=""?": $ne":"");$pe=strip_tags($oe.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());echo'<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex">
<meta name="referrer" content="origin-when-crossorigin">
<title>',$pe,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME))."?file=default.css&amp;version=4.3.1&amp;driver=mysql",'">
<script type="text/javascript" src="',h(preg_replace("~\\?.*~","",ME))."?file=functions.js&amp;version=4.3.1&amp;driver=mysql",'"></script>
';if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME))."?file=favicon.ico&amp;version=4.3.1&amp;driver=mysql",'">
<link rel="apple-touch-icon" href="',h(preg_replace("~\\?.*~","",ME))."?file=favicon.ico&amp;version=4.3.1&amp;driver=mysql",'">
';if(file_exists("adminer.css")){echo'<link rel="stylesheet" type="text/css" href="adminer.css">
';}}echo'
<body class="ltr nojs" onkeydown="bodyKeydown(event);" onclick="bodyClick(event);"',(isset($_COOKIE["adminer_version"])?"":" onload=\"verifyVersion('$ca');\"");?>>
<script type="text/javascript">
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = '<?php echo
js_escape('You are offline.'),'\';
</script>

<div id="help" class="jush-',$v,' jsonly hidden" onmouseover="helpOpen = 1;" onmouseout="helpMouseout(this, event);"></div>

<div id="content">
';if($Ca!==null){$y=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($y?$y:".").'">'.$jb[DRIVER].'</a> &raquo; ';$y=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$O=(SERVER!=""?h(SERVER):'Server');if($Ca===false)echo"$O\n";else{echo"<a href='".($y?h($y):".")."' accesskey='1' title='Alt+Shift+1'>$O</a> &raquo; ";if($_GET["ns"]!=""||(DB!=""&&is_array($Ca)))echo'<a href="'.h($y."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';if(is_array($Ca)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> &raquo; ';foreach($Ca
as$w=>$V){$db=(is_array($V)?$V[1]:h($V));if($db!="")echo"<a href='".h(ME."$w=").urlencode(is_array($V)?$V[0]:$V)."'>$db</a> &raquo; ";}}echo"$me\n";}}echo"<h2>$oe</h2>\n","<div id='ajaxstatus' class='jsonly hidden'></div>\n";restart_session();page_messages($k);$ab=&get_session("dbs");if(DB!=""&&$ab&&!in_array(DB,$ab,true))$ab=null;stop_session();define("PAGE_HEADER",1);}function
page_headers(){global$b;header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");if($b->headers()){header("X-Frame-Options: deny");header("X-XSS-Protection: 0");}}function
page_messages($k){$Fe=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$Jc=$_SESSION["messages"][$Fe];if($Jc){echo"<div class='message'>".implode("</div>\n<div class='message'>",$Jc)."</div>\n";unset($_SESSION["messages"][$Fe]);}if($k)echo"<div class='error'>$k</div>\n";}function
page_footer($Kc=""){global$b,$re;echo'</div>

';if($Kc!="auth"){echo'<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="',$re,'">
</p>
</form>
';}echo'<div id="menu">
';$b->navigation($Kc);echo'</div>
<script type="text/javascript">setupSubmitHighlight(document);</script>
';}function
int32($B){while($B>=2147483648)$B-=4294967296;while($B<=-2147483649)$B+=4294967296;return(int)$B;}function
long2str($U,$Le){$Fd='';foreach($U
as$V)$Fd.=pack('V',$V);if($Le)return
substr($Fd,0,end($U));return$Fd;}function
str2long($Fd,$Le){$U=array_values(unpack('V*',str_pad($Fd,4*ceil(strlen($Fd)/4),"\0")));if($Le)$U[]=strlen($Fd);return$U;}function
xxtea_mx($Z,$Pe,$be,$qc){return
int32((($Z>>5&0x7FFFFFF)^$Pe<<2)+(($Pe>>3&0x1FFFFFFF)^$Z<<4))^int32(($be^$Pe)+($qc^$Z));}function
encrypt_string($Wd,$w){if($Wd=="")return"";$w=array_values(unpack("V*",pack("H*",md5($w))));$U=str2long($Wd,true);$B=count($U)-1;$Z=$U[$B];$Pe=$U[0];$sd=floor(6+52/($B+1));$be=0;while($sd-->0){$be=int32($be+0x9E3779B9);$lb=$be>>2&3;for($dd=0;$dd<$B;$dd++){$Pe=$U[$dd+1];$Mc=xxtea_mx($Z,$Pe,$be,$w[$dd&3^$lb]);$Z=int32($U[$dd]+$Mc);$U[$dd]=$Z;}$Pe=$U[0];$Mc=xxtea_mx($Z,$Pe,$be,$w[$dd&3^$lb]);$Z=int32($U[$B]+$Mc);$U[$B]=$Z;}return
long2str($U,false);}function
decrypt_string($Wd,$w){if($Wd=="")return"";if(!$w)return
false;$w=array_values(unpack("V*",pack("H*",md5($w))));$U=str2long($Wd,false);$B=count($U)-1;$Z=$U[$B];$Pe=$U[0];$sd=floor(6+52/($B+1));$be=int32($sd*0x9E3779B9);while($be){$lb=$be>>2&3;for($dd=$B;$dd>0;$dd--){$Z=$U[$dd-1];$Mc=xxtea_mx($Z,$Pe,$be,$w[$dd&3^$lb]);$Pe=int32($U[$dd]-$Mc);$U[$dd]=$Pe;}$Z=$U[$B];$Mc=xxtea_mx($Z,$Pe,$be,$w[$dd&3^$lb]);$Pe=int32($U[0]-$Mc);$U[0]=$Pe;$be=int32($be-0x9E3779B9);}return
long2str($U,true);}$f='';$Zb=$_SESSION["token"];if(!$Zb)$_SESSION["token"]=rand(1,1e6);$re=get_token();$hd=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$V){list($w)=explode(":",$V);$hd[$w]=$V;}}function
add_invalid_login(){global$b;$Fb=get_temp_dir()."/adminer.invalid";$Rb=@fopen($Fb,"r+");if(!$Rb){$Rb=@fopen($Fb,"w");if(!$Rb)return;}flock($Rb,LOCK_EX);$oc=unserialize(stream_get_contents($Rb));$ke=time();if($oc){foreach($oc
as$pc=>$V){if($V[0]<$ke)unset($oc[$pc]);}}$nc=&$oc[$b->bruteForceKey()];if(!$nc)$nc=array($ke+30*60,0);$nc[1]++;$Md=serialize($oc);rewind($Rb);fwrite($Rb,$Md);ftruncate($Rb,strlen($Md));flock($Rb,LOCK_UN);fclose($Rb);}$ra=$_POST["auth"];if($ra){$oc=unserialize(@file_get_contents(get_temp_dir()."/adminer.invalid"));$nc=$oc[$b->bruteForceKey()];$Oc=($nc[1]>30?$nc[0]-time():0);if($Oc>0)auth_error(lang(array('Too many unsuccessful logins, try again in %d minute.','Too many unsuccessful logins, try again in %d minutes.'),ceil($Oc/60)));session_regenerate_id();$X=$ra["driver"];$O=$ra["server"];$T=$ra["username"];$G=(string)$ra["password"];$h=$ra["db"];set_password($X,$O,$T,$G);$_SESSION["db"][$X][$O][$T][$h]=true;if($ra["permanent"]){$w=base64_encode($X)."-".base64_encode($O)."-".base64_encode($T)."-".base64_encode($h);$pd=$b->permanentLogin(true);$hd[$w]="$w:".base64_encode($pd?encrypt_string($G,$pd):"");cookie("adminer_permanent",implode(" ",$hd));}if(count($_POST)==1||DRIVER!=$X||SERVER!=$O||$_GET["username"]!==$T||DB!=$h)redirect(auth_url($X,$O,$T,$h));}elseif($_POST["logout"]){if($Zb&&!verify_token()){page_header('Logout','Invalid CSRF token. Send the form again.');page_footer("db");exit;}else{foreach(array("pwds","db","dbs","queries")as$w)set_session($w,null);unset_permanent();redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.');}}elseif($hd&&!$_SESSION["pwds"]){session_regenerate_id();$pd=$b->permanentLogin();foreach($hd
as$w=>$V){list(,$Ha)=explode(":",$V);list($X,$O,$T,$h)=array_map('base64_decode',explode("-",$w));set_password($X,$O,$T,decrypt_string(base64_decode($Ha),$pd));$_SESSION["db"][$X][$O][$T][$h]=true;}}function
unset_permanent(){global$hd;foreach($hd
as$w=>$V){list($X,$O,$T,$h)=array_map('base64_decode',explode("-",$w));if($X==DRIVER&&$O==SERVER&&$T==$_GET["username"]&&$h==DB)unset($hd[$w]);}cookie("adminer_permanent",implode(" ",$hd));}function
auth_error($k){global$b,$Zb;$Pd=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$Pd]||$_GET[$Pd])&&!$Zb)$k='Session expired, please login again.';else{add_invalid_login();$G=get_password();if($G!==null){if($G===false)$k.='<br>'.sprintf('Master password expired. <a href="https://www.adminer.org/en/extension/" target="_blank">Implement</a> %s method to make it permanent.','<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);}unset_permanent();}}if(!$_COOKIE[$Pd]&&$_GET[$Pd]&&ini_bool("session.use_only_cookies"))$k='Session support must be enabled.';$fd=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?$_COOKIE["adminer_key"]:rand_string()),$fd["lifetime"]);page_header('Login',$k,null);echo"<form action='' method='post'>\n";$b->loginForm();echo"<div>";hidden_fields($_POST,array("auth"));echo"</div>\n","</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])){if(!class_exists("Min_DB")){unset($_SESSION["pwds"][DRIVER]);unset_permanent();page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",$ld)),false);page_footer("auth");exit;}$f=connect();}$i=new
Min_Driver($f);if(!is_object($f)||($Bc=$b->login($_GET["username"],get_password()))!==true)auth_error((is_string($f)?h($f):(is_string($Bc)?$Bc:'Invalid credentials.')));if($ra&&$_POST["token"])$_POST["token"]=$re;$k='';if($_POST){if(!verify_token()){$kc="max_input_vars";$Hc=ini_get($kc);if(extension_loaded("suhosin")){foreach(array("suhosin.request.max_vars","suhosin.post.max_vars")as$w){$V=ini_get($w);if($V&&(!$Hc||$V<$Hc)){$kc=$w;$Hc=$V;}}}$k=(!$_POST["token"]&&$Hc?sprintf('Maximum number of allowed fields exceeded. Please increase %s.',"'$kc'"):'Invalid CSRF token. Send the form again.'.' '.'If you did not send this request from Adminer then close this page.');}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){$k=sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.',"'post_max_size'");if(isset($_GET["sql"]))$k.=' '.'You can upload a big SQL file via FTP and import it from server.';}if(!ini_bool("session.use_cookies")||@ini_set("session.use_cookies",false)!==false)session_write_close();function
email_header($ac){return"=?UTF-8?B?".base64_encode($ac)."?=";}function
send_mail($pb,$Zd,$A,$Sb="",$Gb=array()){$j=(DIRECTORY_SEPARATOR=="/"?"\n":"\r\n");$A=str_replace("\n",$j,wordwrap(str_replace("\r","","$A\n")));$Ba=uniqid("boundary");$qa="";foreach((array)$Gb["error"]as$w=>$V){if(!$V)$qa.="--$Ba$j"."Content-Type: ".str_replace("\n","",$Gb["type"][$w]).$j."Content-Disposition: attachment; filename=\"".preg_replace('~["\\n]~','',$Gb["name"][$w])."\"$j"."Content-Transfer-Encoding: base64$j$j".chunk_split(base64_encode(file_get_contents($Gb["tmp_name"][$w])),76,$j).$j;}$ya="";$bc="Content-Type: text/plain; charset=utf-8$j"."Content-Transfer-Encoding: 8bit";if($qa){$qa.="--$Ba--$j";$ya="--$Ba$j$bc$j$j";$bc="Content-Type: multipart/mixed; boundary=\"$Ba\"";}$bc.=$j."MIME-Version: 1.0$j"."X-Mailer: Adminer Editor".($Sb?$j."From: ".str_replace("\n","",$Sb):"");return
mail($pb,email_header($Zd),$ya.$A.$qa,$bc);}function
like_bool($l){return
preg_match("~bool|(tinyint|bit)\\(1\\)~",$l["full_type"]);}$f->select_db($b->database());$Tc="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";$jb[DRIVER]='Login';if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["download"])){$a=$_GET["download"];$m=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));$N=array(idf_escape($_GET["field"]));$J=$i->select($a,$N,array(where($_GET,$m)),$N);$L=($J?$J->fetch_row():array());echo$L[0];exit;}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$m=fields($a);$Y=(isset($_GET["select"])?(count($_POST["check"])==1?where_check($_POST["check"][0],$m):""):where($_GET,$m));$Ee=(isset($_GET["select"])?$_POST["edit"]:$Y);foreach($m
as$C=>$l){if(!isset($l["privileges"][$Ee?"update":"insert"])||$b->fieldName($l)=="")unset($m[$C]);}if($_POST&&!$k&&!isset($_GET["select"])){$z=$_POST["referer"];if($_POST["insert"])$z=($Ee?null:$_SERVER["REQUEST_URI"]);elseif(!preg_match('~^.+&select=.+$~',$z))$z=ME."select=".urlencode($a);$t=indexes($a);$_e=unique_array($_GET["where"],$t);$vd="\nWHERE $Y";if(isset($_POST["delete"]))queries_redirect($z,'Item has been deleted.',$i->delete($a,$vd,!$_e));else{$P=array();foreach($m
as$C=>$l){$V=process_input($l);if($V!==false&&$V!==null)$P[idf_escape($C)]=$V;}if($Ee){if(!$P)redirect($z);queries_redirect($z,'Item has been updated.',$i->update($a,$P,$vd,!$_e));if(is_ajax()){page_headers();page_messages($k);exit;}}else{$J=$i->insert($a,$P);$xc=($J?last_id():0);queries_redirect($z,sprintf('Item%s has been inserted.',($xc?" $xc":"")),$J);}}}$L=null;if($_POST["save"])$L=(array)$_POST["fields"];elseif($Y){$N=array();foreach($m
as$C=>$l){if(isset($l["privileges"]["select"])){$oa=convert_field($l);if($_POST["clone"]&&$l["auto_increment"])$oa="''";if($v=="sql"&&preg_match("~enum|set~",$l["type"]))$oa="1*".idf_escape($C);$N[]=($oa?"$oa AS ":"").idf_escape($C);}}$L=array();if(!support("table"))$N=array("*");if($N){$J=$i->select($a,$N,array($Y),$N,array(),(isset($_GET["select"])?2:1));$L=$J->fetch_assoc();if(!$L)$L=false;if(isset($_GET["select"])&&(!$L||$J->fetch_assoc()))$L=null;}}if(!support("table")&&!$m){if(!$Y){$J=$i->select($a,array("*"),$Y,array("*"));$L=($J?$J->fetch_assoc():false);if(!$L)$L=array($i->primary=>"");}if($L){foreach($L
as$w=>$V){if(!$Y)$L[$w]=null;$m[$w]=array("field"=>$w,"null"=>($w!=$i->primary),"auto_increment"=>($w==$i->primary));}}}edit_form($a,$m,$L,$Ee);}elseif(isset($_GET["select"])){$a=$_GET["select"];$S=table_status1($a);$t=indexes($a);$m=fields($a);$Ob=column_foreign_keys($a);$Sc="";if($S["Oid"]){$Sc=($v=="sqlite"?"rowid":"oid");$t[]=array("type"=>"PRIMARY","columns"=>array($Sc));}parse_str($_COOKIE["adminer_import"],$ia);$Ed=array();$e=array();$ie=null;foreach($m
as$w=>$l){$C=$b->fieldName($l);if(isset($l["privileges"]["select"])&&$C!=""){$e[$w]=html_entity_decode(strip_tags($C),ENT_QUOTES);if(is_shortable($l))$ie=$b->selectLengthProcess();}$Ed+=$l["privileges"];}list($N,$p)=$b->selectColumnsProcess($e,$t);$u=count($p)<count($N);$Y=$b->selectSearchProcess($m,$t);$E=$b->selectOrderProcess($m,$t);$x=$b->selectLimitProcess();$Sb=($N?implode(", ",$N):"*".($Sc?", $Sc":"")).convert_fields($e,$m,$N)."\nFROM ".table($a);$Ub=($p&&$u?"\nGROUP BY ".implode(", ",$p):"").($E?"\nORDER BY ".implode(", ",$E):"");if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Ae=>$L){$oa=convert_field($m[key($L)]);$N=array($oa?$oa:idf_escape(key($L)));$Y[]=where_check($Ae,$m);$K=$i->select($a,$N,$Y,$N);if($K)echo
reset($K->fetch_row());}exit;}if($_POST&&!$k){$Ne=$Y;if(!$_POST["all"]&&is_array($_POST["check"])){$Ga=array();foreach($_POST["check"]as$Ea)$Ga[]=where_check($Ea,$m);$Ne[]="((".implode(") OR (",$Ga)."))";}$Ne=($Ne?"\nWHERE ".implode(" AND ",$Ne):"");$nd=$Ce=null;foreach($t
as$s){if($s["type"]=="PRIMARY"){$nd=array_flip($s["columns"]);$Ce=($N?$nd:array());break;}}foreach((array)$Ce
as$w=>$V){if(in_array(idf_escape($w),$N))unset($Ce[$w]);}if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");if(!is_array($_POST["check"])||$Ce===array())$I="SELECT $Sb$Ne$Ub";else{$ze=array();foreach($_POST["check"]as$V)$ze[]="(SELECT".limit($Sb,"\nWHERE ".($Y?implode(" AND ",$Y)." AND ":"").where_check($V,$m).$Ub,1).")";$I=implode(" UNION ALL ",$ze);}$b->dumpData($a,"table",$I);exit;}if(!$b->selectEmailProcess($Y,$Ob)){if($_POST["save"]||$_POST["delete"]){$J=true;$ja=0;$P=array();if(!$_POST["delete"]){foreach($e
as$C=>$V){$V=process_input($m[$C]);if($V!==null&&($_POST["clone"]||$V!==false))$P[idf_escape($C)]=($V!==false?$V:idf_escape($C));}}if($_POST["delete"]||$P){if($_POST["clone"])$I="INTO ".table($a)." (".implode(", ",array_keys($P)).")\nSELECT ".implode(", ",$P)."\nFROM ".table($a);if($_POST["all"]||($Ce===array()&&is_array($_POST["check"]))||$u){$J=($_POST["delete"]?$i->delete($a,$Ne):($_POST["clone"]?queries("INSERT $I$Ne"):$i->update($a,$P,$Ne)));$ja=$f->affected_rows;}else{foreach((array)$_POST["check"]as$V){$Me="\nWHERE ".($Y?implode(" AND ",$Y)." AND ":"").where_check($V,$m);$J=($_POST["delete"]?$i->delete($a,$Me,1):($_POST["clone"]?queries("INSERT".limit1($I,$Me)):$i->update($a,$P,$Me)));if(!$J)break;$ja+=$f->affected_rows;}}}$A=lang(array('%d item has been affected.','%d items have been affected.'),$ja);if($_POST["clone"]&&$J&&$ja==1){$xc=last_id();if($xc)$A=sprintf('Item%s has been inserted.'," $xc");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page":""),$A,$J);if(!$_POST["delete"]){edit_form($a,$m,(array)$_POST["fields"],!$_POST["clone"]);page_footer();exit;}}elseif(!$_POST["import"]){if(!$_POST["val"])$k='Ctrl+click on a value to modify it.';else{$J=true;$ja=0;foreach($_POST["val"]as$Ae=>$L){$P=array();foreach($L
as$w=>$V){$w=bracket_escape($w,1);$P[idf_escape($w)]=(preg_match('~char|text~',$m[$w]["type"])||$V!=""?$b->processInput($m[$w],$V):"NULL");}$J=$i->update($a,$P," WHERE ".($Y?implode(" AND ",$Y)." AND ":"").where_check($Ae,$m),!($u||$Ce===array())," ");if(!$J)break;$ja+=$f->affected_rows;}queries_redirect(remove_from_uri(),lang(array('%d item has been affected.','%d items have been affected.'),$ja),$J);}}elseif(!is_string($Eb=get_file("csv_file",true)))$k=upload_error($Eb);elseif(!preg_match('~~u',$Eb))$k='File must be in UTF-8 encoding.';else{cookie("adminer_import","output=".urlencode($ia["output"])."&format=".urlencode($_POST["separator"]));$J=true;$Oa=array_keys($m);preg_match_all('~(?>"[^"]*"|[^"\\r\\n]+)+~',$Eb,$Dc);$ja=count($Dc[0]);$i->begin();$Ld=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$M=array();foreach($Dc[0]as$w=>$V){preg_match_all("~((?>\"[^\"]*\")+|[^$Ld]*)$Ld~",$V.$Ld,$Ec);if(!$w&&!array_diff($Ec[1],$Oa)){$Oa=$Ec[1];$ja--;}else{$P=array();foreach($Ec[1]as$q=>$La)$P[idf_escape($Oa[$q])]=($La==""&&$m[$Oa[$q]]["null"]?"NULL":q(str_replace('""','"',preg_replace('~^"|"$~','',$La))));$M[]=$P;}}$J=(!$M||$i->insertUpdate($a,$M,$nd));if($J)$J=$i->commit();queries_redirect(remove_from_uri("page"),lang(array('%d row has been imported.','%d rows have been imported.'),$ja),$J);$i->rollback();}}}$ee=$b->tableName($S);if(is_ajax()){page_headers();ob_start();}else
page_header('Select'.": $ee",$k);$P=null;if(isset($Ed["insert"])||!support("table")){$P="";foreach((array)$_GET["where"]as$V){if(count($Ob[$V["col"]])==1&&($V["op"]=="="||(!$V["op"]&&!preg_match('~[_%]~',$V["val"]))))$P.="&set".urlencode("[".bracket_escape($V["col"])."]")."=".urlencode($V["val"]);}}$b->selectLinks($S,$P);if(!$e&&support("table"))echo"<p class='error'>".'Unable to select the table'.($m?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($N,$e);$b->selectSearchPrint($Y,$e,$t);$b->selectOrderPrint($E,$e,$t);$b->selectLimitPrint($x);$b->selectLengthPrint($ie);$b->selectActionPrint($t);echo"</form>\n";$F=$_GET["page"];if($F=="last"){$n=$f->result(count_rows($a,$Y,$u,$p));$F=floor(max(0,$n-1)/$x);}$Id=$N;if(!$Id){$Id[]="*";if($Sc)$Id[]=$Sc;}$Ua=convert_fields($e,$m,$N);if($Ua)$Id[]=substr($Ua,2);$J=$i->select($a,$Id,$Y,$p,$E,$x,$F,true);if(!$J)echo"<p class='error'>".error()."\n";else{if($v=="mssql"&&$F)$J->seek($x*$F);$rb=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$M=array();while($L=$J->fetch_assoc()){if($F&&$v=="oracle")unset($L["RNUM"]);$M[]=$L;}if($_GET["page"]!="last"&&+$x&&$p&&$u&&$v=="sql")$n=$f->result(" SELECT FOUND_ROWS()");if(!$M)echo"<p class='message'>".'No rows.'."\n";else{$xa=$b->backwardKeys($a,$ee);echo"<table id='table' cellspacing='0' class='nowrap checkable' onclick='tableClick(event);' ondblclick='tableClick(event, true);' onkeydown='return editingKeydown(event);'>\n","<thead><tr>".(!$p&&$N?"":"<td><input type='checkbox' id='all-page' onclick='formCheck(this, /check/);' class='jsonly'> <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".'Modify'."</a>");$Nc=array();$Tb=array();reset($N);$xd=1;foreach($M[0]as$w=>$V){if($w!=$Sc){$V=$_GET["columns"][key($N)];$l=$m[$N?($V?$V["col"]:current($N)):$w];$C=($l?$b->fieldName($l,$xd):($V["fun"]?"*":$w));if($C!=""){$xd++;$Nc[$w]=$C;$d=idf_escape($w);$ec=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($w);$db="&desc%5B0%5D=1";echo'<th onmouseover="columnMouse(this);" onmouseout="columnMouse(this, \' hidden\');">','<a href="'.h($ec.($E[0]==$d||$E[0]==$w||(!$E&&$u&&$p[0]==$d)?$db:'')).'">';echo
apply_sql_function($V["fun"],$C)."</a>";echo"<span class='column hidden'>","<a href='".h($ec.$db)."' title='".'descending'."' class='text'> â†“</a>";if(!$V["fun"])echo'<a href="#fieldset-search" onclick="selectSearch(\''.h(js_escape($w)).'\'); return false;" title="'.'Search'.'" class="text jsonly"> =</a>';echo"</span>";}$Tb[$w]=$V["fun"];next($N);}}$_c=array();if($_GET["modify"]){foreach($M
as$L){foreach($L
as$w=>$V)$_c[$w]=max($_c[$w],min(40,strlen(utf8_decode($V))));}}echo($xa?"<th>".'Relations':"")."</thead>\n";if(is_ajax()){if($x%2==1&&$F%2==1)odd();ob_end_clean();}foreach($b->rowDescriptions($M,$Ob)as$B=>$L){$_e=unique_array($M[$B],$t);if(!$_e){$_e=array();foreach($M[$B]as$w=>$V){if(!preg_match('~^(COUNT\\((\\*|(DISTINCT )?`(?:[^`]|``)+`)\\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\\(`(?:[^`]|``)+`\\))$~',$w))$_e[$w]=$V;}}$Ae="";foreach($_e
as$w=>$V){if(($v=="sql"||$v=="pgsql")&&strlen($V)>64){$w=(strpos($w,'(')?$w:idf_escape($w));$w="MD5(".($v=='sql'&&preg_match("~^utf8_~",$m[$w]["collation"])?$w:"CONVERT($w USING ".charset($f).")").")";$V=md5($V);}$Ae.="&".($V!==null?urlencode("where[".bracket_escape($w)."]")."=".urlencode($V):"null%5B%5D=".urlencode($w));}echo"<tr".odd().">".(!$p&&$N?"":"<td>".checkbox("check[]",substr($Ae,1),in_array(substr($Ae,1),(array)$_POST["check"]),"","this.form['all'].checked = false; formUncheck('all-page');").($u||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Ae)."'>".'edit'."</a>"));foreach($L
as$w=>$V){if(isset($Nc[$w])){$l=$m[$w];if($V!=""&&(!isset($rb[$w])||$rb[$w]!=""))$rb[$w]=(is_mail($V)?$Nc[$w]:"");$y="";if(preg_match('~blob|bytea|raw|file~',$l["type"])&&$V!="")$y=ME.'download='.urlencode($a).'&field='.urlencode($w).$Ae;if(!$y&&$V!==null){foreach((array)$Ob[$w]as$Nb){if(count($Ob[$w])==1||end($Nb["source"])==$w){$y="";foreach($Nb["source"]as$q=>$Sd)$y.=where_link($q,$Nb["target"][$q],$M[$B][$Sd]);$y=($Nb["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\\1'.urlencode($Nb["db"]),ME):ME).'select='.urlencode($Nb["table"]).$y;if($Nb["ns"])$y=preg_replace('~([?&]ns=)[^&]+~','\\1'.urlencode($Nb["ns"]),$y);if(count($Nb["source"])==1)break;}}}if($w=="COUNT(*)"){$y=ME."select=".urlencode($a);$q=0;foreach((array)$_GET["where"]as$U){if(!array_key_exists($U["col"],$_e))$y.=where_link($q++,$U["col"],$U["val"],$U["op"]);}foreach($_e
as$qc=>$U)$y.=where_link($q++,$qc,$U);}$V=select_value($V,$y,$l,$ie);$r=h("val[$Ae][".bracket_escape($w)."]");$W=$_POST["val"][$Ae][bracket_escape($w)];$nb=!is_array($L[$w])&&is_utf8($V)&&$M[$B][$w]==$L[$w]&&!$Tb[$w];$he=preg_match('~text|lob~',$l["type"]);if(($_GET["modify"]&&$nb)||$W!==null){$Wb=h($W!==null?$W:$L[$w]);echo"<td>".($he?"<textarea name='$r' cols='30' rows='".(substr_count($L[$w],"\n")+1)."'>$Wb</textarea>":"<input name='$r' value='$Wb' size='$_c[$w]'>");}else{$Cc=strpos($V,"<i>...</i>");echo"<td id='$r' onclick=\"selectClick(this, event, ".($Cc?2:($he?1:0)).($nb?"":", '".h('Use edit link to modify this value.')."'").");\">$V";}}}if($xa)echo"<td>";$b->backwardKeysPrint($xa,$M[$B]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n";}if(($M||$F)&&!is_ajax()){$yb=true;if($_GET["page"]!="last"){if(!+$x)$n=count($M);elseif($v!="sql"||!$u){$n=($u?false:found_rows($S,$Y));if($n<max(1e4,2*($F+1)*$x))$n=reset(slow_query(count_rows($a,$Y,$u,$p)));else$yb=false;}}if(+$x&&($n===false||$n>$x||$F)){echo"<p class='pages'>";$Fc=($n===false?$F+(count($M)>=$x?2:1):floor(($n-1)/$x));if($v!="simpledb"){echo'<a href="'.h(remove_from_uri("page"))."\" onclick=\"pageClick(this.href, +prompt('".'Page'."', '".($F+1)."'), event); return false;\">".'Page'."</a>:",pagination(0,$F).($F>5?" ...":"");for($q=max(1,$F-4);$q<min($Fc,$F+5);$q++)echo
pagination($q,$F);if($Fc>0){echo($F+5<$Fc?" ...":""),($yb&&$n!==false?pagination($Fc,$F):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Fc'>".'last'."</a>");}echo(($n===false?count($M)+1:$n-$F*$x)>$x?' <a href="'.h(remove_from_uri("page")."&page=".($F+1)).'" onclick="return !selectLoadMore(this, '.(+$x).', \''.'Loading'.'...\');" class="loadmore">'.'Load more data'.'</a>':'');}else{echo'Page'.":",pagination(0,$F).($F>1?" ...":""),($F?pagination($F,$F):""),($Fc>$F?pagination($F+1,$F).($Fc>$F+1?" ...":""):"");}}echo"<p class='count'>\n",($n!==false?"(".($yb?"":"~ ").lang(array('%d row','%d rows'),$n).") ":"");$hb=($yb?"":"~ ").$n;echo
checkbox("all",1,0,'whole result',"var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$hb' : checked); selectCount('selected2', this.checked || !checked ? '$hb' : checked);")."\n";if($b->selectCommandPrint()){echo'<fieldset',($_GET["modify"]?'':' class="jsonly"'),'><legend>Modify</legend><div>
<input type="submit" value="Save"',($_GET["modify"]?'':' title="'.'Ctrl+click on a value to modify it.'.'"'),'>
</div></fieldset>
<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete"',confirm(),'>
</div></fieldset>
';}$Pb=$b->dumpFormat();foreach((array)$_GET["columns"]as$d){if($d["fun"]){unset($Pb['sql']);break;}}if($Pb){print_fieldset("export",'Export'." <span id='selected2'></span>");$cd=$b->dumpOutput();echo($cd?html_select("output",$cd,$ia["output"])." ":""),html_select("format",$Pb,$ia["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}echo(!$p&&$N?"":"<script type='text/javascript'>tableCheck();</script>\n");}if($b->selectImportPrint()){print_fieldset("import",'Import',!$M);echo"<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$ia["format"],1);echo" <input type='submit' name='import' value='".'Import'."'>","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($rb,'strlen'),$e);echo"<p><input type='hidden' name='token' value='$re'></p>\n","</form>\n";}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["script"])){if($_GET["script"]=="kill")$f->query("KILL ".number($_POST["kill"]));elseif(list($R,$r,$C)=$b->_foreignColumn(column_foreign_keys($_GET["source"]),$_GET["field"])){$x=11;$J=$f->query("SELECT $r, $C FROM ".table($R)." WHERE ".(preg_match('~^[0-9]+$~',$_GET["value"])?"$r = $_GET[value] OR ":"")."$C LIKE ".q("$_GET[value]%")." ORDER BY 2 LIMIT $x");for($q=1;($L=$J->fetch_row())&&$q<$x;$q++)echo"<a href='".h(ME."edit=".urlencode($R)."&where".urlencode("[".bracket_escape(idf_unescape($r))."]")."=".urlencode($L[0]))."'>".h($L[1])."</a><br>\n";if($L)echo"...\n";}exit;}else{page_header('Server',"",false);if($b->homepage()){echo"<form action='' method='post'>\n","<p>".'Search data in tables'.": <input name='query' value='".h($_POST["query"])."'> <input type='submit' value='".'Search'."'>\n";if($_POST["query"]!="")search_tables();echo"<table cellspacing='0' class='nowrap checkable' onclick='tableClick(event);'>\n",'<thead><tr class="wrap"><td><input id="check-all" type="checkbox" onclick="formCheck(this, /^tables\[/);" class="jsonly"><th>'.'Table'.'<td>'.'Rows'."</thead>\n";foreach(table_status()as$R=>$L){$C=$b->tableName($L);if(isset($L["Engine"])&&$C!=""){echo'<tr'.odd().'><td>'.checkbox("tables[]",$R,in_array($R,(array)$_POST["tables"],true),"","formUncheck('check-all');"),"<th><a href='".h(ME).'select='.urlencode($R)."'>$C</a>";$V=format_number($L["Rows"]);echo"<td align='right'><a href='".h(ME."edit=").urlencode($R)."'>".($L["Engine"]=="InnoDB"&&$V?"~ $V":$V)."</a>";}}echo"</table>\n","<script type='text/javascript'>tableCheck();</script>\n","</form>\n";}}page_footer();