<?php if( $location->hours ) : ?>
    <div class="my-10 text-center mt-12">
        <h2 class="hdg-3 font-bold mb-3 md:mb-10">
            Branch Hours
        </h2>
        <div class="max-4-col mx-auto bg-brand-light-gray grid gap-4 py-0 px-6 text-center rounded-md p-6 rounded-md">
            <ul class="language-list text-center -mx-3 my-5 flex flex-wrap justify-center">

                <?php if( $location->hours->weekdays ) : ?>
                    <li class="text-sm text-gray-300 text-center mx-3 my-2">
                        <div class="font-medium">
                            <?php echo $location->hours->weekdays; ?>
                        </div>
                    </li>
                <?php endif ; ?>
                <?php if( $location->hours->saturday ) : ?>
                    <li class="text-sm text-gray-300 text-center mx-3 my-2">
                        <div class="font-medium">
                            <?php echo $location->hours->saturday; ?>
                        </div>
                    </li>
                <?php endif ; ?>
                <?php if( $location->hours->sunday ) : ?>
                    <li class="text-sm text-gray-300 text-center mx-3 my-2">
                        <div class="font-medium">
                            <?php echo $location->hours->sunday; ?>
                        </div>
                    </li>
                <?php endif ; ?>
            </ul> <!-- /.bg-gray-100 -->
        </div>
    </div>
<? endif ; ?>
