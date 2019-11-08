/*******************************************************************
 Utility functions and settings
 *******************************************************************/
$.support.cors = true;

$.ajaxSetup ({
    // Disable caching of AJAX responses,
    // very helpful for debugging!
    // http://stackoverflow.com/a/168977/2619926
    cache: false
});

/**
 *
 */
var reloadSecure = function(hash) {
  if (location.protocol != 'https:') {
    location.href = 'https:' + window.location.href.substring(
      window.location.protocol.length) + hash;
  }
}

var securePath = function(relativePath) {
  if (location.protocol == 'https:') {
    return relativePath;
  }

  var dirname = location.pathname.replace(/\/[^\/]*$/,'');
  return "https:/" + dirname + "/secure/" + relativePath;
}

/**
 * @param grid an array of uniform objects
 */
var makeTable = function(grid) {
  var table = $("<table>");
  
  // table headings
  var topRow = $("<tr>");
  for (var key in grid[0]) {
    var heading = $("<th>")
    heading.append(key);
    topRow.append(heading);
  }
  $(table).append(topRow);
  
  var makeRow = function (index, value) {
    var row = $("<tr>");
    
    var makeCell = function (key, val) {
      var cell = $("<td>");
      cell.append(val)
      $(row).append(cell);
    };
    $.each(value, makeCell);
    
    $(table).append(row);
  };
  
  // table body
  $.each(grid, makeRow);
  
  return table;
}

var displayAlert = function(label, details) {
    var html = "<h3>" + label + ":</h3><pre>" +
      JSON.stringify(details, null, 2) +
      "</pre>Please re-submit the form:<hr>";
    $("#fodder").html(html);
}

var displayError = function(jqXHR, textStatus, errorThrown) {
  alert(textStatus + "\n" + errorThrown);
};

var tidyUp = function() {
  //alert("Done with ajax.");
};

var showUndefined = function() {
  var href = $(this).attr("href");
  $("#alert").html( "&nbsp;");
  $("#fodder").html( "The " + href + " page is under construction.");
}

/*******************************************************************
 Functions and variables to load, submit and display results
 of the login form.
 *******************************************************************/
var actionLogin;
var populateLogin;

var displayLoginResult = function(data) {
  $("#fodder").html("<pre>" + data + "</pre>");
  //alert("examine the raw data");
  var json = JSON.parse(data);
  populateLogin = json.submitted;
  var query = json.query;
  var result = json.result;
  if(Object.keys(json.errors).length > 0) {
    displayAlert("Errors", json.errors);
    return;
  }
  
  if(typeof json.success == true) {
    $("#fodder").html("Login successful");
  }
};

/**
 * Submit a form, and invoke a handler for the result.
 * @param event the submit event object.
 * @param this the form being submitted.  
 *   $(this)[0] contains the form data.
 */
var submitLoginForm = function(event) {
  var data = new FormData($(this)[0]);
  //var data = new FormData($(this));
  try {
    $.ajax({
      url: actionLogin,
      type: 'POST',
      data: data,
      success: displayLoginResult,
      cache: false,
      contentType: false,
      processData: false,
      error: displayError
    });
  } catch(error) {
    alert("ajax error: " + error);
  }
  event.preventDefault();
};

var redirectLoginForm = function (data) {
  var action = $("#alert form").attr("action");
  actionLogin = securePath(action);
  $('#alert form').submit(submitLoginForm);

  if (typeof populateLogin !== 'undefined') {
    $('#alert form select[name="username"]').val(populateLogin.username);
    $('#alert form input[name="password"]').val(populateLogin.password);
  }
}

var loadLoginForm = function(event) {
  reloadSecure(this.hash);
  var filename = securePath("login.html");
  $("#alert").load(filename + " form", redirectLoginForm);
  $("#fodder").html( "&nbsp;");
};

/*******************************************************************
 Functions and variables to load, submit and display results
 of the logout form.
 *******************************************************************/

var displayLogoutResult = function(data) {
  $("#fodder").html("<pre>" + data + "</pre>");
  var json = JSON.parse(data);
  if(Object.keys(json.errors).length > 0) {
    displayAlert("Errors", json.errors);
    return;
  }
  
  if(typeof json.success == true) {
    $("#fodder").html("Login successful");
  }
};

/**
 * Logout and invoke a handler for the result.
 * @param event the submit event object, ignored
 */
var logout = function(event) {
  try {
    $.ajax({
      url: securePath("logout.php"),
      // type: 'POST',
      // data: data,
      success: displayLogoutResult,
      cache: false,
      contentType: false,
      processData: false,
      error: displayError
    });
  } catch(error) {
    alert("ajax error: " + error);
  }
  event.preventDefault();
};

/*******************************************************************
 Functions and variables to load, submit and display results
 of the selection form.
 *******************************************************************/
var actionSelect;
var populateSelect;

var displaySelectResult = function(data) {
  $("#fodder").html("<pre>" + data + "</pre>");
  //alert("examine the raw data");
  var json = JSON.parse(data);
  populateSelect = json.submitted;
  var query = json.query;
  var result = json.result;
  if(Object.keys(json.errors).length > 0) {
    displayAlert("Errors", json.errors);
    //loadSelectForm();
    return;
  }
  
  if(typeof json.success !== 'undefined') {
    var message = "The query " +
      "<pre>" + query + "</pre>" +
      "returned " + result.length + " records.";
    $("#fodder").html(message);
    
    if(result.length > 0) {
      $("#fodder").append(makeTable(result));
    }
  }
};

/**
 * Submit a form, and invoke a handler for the result.
 * @param event the submit event object.
 * @param this the form being submitted.  
 *   $(this)[0] contains the form data.
 */
var submitSelectForm = function(event) {
  var data = new FormData($(this)[0]);
  //var data = new FormData($(this));
  try {
    $.ajax({
      url: actionSelect,
      type: 'POST',
      data: data,
      success: displaySelectResult,
      cache: false,
      contentType: false,
      processData: false,
      error: displayError
    });
  } catch(error) {
    alert("ajax error: " + error);
  }
  event.preventDefault();
};

var redirectSelectForm = function (data) {
  action = $("#alert form").attr("action");
  actionSelect = securePath(action);
  $('#alert form').submit(submitSelectForm);

  if (typeof populateSelect !== 'undefined') {
    $('#alert form select[name="menu"]').val(populateSelect.menu);
    $('#alert form input[name="search"]').val(populateSelect.search);
  }
}

var loadSelectForm = function(event) {
  reloadSecure(this.hash);
  var filename = securePath("select.html");
  $("#alert").load(filename + " form", redirectSelectForm);
  $("#fodder").html( "&nbsp;");
};

/*******************************************************************
 Functions and variables to load, submit and display results
 of the append form.
 *******************************************************************/
var actionAppend;
var populateAppend;

var displayAppendResult = function(data) {
  $("#fodder").html("<pre>" + data + "</pre>");
  //alert("examine the raw data");
  var json = JSON.parse(data);
  populateAppend = json.submitted;
  var query = json.query;
  var result = json.result;
  if(Object.keys(json.errors).length > 0) {
    displayAlert("Errors", json.errors);
    //loadAppendForm();
    return;
  }
  
  if(typeof json.success !== 'undefined') {
    $("#fodder").html("Insertion succeeded.");
  }
};

var submitAppendForm = function(event) {
  var data = new FormData($(this)[0]);
  //var data = new FormData($(this));
  try {
    $.ajax({
      url: actionAppend,
      type: 'POST',
      data: data,
      success: displayAppendResult,
      cache: false,
      contentType: false,
      processData: false,
      error: displayError
    });
  } catch(error) {
    alert("ajax error: " + error);
  }
  event.preventDefault();
};

var redirectAppendForm = function (data) {
  action = $("#alert form").attr("action");
  actionAppend = securePath(action);
  $('#alert form').submit(submitAppendForm);

  // populate the form based on history
  if (typeof populateAppend !== 'undefined') {
    $('#alert form select[name="breeds"]').val(populateAppend.breeds);
    $('#alert form input[name="petname"]').val(populateAppend.name);
  }
};

var loadAppendForm = function(event) {
  reloadSecure(this.hash);
  var filename = securePath("input.html");
  $("#alert").load(filename + " form", redirectAppendForm);
  $("#fodder").html( "&nbsp;");
};

/*******************************************************************
 Ready at last!  Load all components and begin.
 *******************************************************************/
$(document).ready(function() {
 // $('a[href="#home"]').click(showUndefined);
 $('a[href="#login"]').click(loadLoginForm);
 // $('a[href="#logout"]').click(logout);
 $('a[href="#select"]').click(loadSelectForm);
  $('a[href="#append"]').click(loadAppendForm);
//  $('a[href="#about"]').click(showUndefined);
//  $('a[href="#support"]').click(showUndefined);
//  $('a[href="#contact"]').click(showUndefined);
//  $('a[href="#"]').click(showUndefined);

  // there's a #hash if the page has been reloaded,
  // e.g. by calling reloadSecure()
  hash = location.hash;
  if(hash == undefined) {
    return;
  }

  // if so, act as though the link has been clicked
  selector = "a[href='" + hash + "']";
  jqObject = $(selector);
  if(jqObject.length == 1) {
    jqObject.each(function() {
      $(this).click();
    });
  }
});