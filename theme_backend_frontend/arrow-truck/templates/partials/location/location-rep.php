<article class="employee-card relative bg-brand-light-gray rounded-b-md relative mx-gutter rep">
    <div class="bg-brand-primary h-28 rounded-t-md"></div>
    <figure class="aspect-square shadow absolute overflow-hidden rounded-full w-32 top-0 left-1/2 transform -translate-x-1/2 mt-6 rep-profile-photo" data-api="<?php echo $rep->profile; ?>">
        <?php
            ll_include_component(
                'fit-image',
                [
                    'image_id'          => $rep->profile,
                    'thumbnail_size'    => 'full'
                ]
            );
        ?>
    </figure>
    <div class="px-6 pb-6 pt-16">
        <h3 class="paragraph-base font-bold text-gray-400 mb-1 rep-full-name" data-api="<?php echo $rep->name->full; ?>">
            <?php echo $rep->name->full; ?>
        </h3>
        <p class="paragraph-small text-gray-400 mb-2 rep-title" data-api="<?php echo $rep->title; ?>">
            <?php echo $rep->title; ?>
        </p>
        <?php if ( $rep->languages ) : ?>
            <div class="text-xs uppercase" aria-hidden="true">Multilingual</div>
            <ul style="margin-bottom:.5rem;display: flex;justify-content: space-evenly;flex-wrap: wrap;">
                <?php foreach( (array) $rep->languages as $key => $language ) : ?>
                    <li class="inline-block <?php echo $language ?>" data-api="<?php echo $language; ?>" style="display: flex;align-items: center;justify-content: center;">
                        <span class="mb-1/2 mt-1 text-xs" data-api="<?php echo $language ?>">
                            <?php echo ucfirst( $language ); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <a class="absolute inset-0" href="<?php echo $rep->href; ?>?branch=<?php echo $location->ID; ?>">
        <span class="sr-only">View <?php echo $rep->name->first; ?>'s page</span>
    </a>
</article>
