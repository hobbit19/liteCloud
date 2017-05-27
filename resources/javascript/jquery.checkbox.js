ar KEY_SPACE = 32;

$(document).ready(function() {

  var checkboxGroupApp = new checkboxGroup("cb1");

}); // end ready event

//
// Function keyCodes() is an object to contain keycodes needed for the application
//
function keyCodes() {
  this.space = 32;
}

//
// Function checkboxGroup() is a class constructor for the implementation of a checkbox group widget.
// checkboxGroup() requires an unordered list structure, with the first list entry being the group
// checkbox and the remaining entries being the checkboxes controlled by the group. Each list entry
// must contain an image tag that will be used to display the state of the checkbox.
//
// @param(list string) list is the id of the unordered list that checkboxgroup is to be bound to
//
// @return N/A
//
function checkboxGroup(list) {

  // define object properties
  this.$id = $('#' + list);
  this.keys = new keyCodes();

  this.unchecked = 0;
  this.checked = 1;
  this.mixed = 2;

  this.$groupBox = this.$id.find('li').first();
  this.$checkboxes = this.$groupBox.siblings();
  this.checkedCount = 0; // set to the number of checkboxes that are checked

  // initialize the checkboxGroup object
  this.init();

  // bind event handlers
  this.bindHandlers();

} // end checkboxGroup() constructor

// Function init() is a member function to initialize the checkboxGroup object. Initial checkbox
// states are set according to the aria-checked property of the checkboxes in the group.
//
// return N/A
//
checkboxGroup.prototype.init = function() {

  var thisObj = this;

  this.$checkboxes.each(function() {
    if ($(this).attr('aria-checked') == 'true') {
      thisObj.adjCheckedCount(true);
    }
  });

} // end init()

//
// Function bindHandlers() is a member function to bind event handlers to the checkboxes in the
// checkbox group.
//
// @return N/A
//
checkboxGroup.prototype.bindHandlers = function() {

  var thisObj = this;

  /////////// Bind groupbox handlers ////////////////
  
  // bind a click handler
  this.$groupBox.click(function(e) {
    return thisObj.handleGroupboxClick($(this), e);
  });

  // bind a keydown handler
  this.$groupBox.keydown(function(e) {
    return thisObj.handleGroupboxKeyDown($(this), e);
  });

  // bind a keypress handler
  this.$groupBox.keypress(function(e) {
    return thisObj.handleBoxKeyPress($(this), e);
  });

  // bind a mouseover handler
  this.$groupBox.mouseover(function(e) {
    return thisObj.handleBoxMouseOver($(this), e);
  });

  // bind a mouseout handler
  this.$groupBox.mouseout(function(e) {
    return thisObj.handleBoxMouseOut($(this), e);
  });

  // bind a focus handler
  this.$groupBox.focus(function(e) {
    return thisObj.handleBoxFocus($(this), e);
  });

  // bind a blur handler
  this.$groupBox.blur(function(e) {
    return thisObj.handleBoxBlur($(this), e);
  });

  /////////// Bind checkbox handlers ////////////////
  
  // bind a click handler
  this.$checkboxes.click(function(e) {
    return thisObj.handleCheckboxClick($(this), e);
  });

  // bind a keydown handler
  this.$checkboxes.keydown(function(e) {
    return thisObj.handleCheckboxKeyDown($(this), e);
  });

  // bind a keypress handler
  this.$checkboxes.keypress(function(e) {
    return thisObj.handleBoxKeyPress($(this), e);
  });

  // bind a mouseover handler
  this.$checkboxes.mouseover(function(e) {
    return thisObj.handleBoxMouseOver($(this), e);
  });

  // bind a mouseout handler
  this.$checkboxes.mouseout(function(e) {
    return thisObj.handleBoxMouseOut($(this), e);
  });

  // bind a focus handler
  this.$checkboxes.focus(function(e) {
    return thisObj.handleBoxFocus($(this), e);
  });

  // bind a blur handler
  this.$checkboxes.blur(function(e) {
    return thisObj.handleBoxBlur($(this), e);
  });

} // end bindHandlers()

// Function setBoxState() is a member function to set a checkbox state. This function sets the
// aria-checked property to the passed state value and changes the box image to display the new
// box state.
//
// @param($boxID object) $boxID is the jquery object of the checkbox to manipulate
//
// @param(state integer) state is the check state to set the box
//
// @return N/A
//
checkboxGroup.prototype.setBoxState = function($boxID, state) {

  var $img = $boxID.find('img');

  switch (state) {
    case this.unchecked: {
      $boxID.attr('aria-checked', 'false');
      $img.attr('src','http://www.oaa-accessibility.org/media/examples/images/checkbox-unchecked-black.png');

      break;
    }
    case this.checked: {
      $boxID.attr('aria-checked', 'true');
      $img.attr('src','http://www.oaa-accessibility.org/media/examples/images/checkbox-checked-black.png');
      break;
    }
    case this.mixed: {
      $boxID.attr('aria-checked', 'mixed');
      $img.attr('src','http://www.oaa-accessibility.org/media/examples/images/checkbox-mixed-black.png');
      break;
    }
  } // end switch

} // end setBoxState()

//
// Function adjCheckedCount() is a member function to increment or decrement the count of checked
// boxes. The function modifies the checkes state of the group box accordingly.
//
// @param(inc boolean) inc is true if incrementing the checked count, false if decrementing
//
// @return N/A
//
checkboxGroup.prototype.adjCheckedCount = function(inc) {

  // increment or decrement the count
  if (inc == true) {
    this.checkedCount++;
  }
  else {
    this.checkedCount--;
  }

  // modify the group box state
  if (this.checkedCount == this.$checkboxes.length) {
    // all the boxes are checked
    this.setBoxState(this.$groupBox, this.checked);
  }
  else if (this.checkedCount > 0) {
    // some of the boxes are checked
    this.setBoxState(this.$groupBox, this.mixed);
  }
  else {
    // all boxes are unchecked
    this.setBoxState(this.$groupBox, this.unchecked);
  }

} // end adjCheckedCount()


/////////////////////// Groupbox event handlers /////////////////////////////////

//
// Function handleGroupboxClick() is a member function to handle click events for group checkbox
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the keydown event
//
// @return (boolean) Returns false if processing; true of doing nothing
//
checkboxGroup.prototype.handleGroupboxClick = function($id, e) {
    
  var thisObj = this;

  if (e.altkey || e.ctrlKey || e.shiftKey) {
    // do nothing;
    return true;
  }

  switch ($id.attr('aria-checked')) {
    case 'true' : {
      // uncheck the group

      // clear the groupbox
      this.setBoxState($id, this.unchecked);

      // clear all the checkboxes in the group
      this.$checkboxes.each(function() {

        // clear the groupbox
        thisObj.setBoxState($(this), thisObj.unchecked);
      });

      // reset the checked count
      this.checkedCount = 0;

      break;
    }
    case 'mixed' :
    case 'false' : {
      // check the group

      // set the groupbox to checked
      this.setBoxState($id, this.checked);

      // check all the checkboxes in the group
      this.$checkboxes.each(function() {

        // clear the groupbox
        thisObj.setBoxState($(this), thisObj.checked);
      });

      // set the checked count
      this.checkedCount = this.$checkboxes.length;

      break;
    }
  } // end switch

  e.stopPropagation();
  return false;
  
} // end handleGroupboxClick()
  
//
// Function handleGroupboxKeyDown() is a member function to handle keydown events for the group checkbox
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the keydown event
//
// @return (boolean) Returns false if processing; true of doing nothing
//
checkboxGroup.prototype.handleGroupboxKeyDown = function($id, e) {
    
  var thisObj = this;

  if (e.altkey || e.ctrlKey || e.shiftKey) {
    // do nothing;
    return true;
  }

  if( e.keyCode == this.keys.space ) {

    switch ($id.attr('aria-checked')) {
      case 'true' : {
        // uncheck the group
  
        // clear the groupbox
        this.setBoxState($id, this.unchecked);
  
        // clear all the checkboxes in the group
        this.$checkboxes.each(function() {
  
          // clear the groupbox
          thisObj.setBoxState($(this), thisObj.unchecked);
        });
  
        // reset the checked count
        this.checkedCount = 0;
  
        break;
      }
      case 'mixed' :
      case 'false' : {
        // check the group
  
        // set the groupbox to checked
        this.setBoxState($id, this.checked);
  
        // check all the checkboxes in the group
        this.$checkboxes.each(function() {
  
          // clear the groupbox
          thisObj.setBoxState($(this), thisObj.checked);
        });
  
        // set the checked count
        this.checkedCount = this.$checkboxes.length;
  
        break;
      }
    } // end switch

    e.stopPropagation();
    return false;
  } // endif

  return true;
  
} // end handleGroupboxKeyDown()
  

/////////////////////// Checkbox event handlers /////////////////////////////////

//
// Function handleCheckboxClick() is a member function to handle click events for checkboxes
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the keydown event
//
// @return (boolean) Returns false if processing; true of doing nothing
//
checkboxGroup.prototype.handleCheckboxClick = function($id, e) {
    
  if (e.altkey || e.ctrlKey || e.shiftKey) {
    // do nothing;
    return true;
  }

  // toggle the checkbox state

  if($id.attr('aria-checked') == 'true') {
    this.setBoxState($id, this.unchecked);
    this.adjCheckedCount(false);
  } else {
    this.setBoxState($id, this.checked);
    this.adjCheckedCount(true);
  }  // endif

  e.stopPropagation();
  return false;
  
} // end handleCheckboxClick()
  
//
// Function handleCheckboxKeyDown() is a member function to handle keydown events for checkboxes
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the keydown event
//
// @return (boolean) Returns false if processing; true of doing nothing
//
checkboxGroup.prototype.handleCheckboxKeyDown = function($id, e) {
    
  if (e.altkey || e.ctrlKey || e.shiftKey) {
    // do nothing;
    return true;
  }

  if( e.keyCode == this.keys.space ) {

    // toggle the checkbox state

    if($id.attr('aria-checked') == 'true') {
      this.setBoxState($id, this.unchecked);
      this.adjCheckedCount(false);
    } else {
      this.setBoxState($id, this.checked);
      this.adjCheckedCount(true);
    }  // endif

    e.stopPropagation();
    return false;
  } // endif

  return true;
  
} // end handleCheckboxKeyDown()
  
////////////////////////////////// Common event handlers ///////////////////////////////////

//
// Function handleBoxKeyPress() is a member function to handle keypress events for checkboxes
// This function is needed to consume events for browsers, such as Opera, that perform window
// manipulation on keypress events.
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the keydown event
//
// @return (boolean) Returns false if processing; true of doing nothing
//
checkboxGroup.prototype.handleBoxKeyPress = function($id, e) {
    
  if (e.altkey || e.ctrlKey || e.shiftKey) {
    // do nothing;
    return true;
  }

  if( e.keyCode == this.keys.space ) {
    // consume the event
    e.stopPropagation();
    return false;
  } // endif

  return true;
  
} // end handleBoxKeyPress()

//
// Function handleBoxMouseOver() is a member function to handle mouseover events for checkboxes
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the mouseover event
//
// @return (boolean) Returns false;
//
checkboxGroup.prototype.handleBoxMouseOver = function($id, e) {
    
  // if the box does not have the focus class add the hover highlight
  if ($id.not('.focus')) {
    $id.addClass('hover');
  }

  e.stopPropagation();
  return false;
  
} // end handleBoxMouseOver()

//
// Function handleBoxMouseOut() is a member function to handle mouseout events for checkboxes
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the mouseout event
//
// @return (boolean) Returns false;
//
checkboxGroup.prototype.handleBoxMouseOut = function($id, e) {
    
  $id.removeClass('hover');

  e.stopPropagation();
  return false;
  
} // end handleBoxMouseOut()

//
// Function handleBoxFocus() is a member function to handle focus events for checkboxes
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the focus event
//
// @return (boolean) Returns true;
//
checkboxGroup.prototype.handleBoxFocus = function($id, e) {
    
  $id.addClass('focus');

  // remove the hover class if it is applied
  $id.removeClass('hover');

  return true;
  
} // end handleBoxFocus()

//
// Function handleBoxBlur() is a member function to handle blur events for checkboxes
//
// @param ($id object) $id is the jquery object of the checkbox
//
// @param (e object) e is the event object associated with the blur event
//
// @return (boolean) Returns true;
//
checkboxGroup.prototype.handleBoxBlur = function($id, e) {
    
  $id.removeClass('focus');
  return true;
  
} // end handleBoxBlur()