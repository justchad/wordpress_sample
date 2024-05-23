<?php if( count( (array) $location->languages ) >= 1 ) : ?>
    <div class="my-10 text-center mt-12">
        <h2 class="hdg-3 font-bold mb-3 md:mb-10">
            Multilingual
        </h2>
        <div class="max-4-col mx-auto bg-brand-light-gray grid gap-4 py-0 px-6 text-center rounded-md p-6 rounded-md">
            <ul class="language-list text-center -mx-3 my-5 flex flex-wrap justify-center">
                <?php foreach( $location->languages as $key => $language ) : ?>
                    <li class="text-sm text-gray-300 text-center mx-3 my-2 <?php echo $language; ?>">
                        <div class="font-medium">
                            <?php echo ucfirst( $language ); ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul> <!-- /.language-list -->
        </div>
    </div>
<?php endif; ?>
