# pkg_agadvents / Adventskalernder and Imagemap for Joomla! 3


# Quickstart

1. Install this package via Joomla! installer. 
Please check after the installation, if the menu item for the component is inserted into the menu.

![b1](https://user-images.githubusercontent.com/9974686/51182088-51b9bc80-18cd-11e9-9786-0fc902cff8fb.png)

2. Open the component via click on `Components | (agadvent | imagemap)`  
You see the sample data.

![b2](https://user-images.githubusercontent.com/9974686/51182087-51212600-18cd-11e9-9927-b9402be6f138.png)

3. Open the category view via click on `category` in the left side menu
The category is the place, where you have to store all clickable items on the 
image. 
You see the sample date. 

![b3](https://user-images.githubusercontent.com/9974686/51182086-51212600-18cd-11e9-9b60-9e583809e681.png)

4. After that create a new category
Click on `new` in the top menu. Now you see the form with all information 
of the category. You have to insert the title and  your image here. 

![b4](https://user-images.githubusercontent.com/9974686/51182085-51212600-18cd-11e9-9415-f18dbcddaa85.png)

5. Click on `save and close` in the top menu and check if your category is created

![b5](https://user-images.githubusercontent.com/9974686/51182084-51212600-18cd-11e9-91cd-aed3840cc941.png)

6. Open the view agadvents  
Now you can create a item that will open if you click on a special area of the image.

![b6](https://user-images.githubusercontent.com/9974686/51182083-51212600-18cd-11e9-9950-50c75b005b69.png)

7. Click on `new` in the top menu  
Now you see the form for editing the informations of the item. Important ist, 
that you `choose the category` and `save` the item.

![b7](https://user-images.githubusercontent.com/9974686/51182081-50888f80-18cd-11e9-80cc-e3e7e157787f.png)

8. Scool to the bottom  
After you have `choosen the category` and `saved` the item 
you see the image of the category at the bottom. Now you can 
select the clickable area with your mouse. You can only choose an area in the shape of an rectangle.

![b8](https://user-images.githubusercontent.com/9974686/51182080-50888f80-18cd-11e9-9bd6-e5f76055d358.png)

All other options are self-explanatory.

9. If you have inserted all informations you can `save and close` the item.

![91](https://user-images.githubusercontent.com/9974686/51182079-50888f80-18cd-11e9-80a7-5ed2b40e04fa.png)

10. Open the the menu menu in the Joomla toolbar

![b9](https://user-images.githubusercontent.com/9974686/51182077-50888f80-18cd-11e9-8c6f-b9fc479e368b.png)

11. Click on `menu item` and then on `new`

![10](https://user-images.githubusercontent.com/9974686/51182075-4feff900-18cd-11e9-9c6e-5726dba8322e.png)

12. Insert a `title` and click on `select`

![10a](https://user-images.githubusercontent.com/9974686/51182071-4feff900-18cd-11e9-9683-f51acdd6a859.png)

13. Choose the entry `imagemap`

![b11](https://user-images.githubusercontent.com/9974686/51182073-4feff900-18cd-11e9-8b3c-3e3529e05977.png)

14. Choose the category

![b12](https://user-images.githubusercontent.com/9974686/51182072-4feff900-18cd-11e9-9fed-d0293852cc94.png)

15. Open the menu item in front end and you can click the area you choosen in point 8. The content opens either in a popup or in the content area of your page, depending on what you have selected in the preferences.

![13](https://user-images.githubusercontent.com/9974686/51182070-4feff900-18cd-11e9-8bc3-3421c5c1d944.png)

Voila!


# All parts of this extension

## Module: Agadvents  
The module allows you to display an image map on any position of your website.

## Plugin: Button - Agadvents  
If you activate this extension, a button for inserting an imagemap is inserted into an article.

## Plugin: Installer - Agadvents  
This extension is currently not used.

## Plugin: Search - Agadvents  
If you enable this extension, the Joomla search will be extended to contents of this extension.

## Plugin: Smart Search - Agadvents 
If you enable this extension, the Joomla smart search will be extended to contents of this extension.
 
## Plugin: System - Agadvents  
If you enable this extension, you can use the Joomla statistics in this extension.


You may be wondering why I have so many plugins integrated into this little 
extension. Very easily: I used this extension to learn many Joomla 3 functions. 
Advent calendar for Joomla! 3.7 with selectable background and freely configurable windows.

- Plugins for statistics, search and smart search are available.
- The advent calendar can be displayed in a module at any position on the website.
- The component supports versions, language associations, and custom fields.
- The content of a advent item will be opened in the content area or in a pop up.
- Test mode to test individual days in advance.
- The Advent Calendar can also be used as an image map.
- Several advent calendars can be active at the same time. So you can display content from a previous year as a kind of archive.


# FAQ

## The calendar works not correct on safari. The clickable areas are not on the right place. What can I do?

Please switch to the simple mode in the options of this component.

## The close button of the popup is behind a sticky top menu and can not closed 

Add .vbox-close { margin-top:20%; } to your custom css file.


# Support and New Features

This Joomla! Extension is a simple feature. But it is most likely, that your requirements are 
already covered or require very little adaptation.

If you have more complex requirements, need new features or just need some support, 
I am open to doing paid custom work and support around this Joomla! Extension. 

Contact me and we'll sort this out!
