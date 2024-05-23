<!-- PHONE -->
<?php if ( $employee->phone ) : ?>

    <a class="inline-flex items-center mt-6 mr-8 hover:text-brand-primary" href="tel:<?php echo strip_phone( $employee->phone ); ?>">
        <svg class="icon icon-phone text-brand-primary text-xl mr-2" aria-hidden="true">
            <use xlink:href="#icon-phone"></use>
        </svg>
        Call
    </a>

<?php else : ?>

    <a class="inline-flex items-center mt-6 mr-8 hover:text-brand-primary" href="tel:<?php echo strip_phone( $location->contact->toll_free_1 ); ?>">
        <svg class="icon icon-phone text-brand-primary text-xl mr-2" aria-hidden="true">
            <use xlink:href="#icon-phone"></use>
        </svg>
        Call Branch
    </a>

<?php endif; ?>

<!-- EMAIL -->
<?php if ( $employee->email ) : ?>

    <a class="inline-flex items-center mt-6 mr-8 hover:text-brand-primary" href="mailto:<?php echo $employee->email; ?>">
        <svg class="icon icon-mail text-brand-primary text-xl mr-2" aria-hidden="true">
            <use xlink:href="#icon-mail"></use>
        </svg>
        Email
    </a>

<?php endif; ?>

<!-- V-CARD -->
<?php if ( $employee->v_card_href ) : ?>

    <a href="<?php echo $employee->v_card_href; ?>" target="_blank" class="btn-ghost mt-6">
        Save to Contacts
    </a>

<?php endif; ?>
