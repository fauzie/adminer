<?php
/**
 * Displays only one prefered action in table list.
 *
 * Get rid of schizophrenic decisions between selecting data and showing table structure.
 * Optimize your workflow!
 *
 * @author Peter Knut
 * @copyright 2014-2015 Pematon, s.r.o. (http://www.pematon.com/)
 */
class AdminerQuickFilterTables
{
	public function AdminerQuickFilterTables() {}
	
	public function head()
	{   
?>
		<style<?php echo nonce() ?>>
		.quick-filter{
			position: relative;
		}
			#quick{
				display:block;
				width: 96%;
    			margin: 0 2%;
    			padding:5px 6px;
    			-moz-box-sizing: border-box;
				-webkit-box-sizing: border-box;
				box-sizing: border-box;
				outline: none;
			}
			.clear{
				display: none;
			    position: absolute;
			    right: 3%;
			    top: 0;
			    text-align: center;
			    padding: 8px 9px;
			    font-size: 9px;
			    cursor: pointer;
			}
			body>#menu {
				width: 280px;
			}
			body>#menu p.links a {
                text-align: center;
			}
			body>#menu p.links a>span {
			    font-size: 12px;
			}
			body>#content {
			    margin-left: 290px;
			    margin-right: 20px;
			}
		</style>
		<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" <?php echo nonce() ?>>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.slim.min.js" <?php echo nonce() ?>></script>
		<script<?php echo nonce() ?>>
			jQuery(document).ready(function($){
				$('#tables').prepend('<div class="quick-filter"><input id="quick" placeholder="Filter tables"><div class="clear quick-clear">X</div></div>');
				$('.quick-clear').on('click',function(){
					$('#quick').val('').trigger('keypress');
				})
				$('#quick').on('keyup keypress',function(){
					var filter = $(this).val();
					localStorage.setItem('filter', filter);
					if(filter.length){
						$('.quick-clear').show();
						//hide all show only match
						$('#tables a').hide();
						$('#tables a[href*="'+filter+'"]').show();
					}else{
						$('.quick-clear').hide();
						$('#tables a').show();
					}
				}).val(localStorage.getItem('filter')).trigger('keypress');
				if ($('select[name^="Collation"]').length)
    				$('select[name^="Collation"]').val('utf8_general_ci');
    				
    			if ($('select[name*="collation"]').length)
    				$('select[name*="collation"]').val('utf8_general_ci');
    			
    			$('#menu p.links a').each(function(i,el) {
    			    var title = $(this).text();
    			    $(this).attr('title', title);
    			    if (i === 0) {
    			        $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-terminal fa-stack-1x fa-inverse"></i></span>');
    			    }
    			    else if (i === 1) {
    			        $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-download fa-stack-1x fa-inverse"></i></span>');
    			    }
    			    else if (i === 2) {
    			        $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-upload fa-stack-1x fa-inverse"></i></span>');
    			    }
    			    else if (i === 3) {
    			        $(this).html('<span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-plus fa-stack-1x fa-inverse"></i></span>');
    			    }
    			});
			})

		</script>

<?php
	}
	
}
