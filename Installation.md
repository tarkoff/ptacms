#installation guide.

# PTA CMS installation guide #

You have to make couple simple steps for installation sites based on PTA CMS.

Add your content here.  Format your content with:
  1. First of all you need to do checkout P.T.A. CMS kernel files from SVN repository into the root of your site.
    * Install subversion client if you need.
    * Execute in console:
```
svn checkout http://ptacms.googlecode.com/svn/trunk/
```
  1. Download Zend Framework and copy **Zend** folder with framework files to **/<path to your site>/library** folder.
  1. Execute in console with admin rights next commads in your site root path
```
chmod -R 0755 .
chmod -R 0777 public/content
chmod -R 0764 public/timthumb/cache
chmod -R 0764 system/cache/admin/templates_c
chmod -R 0764 system/cache/site/templates_c
```
  1. As result you must have next folder structure
    * /<path to your site>
      * app
        * admin
        * global
        * site
      * library
        * PTA
        * Smarty
        * Zend
      * public
        * content
        * css
        * js
        * style
        * timthumb
      * system
        * backups
        * cache
        * logs
        * utils
  1. That's all.)