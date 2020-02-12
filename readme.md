# Pagination by instinct

### Functions

* `get_aniomalia_pagination()` - outputs array
* `aniomalia_pagination()` - outputs HTML

### get_aniomalia_pagination() array output

* `total` - total number of pages
* `first` - link to first page
* `previous` - link to previous page (if exists)
* `current` - number of current page
* `next` - link to next page (if exists)
* `last` - link to last page
* `pages` - array of pages with the following data:
	* `page` - page number
	* `url` - link to page
	* `current` - true or false if is the current page


### Function call arguments

```PHP
// shows only extra links (first, previous, next, last)
aniomalia_pagination(false);

// shows all pages with no reduction (1st parameter), hides previous and nezt page links (2nd parameter)
aniomalia_pagination(-1, false, true);

// shows all pages with no reduction, hides first and last page links (3rd parameter)
aniomalia_pagination(-1, true, false);

// shows only 3 pages at once
aniomalia_pagination(3);

// shows only 6 pages at once, 2 before and 3 after the current page
aniomalia_pagination(6);
```


### License

This plugin is released under a GPL license. Read more [here](http://www.gnu.org/licenses/gpl-2.0.html])