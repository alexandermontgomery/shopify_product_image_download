# Shopify Product Image Download
This is a script that will parse a [Shopify product export](http://docs.shopify.com/manual/your-store/products/export-products) and download all the files. In order to use it you must first download a [Shopify product export](http://docs.shopify.com/manual/your-store/products/export-products). The script expects the following command line arguments:

    --export        The location of the csv to parse
    --destination   The directory the script to create to save all the images in
    
#### Sample Usage
    php download_images.php --export products_export.csv --destination shopify_product_images