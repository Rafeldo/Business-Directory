<?php require 'home_custom_search.php';  ?>
<?php $per_page = get_settings('business_settings', 'posts_per_page', 6); ?>
<!-- Page heading two starts -->
    <div class="page-heading-two">
      <div class="container">
        <h2><?php echo get_category_title_by_id($category_id);?></h2>
        <div class="breads">
            <a href="<?php echo site_url(); ?>"><?php echo lang_key('home'); ?></a> / 
            <?php 
            $CI = get_instance();
            $CI->load->model('admin/category_model');
            $category = $CI->category_model->get_category_by_id($category_id);
            if($category->parent!=0)
            {
                $parent = $CI->category_model->get_category_by_id($category->parent);
                echo '<a href="'.site_url('show/categoryposts/'.$parent->id.'/'.dbc_url_title(lang_key($parent->title))).'">'.get_category_title_by_id($category->parent).'</a>/';
            }
            ?>
            <?php echo get_category_title_by_id($category_id);?>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>   
    
<!-- Container -->
<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12 col-xs-12">
            <div class="block-heading-two">
                <h3><span><i class="fa fa-folder"></i> <?php echo lang_key('category');?> : <?php echo get_category_title_by_id($category_id);?></span>
                    <div class="pull-right list-switcher">
                        <a target="recent-posts" href="<?php echo site_url('show/categoryposts_ajax/'.$per_page.'/grid/'.$category_id);?>"><i class="fa fa-th "></i></a>
                        <a target="recent-posts" href="<?php echo site_url('show/categoryposts_ajax/'.$per_page.'/list/'.$category_id);?>"><i class="fa fa-th-list "></i></a>
                    </div>
                </h3>   
            </div>
            <span class="recent-posts">
            </span>
            <div class="ajax-loading recent-loading"><img src="<?php echo theme_url();?>/assets/img/loading.gif" alt="loading..."></div>
            <a href="" class="load-more-recent btn btn-blue" style="width:100%"><?php echo lang_key('load_more_posts');?></a>
            <script type="text/javascript">
            jQuery(document).ready(function(){

                var per_page = '<?php echo $per_page;?>';
                var recent_count = '<?php echo $per_page;?>';

                jQuery('.list-switcher a').click(function(e){
                    jQuery('.list-switcher a').removeClass('selected');
                    jQuery(this).addClass('selected');
                    e.preventDefault();
                    var target = jQuery(this).attr('target');
                    var loadUrl = jQuery(this).attr('href');
                    jQuery('.recent-loading').show();
                    jQuery.post(
                        loadUrl,
                        {},
                        function(responseText){
                            jQuery('.'+target).html(responseText);
                            jQuery('.recent-loading').hide();
                            if(jQuery('.recent-posts > div').children().length<recent_count)
                            {
                                jQuery('.load-more-recent').hide();
                            }
                            fix_grid_height();
                        }
                    );
                });

                jQuery('.load-more-recent').click(function(e){
                        e.preventDefault();
                        var next = parseInt(recent_count)+parseInt(per_page);
                        jQuery('.list-switcher a').each(function(){
                            var url = jQuery(this).attr('href');
                            url = url.replace('/'+recent_count+'/','/'+next+'/');
                            jQuery(this).attr('href',url);
                        });
                        recent_count = next;
                        jQuery('.list-switcher > .selected').trigger('click');
                });
            });
            </script>
        </div>


        <div class="col-md-3 col-sm-12 col-xs-12">
            <div class="sidebar">
                <?php
                $CI = get_instance();
                $CI->load->model('user/post_model');
                $category_id =  $CI->uri->segment(4);
                $CI->load->database();
                $CI->db->order_by('title','asc');
                $CI->db->where(array('status'=>1,'parent'=>$category_id));
                $query = $CI->db->get('categories');
                ?>
                <div class="s-widget">
                    <!-- Heading -->
                    <h5><i class="fa fa-sun-o color"></i>&nbsp; <?php echo lang_key('all_sub_categories');?></h5>
                    <!-- Widgets Content -->
                    <div class="widget-content hot-properties">
                        <?php if($query->num_rows()<=0){?>
                            <div class="alert alert-info"><?php echo lang_key('no_sub_categories');?></div>
                        <?php }else{?>
                            <ul class="list-unstyled list-6">
                                <?php
                                foreach ($query->result() as $post) {
                                    ?>
                                    <li class="col-xs-12 col-sm-6 col-md-12 col-lg-12"><a href="<?php echo site_url('show/categoryposts/'.$post->id.'/'.dbc_url_title(lang_key($post->title)));?>"><?php echo lang_key($post->title); ?> <span dir="rtl" class="color">(<?php echo $CI->post_model->count_post_by_category_id($post->id);?>)</span></a></li>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        <?php }?>
                    </div>
                </div>
                <div style="clear:both"></div>

                <?php render_widgets('right_bar_categories');?>
            </div>
        </div>

    </div>
</div>
