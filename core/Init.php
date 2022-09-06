<?php
/**
 * @package  Nolan_Group.
 */
namespace Nolan_Group;

final class Init
{
	/**
	 * Store all the classes inside an array.
	 * @return array Full list of classes.
	 */
	public static function get_services()
	{
		return [
			PostTypes\Products::class,
			PostTypes\Brands::class,
			PostTypes\Guides::class,
			PostTypes\CaseStudies::class,
			PostTypes\Brochures::class,
            
            Filters\Links::class,
            Filters\Breadcrumbs ::class,
            Filters\Paginations::class,
            Filters\Brands::class,
            Filters\Products::class,
			Filters\CategorySideBar::class,
			Filters\FacetWP::class,

            Actions\PostTypes::class,
            Actions\ProductTaxonomies::class,
            
			Taxonomies\ProductCategory::class,
            
            Shortcodes\ProductCarousel::class,
            Shortcodes\ContactCTA::class,
            Shortcodes\BrandsFeatured::class,
            Shortcodes\TaxonomyIcon::class,

			Pages\ManualSync::class,
			Pages\BackgroundSync::class,

			ScheduledActions\ProcessSync::class,
		];
	}

	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services()
	{
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class.
	 * @param  class $class    class from the services array.
	 * @return class instance  new instance of the class.
	 */
	private static function instantiate( $class )
	{
		$service = new $class();

		return $service;
	}
}