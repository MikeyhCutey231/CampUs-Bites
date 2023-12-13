# jQuery PH Locations

jQuery Plugin for displaying dropdown list of Philippines' Region, Province, City and Barangay in your static webpage.


![jQuery PH Locations demo](https://f001.backblazeb2.com/file/buonzz-assets/jquery-ph-locations-demo.gif)

## Features

* Dynamically generate option items (you only need empty select tags)
* No server-side scripts needed - it comes with its own free API for serving the data
* Free usage of our API to filter locations on the server side (there is no crazy filtering in localStorage or similar)
* Easily embed in static sites in any domain

## Usage

1. install it by putting this in your HTML code (head or right before footer)

```
<script src="https://f001.backblazeb2.com/file/buonzz-assets/jquery.ph-locations-v1.0.0.js"></script>
```
or upload the jquery.ph-locations-v1.0.0.js somewhere in your server and reference it.

2. create the markup
```
<select name="city" id="my-city-dropdown"></select>
```

3. initialize the control
```
$('#my-city-dropdown').ph_locations({'location_type': 'cities'});
```
the **location_type** is the only required parameter to initialize the plugin. 

4. populate the dropdown with items (and optionally pass any filter)

```
$('#my-city-dropdown').ph_locations( 'fetch_list', [{"province_code": "1339"}]);
```
see more below about the codes assigned for each location.

## Configuration 

When initializing the plugin, you need to pass the location_type setting so that it knows what kind of data you are trying to display (region, province, city, barangay). The possible values are:

* **regions** - this dropdown will gonna be filled with list of region
* **provinces** - this dropdown will gonna be filled with list of province
* **cities** - this dropdown will gonna be filled with list of cities
* **barangays** - this dropdown will gonna be filled with list of barangay

in order to populate the dropdown with items, you need to call the **fetch_list** function by the plugin. The fetch_list function can accept **filters** that allows you to limit the number of items to show. For example, the dropdown above will list only show cities that is under Manila City, represented by province_code 1339. The 1339 value is the assigned code by  [Philippine Standard Geographic Codes (PSGC)](https://psa.gov.ph/classification/psgc/)

## Demo

check the demo folder that comes with it for usage examples, like cascading dropdown.


