<?php echo $header; ?>
<?php if ($top) { ?>
    <section class="top"><?php echo $top; ?></section>
<?php } ?>
    <section id="container">
        <div class="container">
            <div class="row"><?php echo $column_left; ?>
                <?php if ($column_left && $column_right) { ?>
                    <?php $class = 'col-sm-6'; ?>
                <?php } elseif ($column_left || $column_right) { ?>
                    <?php $class = 'col-sm-9'; ?>
                <?php } else { ?>
                    <?php $class = 'col-sm-12'; ?>
                <?php } ?>
                <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?></div>
                <?php echo $column_right; ?></div>
        </div>
    </section>
    <section class="content_bottom container">
		<div class="row">
			<?php echo $content_bottom; ?>
		</div>
	</section>

<div class='embed-container maps'>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3691.7800282728367!2d-46.613074049666714!3d-22.286320985258858!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c909e0c2beec97%3A0xc6a3e46a607a3496!2sR.+Santo+Ant%C3%B4nio%2C+555%2C+Jacutinga+-+MG%2C+37590-000!5e0!3m2!1spt-BR!2sbr!4v1485798988540" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('.maps').click(function () {
        $('.maps iframe').css("pointer-events", "auto");
    });

    $( ".maps" ).mouseleave(function() {
      $('.maps iframe').css("pointer-events", "none"); 
    });
});
</script>

<style type="text/css">
    footer {
        margin-top: 0px !important;
    }
</style>

<?php if ($bottom) { ?>
    <section class="bottom"><?php echo $bottom; ?></section>
<?php }
echo $footer; ?>