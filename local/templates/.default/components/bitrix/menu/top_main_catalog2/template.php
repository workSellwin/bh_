<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?//$arResult = getChilds($arResult)?>
<?if (!empty($arResult)):?>
    <!--start_content-->
    <div class="nav-main-lnks-my">
        <nav>
            <ul class="topmenu">
            <? foreach ($arResult as $key => $arItem):
                $data_parent_link = str_replace('/', '_', $arItem['LINK'])?>
                <li onmouseenter="mouselogLi(event)" onmouseleave="mouselogLi(event)" data-link="<?=$data_parent_link?>">
                    <a href="<?= $arItem['LINK'] ?>" class="" ><?=$arItem["TEXT"]?>
                        <?if(!empty($arItem['CHILD']) || !empty($arItem['BRANDS']) || !empty($arItem['SERII']) || !empty($arItem['IMG'])):?>
                            <span class="fa fa-angle-down"></span>
                        <?endif;?>
                    </a>
                </li>
            <? endforeach ?>
        </nav>
    </div>
<?endif?>


<? foreach ($arResult as $arItem):
    $data_parent_link = str_replace('/', '_', $arItem['LINK'])?>
    <? if (!empty($arItem['CHILD']) || !empty($arItem['BRANDS']) || !empty($arItem['SERII']) || !empty($arItem['IMG'])):?>

		<?//PR($arItem);?>
        <div class="submenu-box" onmouseenter="mouselogDiv(event)" onmouseleave="mouselogDiv(event)" data-submenu-link="<?=$data_parent_link?>">
            <div class="flex-box-menu" style="display: flex">

                    <div class="submenu-box--item">
                        <h5>Категория</h5>
                        <?if(!empty($arItem['CHILD'])):?>

                            <? foreach ($arItem['CHILD'] as $val): ?>
                                <a href="<?=$val['SECTION_PAGE_URL']?>"><?=$val["NAME"]?></a>
                            <? endforeach ?>
                        <?endif;?>
                    </div>

                    <div class="submenu-box--item" id="box_products">
                        <h5>Продукты</h5>
                        <?if(!empty($arItem['PRODUCTS'])):?>

                            <?foreach ($arItem['PRODUCTS'] as $k => $br):?>
                                <a href="<?=$br?>" mousever=""><?=$k?></a>
                            <?endforeach;?>
                        <?endif;?>
                    </div>

                    <div class="submenu-box--item" id="box_brands">
                        <h5>Бренды</h5>
                        <?if(!empty($arItem['BRANDS'])):?>

                            <?foreach ($arItem['BRANDS'] as $k => $br):?>
                                <a href="<?=$br?>" mousever=""><?=$k?></a>
                            <?endforeach;?>
                        <?endif;?>
                    </div>

                    <div class="submenu-box--item" id="box_serii">
                        <h5 style="display: none">Серии</h5>
                        <?/*if(!empty($arItem['SERII'])):*/?><!--

                            <?/*foreach ($arItem['SERII'] as $k => $br):*/?>
                                <a href="<?/*=$br*/?>" data-serii="Biolage"><?/*=$k*/?></a>
                            <?/*endforeach;*/?>
                        --><?/*endif;*/?>
                        <?if(!empty($arItem['BR_SR'])):?>

                            <?foreach ($arItem['BR_SR'] as $k => $br):?>
                                <?foreach ($br as $kl => $ser):?>
                                    <a href="<?=$ser['URL']?>" data-serii="<?=$k?>"><?=$ser['NAME']?></a>
                                <?endforeach;?>
                            <?endforeach;?>
                        <?endif;?>
                    </div>


                <?if(!empty($arItem['IMG'])):?>
                    <?foreach ($arItem['IMG'] as $img):?>
                        <div class="submenu-box--item box-img">
                            <img src="<?=$img?>">
                        </div>
                    <?endforeach;?>
                <?endif;?>

            </div>

        </div>
    <? endif; ?>
<? endforeach ?>





<?//PR($arResult);?>

<?if (!empty($arResult)):?>
<!--start_content-->
	<div class="nav-main-lnks" style="display: none">
		<?
		foreach($arResult as $arItem):
			if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
				continue; ?>
			<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>

		<?endforeach?>
	</div>
<?endif?>






<script type="text/javascript">

    $('#box_brands a').hover(function () {
        var brands =  $(this).text();
        $('#box_serii a').each(function(){
            var  serii = $(this).attr('data-serii');
            if(serii == brands){
                $('#box_serii h5').css('display', 'block');
                $(this).css('display', 'block');
            }else{
                $(this).css('display', 'none');
            }
        });
    });



    function mouselogLi(event) {
        var this_link = event.target.dataset.link;
        var event_type = event.type;
        if(event_type == 'mouseenter'){
            $('.submenu-box').each(function () {
                $(this).hide();
            });
            $('.submenu-box[data-submenu-link='+this_link+']').show();
            $('.submenu-box[data-submenu-link='+this_link+']').css('z-index', '8');
            $('.topmenu li').each(function () {
                $(this).css('border-bottom', '1px solid #fff');
            });

            $(event.target).css('border-bottom', '1px solid black');
            $(event.target).css('z-index', '10');

            isVisible($('.submenu-box[data-submenu-link='+this_link+']'));
        }
        if(event_type == 'mouseleave'){
            $('.topmenu li').each(function () {
                $(this).css('border-bottom', '1px solid #fff');
            });
            $(event.target).css('border-bottom', '1px solid #fff');

            isVisible($('.submenu-box[data-submenu-link='+this_link+']'));
        }
    }


    function mouselogDiv(event) {
        var event_type = event.type;
        var this_li = $(event.target).attr('data-submenu-link');
        if(event_type == 'mouseenter'){
            $('.topmenu li').each(function () {
                $(this).css('border-bottom', '1px solid #fff');
            });
            $('.topmenu li[data-link='+this_li+']').css('border-bottom', '1px solid black');
            isVisible(event.target);

        }
        if(event_type == 'mouseleave'){
            $('.topmenu li').each(function () {
                $(this).css('border-bottom', '1px solid #fff');
            });
            $('.topmenu li[data-link='+this_li+']').css('border-bottom', '1px solid black');
            $('.submenu-box').each(function () {
                $(this).hide();
            });


            $('#box_serii a').each(function(){
                $('#box_serii h5').css('display', 'none');
                $(this).css('display', 'none');
            });


            isVisible(event.target);
        }
    }


    function isVisible(element) {
        if($(element).is(":visible")){
            var this_li = $(element).attr('data-submenu-link');
            $('.topmenu li[data-link='+this_li+']').css('border-bottom', '1px solid black');
        }else{
            $('.topmenu li').each(function () {
                $(this).css('border-bottom', '1px solid black');
            });
        }
    }

</script>