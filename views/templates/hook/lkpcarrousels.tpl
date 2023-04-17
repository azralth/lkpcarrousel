{if $carrousels|count > 0}
    <div class="lkpcarrousel-wrapper">
        <div class="container">
            {foreach $carrousels as $carrousel}
                <div class="lkpc-item">
                    <div class="lkcp__header carrousel__header">
                        <div class="lkpc__title"><h2>{$carrousel.carrousel->getCarrouselTitle()}</h2></div>
                        {if !empty($carrousel.allProductsLink)}
                            <a class="all-product-link" href="{$carrousel.allProductsLink}">
                                {$carrousel.carrousel->getCarrouselBtnTitle()} <i class="mm-icon-fleche-lien"></i>
                            </a>
                        {/if}
                    </div>
                    {assign var="productscount" value=$carrousel.products|count}
                    <div class="products products-slick spacing-md-top{if $productscount > 1} products--slickmobile{/if}"
                         data-slick='{strip}
    {ldelim}
    "slidesToShow": 1,
    "slidesToScroll": 1,
    "mobileFirst":true,
    "arrows":true,
    "rows":0,
    "responsive": [
      {ldelim}
        "breakpoint": 992,
        "settings":
        {if $productscount > 1}
        {ldelim}
        "slidesToShow": {$carrousel.carrousel->getNbProductToShow()},
        "slidesToScroll": {$carrousel.carrousel->getNbProductToShow()},
        "dots":{$carrousel.show_dots},
        "arrows":{$carrousel.show_arrow}
        {rdelim}
        {else}
        "unslick"
        {/if}
      {rdelim},
      {ldelim}
        "breakpoint": 960,
        "settings":
        {if $productscount > 3}
        {ldelim}
        "dots":true,
        "arrows":true,
        "slidesToShow": 3,
        "slidesToScroll": 1
        {rdelim}
        {else}
        "unslick"
        {/if}
      {rdelim},
            {ldelim}
        "breakpoint": 768,
        "settings":
        {if $productscount > 3}
        {ldelim}
        "dots":true,
        "arrows":true,
        "slidesToShow": 2,
        "slidesToScroll": 1
        {rdelim}
        {else}
        "unslick"
        {/if}
      {rdelim},
      {ldelim}
        "breakpoint": 540,
        "settings":
        {if $productscount > 2}
        {ldelim}
        "dots":{$carrousel.carrousel->isShowBullet()},
        "arrows":{$carrousel.carrousel->isShowArrow()},
        "slidesToShow": 2,
        "slidesToScroll": 2
        {rdelim}
        {else}
        "unslick"
        {/if}
      {rdelim}
    ]{rdelim}{/strip}'>
                        {foreach from=$carrousel.products item="product"}
                            {include file="catalog/_partials/miniatures/product.tpl" product=$product}
                        {/foreach}
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
{/if}