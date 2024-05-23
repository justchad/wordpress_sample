<?php echo $content->title; ?>
<?php echo $post->post_content; ?>

<!-- PHONE AND LINKS -->
<div class="flex items-center justify-center mt-10">
    <a href="<?php echo $location->inventory_link; ?>" class="mx-1 lg:mx-6 font-medium hover:text-brand-primary inline-flex items-center">
        <svg class="icon icon-search text-brand-primary mr-2 text-lg svg-align">
            <use xlink:href="#icon-search"></use>
        </svg>
        Inventory
    </a>
    <?php if ( $location->contact->toll_free_1 ) : ?>
        <a href="tel:<?php echo $location->contact->toll_free_1; ?>" class="mx-1 lg:mx-6 font-medium hover:text-brand-primary inline-flex items-center">
            <svg class="icon icon-phone text-brand-primary mr-2 text-lg svg-align">
                <use xlink:href="#icon-phone"></use>
            </svg>
            Call
        </a>
    <?php endif; ?>
    <?php if ( $location->address->link ) : ?>
        <a href="<?php echo $location->address->link; ?>" class="mx-1 lg:mx-6 font-medium hover:text-brand-primary inline-flex items-center" target="_blank">
            <svg class="icon icon-pin text-brand-primary mr-2 text-lg svg-align">
                <use xlink:href="#icon-pin"></use>
            </svg>
            Directions
        </a>
    <?php endif; ?>
</div> <!-- /.flex -->
