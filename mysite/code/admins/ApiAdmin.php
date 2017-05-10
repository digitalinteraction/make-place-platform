<?php
/**
 *  An admin menu to manage api keys
 */
class ApiAdmin extends ModelAdmin {
    
    /** The models this interface managed; Station & MetroLine in this case */
    
    private static $managed_models = [ "ApiKey" ];
	
	/** What url segment should be used to display this page */
	private static $url_segment = "api";

	/** The title of the menu item to navigate to this page */
	private static $menu_title = "Api";
    
}
