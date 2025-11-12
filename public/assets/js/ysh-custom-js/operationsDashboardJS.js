



let deviceSelected = 0;
let selectedCases = [];
let currentModalId = 0;
let caseIDFromOldDialog = 0;

// Function to handle outer tab (stage) switching
function setOuterTab(btnElement) {
    const id = btnElement.id;

    // Save active outer tab to cookie
    Cookies.set('activeOuterTab', id);
    console.log("Set outer tab to:", id);

    // Hide all outer tab panels
    $('.macaw-aurora-tabs > div[role="tabpanel"]').attr('hidden', true);

    // Show the selected panel
    const panelId = $(btnElement).attr('aria-controls');
    $(`#${panelId}`).removeAttr('hidden');

    // Update tab button states
    const tablist = $(btnElement).closest('[role="tablist"]');
    tablist.find('[role="tab"]').attr('aria-selected', false).attr('tabindex', -1);
    $(btnElement).attr('aria-selected', true).removeAttr('tabindex');

    // Set current stage for global use
    const stageMapping = {
        'design': 1,
        'milling': 2,
        '3dprinting': 3,
        'sintering': 4,
        'pressing': 5,
        'finishing': 6,
        'qc': 7,
        'delivery': 8
    };

    window.currentStage = stageMapping[id] || 1;
    console.log("Current stage set to:", window.currentStage);
}

function setInnerTab(btnElement) {
    let id = btnElement.id;
    if (id.toLowerCase().includes('3dprinting')) {
        id = id.replace(/3[dD][pP]rinting/i, '3dprinting');
    }

    const stageKey = $(btnElement).data('stageid');
    const stageMapping = {
        'design': 1,
        'milling': 2,
        '3dprinting': 3,
        'sintering': 4,
        'pressing': 5,
        'finishing': 6,
        'qc': 7,
        'delivery': 8
    };

    const stageNumber = stageMapping[stageKey];
    if (stageNumber) {
        Cookies.set('inner' + stageNumber, id);
        console.log("set cookie for stage " + stageNumber + " => " + id);
    }

    console.log("stageKey:", stageKey);

    // Hide all inner tab panels for this stage
    console.log("Hiding: #active-" + stageKey + ", #waiting-" + stageKey);
    $(`#active-${stageKey}, #waiting-${stageKey}`).attr('hidden', true).removeClass('active');

    // Show the selected panel
    const panelId = $(btnElement).attr('aria-controls');
    console.log("Showing panel:", panelId);
    $(`#${panelId}`).removeAttr('hidden').addClass('active');

    // Update tab button states
    const tablist = $(btnElement).closest('[role="tablist"]');
    tablist.find('[role="tab"]').attr('aria-selected', false).attr('tabindex', -1);
    $(btnElement).attr('aria-selected', true).removeAttr('tabindex');

    // Initialize DataTables for the newly visible panel

}

// setOuterTab function is now defined in the main dashboard view file

// Handle dialog backdrop click for dismissal
function handleDialogBackdropClick(event, deviceId) {
    // Only close if clicking on the backdrop itself, not child elements
    if (event.target === event.currentTarget) {
        closeDeviceDialog(deviceId);
    }
}

// Handle waiting dialog backdrop click for dismissal
function handleWaitingDialogBackdropClick(event, type) {
    // Only close if clicking on the backdrop itself, not child elements
    if (event.target === event.currentTarget) {
        closeModal({id: type, isWaiting: true});
    }
}

// Function to handle radio button changes
function buildRadioChange(radio, type, deviceId) {
    type = CSS.escape(type);

    // Enable the button when a radio is selected
    // document.querySelector(`.${type}.blackbox-button-outer.device-${deviceId}`).classList.remove('disabled');
    document.querySelector(`.${type}.blackbox-button-outer.device-${deviceId}`).classList.add('enabled');

    // document.querySelector(`.${type}.blackbox-button-inner.device-${deviceId}`).classList.remove('disabled');
    document.querySelector(`.${type}.blackbox-button-inner.device-${deviceId}`).classList.add('enabled');
}


function toggleButtonState() {
    const checkboxes = document.querySelectorAll('.active-checkbox');
    const buttons = document.querySelectorAll('.active-cases-btn');

    // Check if any checkbox is checked
    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    buttons.forEach(button => {
        const components = button.children; // Assuming button has 3 components inside

        for (let component of components) {
            if (isChecked) {
                component.classList.add('enabled');
                component.classList.remove('disabled');
            } else {
                component.classList.add('disabled');
                component.classList.remove('enabled');
            }
        }
    });
}

function getCheckedValues(tableKey) {
    var checkedValues = [];
    // First check if we have a case from a single case dialog
    if (caseIDFromOldDialog != 0) return [caseIDFromOldDialog];

    console.log("Checking for checkboxes with selector: " + 'input[name="CheckBoxes' + tableKey + '[]"]:checked');
    // Select checkboxes with the specific dynamic name
    $('input[name="CheckBoxes' + tableKey + '[]"]:checked').each(function () {
        checkedValues.push($(this).val());
    });
    if (checkedValues.length == 0) {
        console.log("No checkboxes checked with selector: " + 'input[name="CheckBoxes' + tableKey + '[]"]:checked');
        console.log("Trying alternative selector with formatted id: " + 'input[name="CheckBoxes' + formatId(
            tableKey) + '[]"]:checked');
        $('input[name="CheckBoxes' + formatId(tableKey) + '[]"]:checked').each(function () {
            checkedValues.push($(this).val());
        });
    }

    // If still no checkboxes found, check the global case ID from waiting dialog
    if (checkedValues.length == 0 && window.caseIDFromOldDialog && window.caseIDFromOldDialog !== 0) {
        checkedValues = [window.caseIDFromOldDialog];
        console.log("Using case from waiting dialog:", window.caseIDFromOldDialog);
    }

    console.log("Checked Count for", tableKey, ":", checkedValues.length); // Debugging
    console.log("Checked Values:", checkedValues); // Debugging

    return checkedValues;
}

function submitForm(key, action, deviceId = 0) {
    console.log("WARNING: submitForm called for " + key + " - this is deprecated. Please use submitWorkflow instead.");

    if (!deviceSelected) //if  0 (false)  = >  true => assign
        deviceSelected = deviceId;


    console.log("submitting  " + key + " action" + action + " deviceId" + deviceId);
    var checkedValues = getCheckedValues(key); // Get checked values for the correct table
    var form = document.getElementById("hiddenForm" + key);

    console.log("check boxes selector: " + "CheckBoxes" + key);
    // Select the correct hidden inputs based on the form's key
    try {
        document.getElementById("WaitingPopupCheckBoxes" + key).value = checkedValues;
    } catch (e) {
        try {
            key = formatId(key);
            document.getElementById("WaitingPopupCheckBoxes" + key).value = checkedValues;
        } catch (e2) {
            console.error("Both elements not found:", e, e2);
        }
    }

    console.log("WaitingPopupCheckBoxes : " + document.getElementById("WaitingPopupCheckBoxes" + key).value);
    if (action == 4)
        document.getElementById("hidden3dprintingBuildName").value = document.getElementById("silicon-valley-input")
            .value;

    document.getElementById("deviceId-" + key).value = deviceSelected
    console.log(checkedValues.length + " === caseIDFromOldDialog:" + caseIDFromOldDialog + "    =xx== " +
        deviceSelected); // Debugging log

    form.action =
        action == 0 ? routes.setMultiple :
            action == 1 ? routes.activateMultiple :
                action == 2 ? routes.finishMultiple :
                    action == 3 ? routes.assignDelivery :
                        action == 4 ? "/set-cases-on-printer" :
                            action == 5 ? routes.activate3D :
                                action == 6 ? routes.finish3D :
                                    routes.finishMultiple;


    console.log("form action: " + form.action);

    document.getElementById("hiddenForm" + key).submit(); // Submit the correct form

}


function formatId(id) {
    // Normalize for 3D printing first
    if (id && id.toLowerCase().includes('3dprinting')) {
        return "3dprinting";
    }
    // Check if id (in lowercase) starts with "3d"
    else if (id && id.toLowerCase().startsWith("3d")) {
        // Preserve "3D" then capitalize the first character of the rest of the string.
        return "3D" + id.slice(2).charAt(0).toUpperCase() + id.slice(3);
    } else {
        // Default: Capitalize the first character.
        return id.charAt(0).toUpperCase() + id.slice(1);
    }
}


function casesDialogCheckBoxChange(stage, deviceId) {

    console.log("casesDialogCheckBoxChange : " + stage + ", Device Id :" + deviceId);
    console.log("Global Id: " + deviceSelected);
    console.log("Selector" + deviceSelected);

    // Escape class names that start with a number by using attribute selector
    const classSelector = `.${CSS.escape(stage)}.active-checkbox`;

    // Check if any checkbox with the specified class is checked
    if ($(classSelector + ":checked").length > 0) {

        $(`.activeSubmitBtn-${deviceId}`).prop('disabled', false);
        $(`.${CSS.escape(stage)}.blackbox-button-outer.device-${deviceId}`).removeClass('disabled').addClass(
            'enabled');
        $(`.${stage}.blackbox-button-inner.device-` + deviceId).removeClass('disabled').addClass('enabled');
        // Perform action when at least one checkbox is checked
    } else {
        console.log("A-Disable Btn Selector : " + `.${CSS.escape(key)}.blackbox-button-outer`);
        $(`.activeSubmitBtn-${deviceId}`).prop('disabled', true);
        $(`.${stage}.blackbox-button-outer`).removeClass('enabled').addClass('disabled');
        $(`.${stage}.blackbox-button-inner`).removeClass('enabled').addClass('disabled');
    }
}


//4.16.2025.9AM FML
// Bind delegated click event to detect clicks on disabled 3DPrinting checkboxes.
$(document).on('click', 'multipleCB.3dprinting', function (e) {
    caseIDFromOldDialog = 0;


    if ($(this).prop('disabled')) {
        e.preventDefault(); // Prevent any default action.
        showToast("Case already selected");
    }
});

function showToast(message, type = 'error') {
    // Remove any existing toasts first
    $('.toast-alert').remove();

    var toast = $('<div class="toast-alert ' + type + '"><span>' + message + '</span></div>');
    $('body').append(toast);

    // Show toast with smooth animation
    toast.css({
        opacity: 0,
        top: '-60px',
        transform: 'translateX(-50%) translateY(-10px)'
    }).animate({
        top: '20px',
        opacity: 1
    }, 400).delay(4000).animate({
        top: '-60px',
        opacity: 0
    }, 400, function () {
        $(this).remove();
    });
}

function showNoJobsMessage() {
    showToast("This machine doesn't have any builds and is currently turned off");
}

function multiCBChanged(groupKey, changedCheckbox) {
    // This part of the function handles the show/hide behavior for .receiveSelectBtn based on overall selection.
    var checkedCount = $(`.multipleCB.${groupKey}:checkbox:checked`).length;

    console.log(`multiCBChanged called for ${groupKey}, checked count: ${checkedCount}`);

    if (checkedCount > 0) {
        // Show the SET button for this specific stage
        const setButton = $(`.receiveSelectBtn.${groupKey}`);

        if (!setButton.is(":visible")) {
            console.log(`Showing SET button for ${groupKey}`);
            setButton.css({
                "opacity": "0",
                "display": "flex"
            }).show().animate({
                opacity: 1
            }, 300);
        }
    } else {
        // Hide the SET button for this specific stage
        console.log(`Hiding SET button for ${groupKey}`);
        $(`.receiveSelectBtn.${groupKey}`).stop(true, true).animate({
            opacity: 0
        }, 300, function () {
            $(this).css({
                "display": "none"
            });
        });
    }
}


function selectAll(ele, classname) {
    console.log(`selectAll called for ${classname}, checked: ${$(ele).prop('checked')}`);

    if ($(ele).prop('checked')) {
        $('.multipleCB.' + classname).prop('checked', true);
    } else {
        $('.multipleCB.' + classname).prop('checked', false);
    }

    // Always call multiCBChanged to update the SET button state
    multiCBChanged(classname);
}



// Function to initialize DataTables safely
var dataTableInitialized = false;

function initializeDataTables() {
    // Prevent multiple initialization attempts
    if (dataTableInitialized) {
        return;
    }

    // Check if jQuery is available first
    if (typeof $ === 'undefined') {
        console.warn("jQuery is not loaded. Cannot initialize DataTables.");
        return;
    }

    // Check if DataTables is available before trying to initialize
    if (typeof $.fn.DataTable !== 'function') {
        console.warn("DataTables plugin is not loaded. Tables will display without DataTable functionality.");
        return;
    }

    // Mark as initialized to prevent duplicate attempts
    dataTableInitialized = true;

    // Use the optimized function
    initializeVisibleTables();

}

// Optimized initialization with caching
var initializedTables = new Set();

function initializeVisibleTables() {
    // ✅ Check if DataTables is available
    if (typeof $.fn.DataTable === 'undefined') {
        console.log('DataTables not yet loaded, retrying...');
        setTimeout(initializeVisibleTables, 100); // retry after 100ms
        return;
    }

    // 🗑️ This does nothing (should be removed)
    // setTimeout(function(){}, 1000);

    var tables = $('.sunriseTable');

    tables.each(function() {
        var tableId = this.id || $(this).index();

        if (initializedTables.has(tableId)) return;

        var $table = $(this);
        var $parent = $table.closest('[role="tabpanel"]');

        if ($table.is(':visible') || ($parent.length && !$parent.attr('hidden'))) {
            if (!$.fn.DataTable.isDataTable(this)) {
                initializeSingleTable(this);
                initializedTables.add(tableId);
            }
        }
    });
}


// Initialize a single table
function initializeSingleTable(table) {
    // Check if DataTables is available
    if (typeof $.fn.DataTable === 'undefined') {
        console.log('DataTables not available for single table initialization');
        return;
    }

    const $table = $(table);

    // Cache DOM elements
    const $thead = $table.find('thead');
    const $tbody = $table.find('tbody');

    // Check table structure
    if ($thead.length === 0 || $tbody.length === 0) {
        return;
    }

    try {
        // Cache header row
        const $headerRow = $thead.find('tr').first();
        const $headerCells = $headerRow.find('th');

        // BOOLEAN: Check if first column has checkbox
        let hasCheckbox = $headerCells.first().find('input[type="checkbox"]').length > 0;

        // INTEGER: Count total columns
        let columnCount = $headerCells.length;

        // Lightweight column configuration
        let columnDefs = [];

        // Simple width configuration based on checkbox and column count
        if (hasCheckbox && columnCount === 7) {
            columnDefs = [
                { width: "5%", targets: 0},
                { width: "20%", targets: 1 },
                { width: "20%", targets: 2 },
                {className: 'dt-center', width: "20%", targets: 3 },
                {className: 'dt-center', width: "10%", targets: 4 },
                {className: 'dt-center', width: "15%", targets: 5 },
                {className: 'dt-center', width: "10%", targets: 6 }
            ];
        } else if (hasCheckbox && columnCount === 6) {
            columnDefs = [
                { width: "5%", targets: 0 },// checkbox
                { width: "20%", targets: 1, },
                { width: "20%", targets: 2 },
                { className: 'dt-center',width: "20%", targets: 3 },
                { className: 'dt-center',width: "10%", targets: 4 },
                { className: 'dt-center', width: "25%", targets: 5 },

            ];
        } else if (!hasCheckbox && columnCount === 6) {
            columnDefs = [
                { width: "30%", targets: 0 },
                { width: "20%", targets: 1 },
                {className: 'dt-center', width: "20%", targets: 2 },
                { className: 'dt-center',width: "10%", targets: 3 },
                {className: 'dt-center', width: "10%", targets: 4 },
                {className: 'dt-center', width: "10%", targets: 5 }
            ];
        } else if (!hasCheckbox && columnCount === 5) {
            columnDefs = [
                { width: "25%", targets: 0 },// doctor
                { width: "20%", targets: 1 },// patient
                {className: 'dt-center', width: "20%", targets: 2 },//deli
                {className: 'dt-center', width: "10%", targets: 3 },// # of units
                {className: 'dt-center', width: "25%", targets: 4 } // tags
            ];
        } else {
            // Default configuration
            for (let i = 0; i < columnCount; i++) {
                let config = { width: (100 / columnCount) + "%", targets: i };
                if (hasCheckbox && i === 0) {
                    config.width = "50px";
                    config.orderable = true;
                }
                columnDefs.push(config);
            }
        }

        // Lightweight DataTable initialization
        var dataTable = $table.DataTable({
            searching: false,
            ordering: false,
            autoWidth: false,
            responsive: true,
            lengthChange: false,
//             stateSave: true,
            pageLength: 10,
            pagingType: "simple_numbers",
            // processing: false,
            //    deferRender: true,
            columnDefs: columnDefs,
            language: {
                emptyTable: "No data available"
            },
            drawCallback: function() {
                // Ensure pagination is visible after drawing
                $(this).closest('.dataTables_wrapper').find('.dataTables_paginate, .dataTables_info').css('visibility', 'visible');
            }
  //           lengthMenu: [[25, 50, 100], [25, 50, 100]]
        });

        $table.addClass("nowrap hover compact stripe");

        // Adjust column widths after initialization
        setTimeout(function() {
            dataTable.columns.adjust().draw(false);
        }, 100);

    } catch (error) {
        console.error("Error initializing DataTable:", error);
    }
}

// Single efficient initialization approach
$(document).ready(function () {
    // Wait for tabs to be visible before initializing DataTables
    setTimeout(function() {
        initializeVisibleTables();

        // Re-initialize when tab changes
        $('[role="tab"]').on('click', function() {
            setTimeout(function() {
                initializeVisibleTables();
            }, 150);
        });
    }, 600);
});

// Export function globally so it can be called from blade templates
window.initializeVisibleTables = initializeVisibleTables;
window.initializeSingleTable = initializeSingleTable;

// ESC key handler for dialog dismissal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Find any open dialogs and close them
        const openDialogs = document.querySelectorAll('.sigma-workflow-modal.active');
        openDialogs.forEach(dialog => {
            const deviceId = dialog.id.replace('casesListDialog', '');
            closeDeviceDialog(deviceId);
        });
    }
});

// Main Tabs
try {
    $(".macaw-aurora-tabs").macawTabs({
        autoVerticalOrientation: true,
        tabPanelTransitionLogic: true,
        tabPanelTransitionTimeoutDuration: 10
    });

    // Nested Tabs
    $(".macaw-silk-tabs").macawTabs({
        autoVerticalOrientation: false,
    });


} catch (e) {
    console.error("Error initializing tabs:", e);
}

// Original document ready code continues below...

toggleButtonState();

tabcontent = document.getElementsByClassName("tabcontent");
for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
}

// Enhanced tab initialization - automatically opens first available tab
var activeOuter = Cookies.get("activeOuterTab");
var isFirstLoad = false;

// Get all available tabs in order
var availableTabs = $('[role="tab"][aria-controls$="label"]');
var firstAvailableTab = availableTabs.first();

// Set default if no cookie exists or if value is invalid
if (!activeOuter || $('#' + activeOuter).length === 0) {
    // Default to first available stage
    activeOuter = firstAvailableTab.attr('id') || 'design';
    isFirstLoad = true;
    console.log("Using default tab (first available):", activeOuter);
}

// Always use lowercase for 3dprinting
if (activeOuter && activeOuter.toLowerCase().includes('3dprinting')) {
    activeOuter = '3dprinting';
}

console.log("Activating outer tab:", activeOuter);

var btn = $('#' + activeOuter);
if (btn.length) {
    btn.attr('aria-selected', true);
    btn.removeAttr('tabindex');

    var tabPanel = $('#' + activeOuter + "label");
    if (tabPanel.length) {
        tabPanel.addClass('active');
        tabPanel.removeAttr('hidden');
    } else {
        console.log("Tab panel not found:", activeOuter + "label");
    }

    // Save the selected outer tab to cookies
    Cookies.set('activeOuterTab', activeOuter);
    console.log("Saved outer tab to cookie:", activeOuter);
} else {
    console.log("Tab button not found:", activeOuter);
}

// Enhanced inner tab initialization
var stageMapping = {
    'design': 1,
    'milling': 2,
    '3dprinting': 3,
    'sintering': 4,
    'pressing': 5,
    'finishing': 6,
    'qc': 7,
    'delivery': 8
};

// activate multiple inner tabs =>
for (let i = 1; i < 11; i++) {
    var activeInnerTab = Cookies.get('inner' + i);
    var stageKey = Object.keys(stageMapping).find(key => stageMapping[key] === i);

    // Enhanced default logic: if no cookie exists or this is the first available tab, default to waiting
    if (activeInnerTab == undefined || (isFirstLoad && stageKey === activeOuter)) {
        if (stageKey) {
            // Default to waiting tab for first load or if no cookie
            activeInnerTab = 'waiting-' + stageKey + 'label';
            console.log("Defaulting to waiting tab for stage:", stageKey);
        } else {
            activeInnerTab = 'active-design';
        }
    }

    // Normalize ID for 3D printing
    if (activeInnerTab && activeInnerTab.toLowerCase().includes('3dprinting')) {
        activeInnerTab = activeInnerTab.replace(/3[dD][pP]rinting/i, '3dprinting');
    }

    var innerTabBtn = $("[id='" + activeInnerTab + "']");
    var innerTab = $("[aria-labelledby='" + activeInnerTab + "']");

    if (innerTabBtn.length && innerTab.length) {
        innerTab.addClass('active');
        innerTab.removeAttr('hidden');
        innerTabBtn.attr('aria-selected', true);
        innerTabBtn.removeAttr('tabindex');

        // Save the inner tab selection to cookies
        if (stageKey) {
            Cookies.set('inner' + i, activeInnerTab);
            console.log("Saved inner tab to cookie for stage", i, ":", activeInnerTab);
        }
    }
}


$("[id^='active']").click(function (e) {
    // Store just the ID value (not prefixing with 'inner')
    Cookies.set('inner' + $(this).attr('href'), $(this).attr('id'));
    console.log("set cookie for : " + 'inner' + $(this).attr('href') + ' => ' + $(this).attr('id'));
});



let escapedKey = "NotAvailable";
$(document).ready(function () {
    enableAllChoices();
    disableTextInput();

    // Make the dialog responsive on window resize
    $(window).resize(function () {
        adjustDialogLayout();
    });

    // Initial layout adjustment
    adjustDialogLayout();
});

function adjustDialogLayout() {
    const dialogWidth = $('.blackbox-dialog').width();
    const deviceContainer = $('.device-container');

    // Adjust container layout based on screen width
    if (dialogWidth < 500) {
        deviceContainer.addClass('small-screen');
    } else {
        deviceContainer.removeClass('small-screen');
    }
}

//
// function capFirstLetter(str) {
//     return str === "3Dprinting" ? "3DPrinting" : str.charAt(0).toUpperCase() + str.slice(1);
// }

function selectOption(element, key, deviceId) {
    // key is stage (p)
    // deviceId is printer/milling id
    //device selected is global variable
    deviceSelected = deviceId;
    key = key.toLowerCase();

    let escapedKey = CSS.escape(key);


    //------------------------------------------
    //-----  PRINTERS --------------------------
    //------------------------------------------
    if (fuzzyMatch(key, "3dprinting")) {
        let numberOfEnabledPrinters = document.querySelectorAll(
            `.silicon-valley-choice.silicon-valley-enabled.${escapedKey}`).length;
        console.log("numberOfEnabledPrinters : " + numberOfEnabledPrinters);
        if (numberOfEnabledPrinters > 0) {
            // Clicked printer is already selected, if 2 or more enabled, dont turn it off (disable)
            if (element.classList.contains('silicon-valley-enabled')) {
                numberOfEnabledPrinters > 1 ? disableAllButChosen(element, deviceId) : disableAllChoicesOf(key);
                numberOfEnabledPrinters > 1 ? enableTextInput() : disableTextInput();
                //3D printing only
                disableButton(key, deviceId, true);

                console.log("element already selected, toggling..");
            } else {
                // Clicked a disabled printer

                disableAllButChosen(element, deviceId);

                console.log("at least 1 printer selected");
            }
        } else {
            console.log("No printer selected key is " + key);
            disableAllButChosen(element, deviceId);
            enableTextInput();
            disableButton(key, deviceId, true);
        }
    }
        //------------------------------------------
        //-----  NOT PRINTERS --------------------------
    //------------------------------------------
    else {
        // Original behavior for other types
        const allChoices = document.querySelectorAll('.silicon-valley-choice.' + CSS.escape(key));
        // true if EVERY element has the 'silicon-valley-enabled' class
        const allEnabled = [...allChoices].every(choice => choice.classList.contains('silicon-valley-enabled'));

        console.log("All choices : " + allChoices.length + " all enabled : " + allEnabled + "key: " + CSS.escape(
            key));
        if (allEnabled && allChoices.length != 1) {
            console.log("all choices have class silicon-valley-enabled");
            disableAllButChosen(element, key);

        } else if (element.classList.contains('silicon-valley-enabled')) {
            console.log("element has class silicon-valley-enabled");
            // Special case for single choice - don't disable it on second click, just add animation
            if (allChoices.length === 1) {
                // Add a pulse animation class
                element.classList.add('pulse-animation');
                // Remove the animation class after it completes
                setTimeout(() => {
                    element.classList.remove('pulse-animation');
                }, 800);
            } else {
                disableAllChoicesOf(CSS.escape(key));
            }
        } else {
            console.log("element does not have class silicon-valley-enabled");
            disableAllChoicesOf(CSS.escape(key));
            element.classList.add('silicon-valley-enabled');
            enableButton(CSS.escape(key));
        }


        // true if at least one element has the 'silicon-valley-enabled' class
        const isAnyChoiceEnabled = [...allChoices].some(choice => choice.classList.contains(
            'silicon-valley-enabled'));
        if (isAnyChoiceEnabled) {
            console.log("Some Choices is enabled");
            enableButton(CSS.escape(key), deviceId);

        } else {
            console.log(" No Choice is enabled");
            disableButton(CSS.escape(key), deviceId, true);
        }
    }
    // if (element.)
}

function disableAllButChosen(element, deviceId) {

    const allChoices = document.querySelectorAll(`.bb-outer-img.waiting-popup`);
    allChoices.forEach(choice => choice.classList.remove('silicon-valley-enabled'));
    element.classList.add('silicon-valley-enabled');

    // const allChoices = document.querySelectorAll(`.silicon-valley-choice.${escapedKey}`);
    // const allEnabled = [...allChoices].every(choice => choice.classList.contains('silicon-valley-enabled'));
    // if (allEnabled) {
    //     allChoices.forEach(choice => {
    //         if (choice !== element) {
    //             choice.classList.remove('silicon-valley-enabled');
    //         }
    //     });
    // }
}

function enableAllChoices() {
    document.querySelectorAll('.silicon-valley-choice:not(.silicon-valley-enabled)').forEach(choice => {
        choice.classList.add('silicon-valley-enabled');
    });
}

function disableAllChoicesOf(key) {
    document.querySelectorAll(`.${CSS.escape(key)}.silicon-valley-choice`).forEach(choice => {
        choice.classList.remove('silicon-valley-enabled');
    });
}

function disableAllRoundedButtons() {
    document.querySelectorAll(`.blackbox-button-inner, .blackbox-button-outer`).forEach(choice => {
        choice.classList.remove('enabled', 'blue', 'orange', 'green');
    });
}

function checkInput() {
    let color = 'orange';
    const input = document.getElementById('silicon-valley-input').value;
    if (input.trim() !== '') {
        document.getElementById('blackbox-btn-outer').classList.add('enabled', color);
        document.getElementById('blackbox-btn-inner').classList.add('enabled', color);
    } else {
        document.getElementById('blackbox-btn-outer').classList.remove('enabled', color);
        document.getElementById('blackbox-btn-inner').classList.remove('enabled', color);
    }
}

/*
|--------------------------------------------------------------------------
|                          disableButton
|--------------------------------------------------------------------------
*/
function disableButton(key, deviceId = "", isWaiting = false) {


    // delivery dialog has no rounded button

        const submitButton = document.getElementById('action-button-delivery');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('btn-loading');
            submitButton.innerText = 'Processing...';
            submitButton.classList.add('disabled');

            console.log('Disabled delivery button');

            // Auto-reset after timeout (fallback) - but the dialog should reset it when reopened
            setTimeout(() => {
                console.log('Auto-resetting delivery button after timeout');
                submitButton.classList.remove('disabled', 'btn-loading');
                submitButton.disabled = true; // Keep disabled until driver selected
                submitButton.innerText = 'ASSIGN';
            }, 3000);
        }



        // active dialog
        if (deviceId !== "" && !isWaiting) {

            $(`.activeSubmitBtn-${CSS.escape(deviceId)}`).removeClass('silicon-valley-enabled enabled  ').addClass(
                'disabled'); //activeSubmitBtn
            $(`.${CSS.escape(key)}.blackbox-button-outer.device-${CSS.escape(deviceId)}`).removeClass(
                'silicon-valley-enabled enabled  ').addClass('disabled');
            $(`.${CSS.escape(key)}.blackbox-button-inner.device-${CSS.escape(deviceId)}`).removeClass(
                'silicon-valley-enabled enabled  ').addClass('disabled');
        }
        // waiting dialog
        else {
            console.log("W-Disable Btn Selector : " + `.${CSS.escape(key)}.blackbox-button-outer`);
            //.${CSS.escape(key)}

            $(`.waiting-popup.blackbox-button-inner`).removeClass('silicon-valley-enabled enabled  ').addClass(
                'disabled');
            $(`.waiting-popup.blackbox-button-outer`).removeClass('silicon-valley-enabled enabled ',).addClass(
                'disabled');


            //TODO disable btn by currentId
        }

}

/*
|--------------------------------------------------------------------------
|                          UNCHECK CHECKBOXES
|--------------------------------------------------------------------------
*/

function uncheckCheckboxes(deviceId) {
    const checkboxes = document.querySelectorAll(`.${CSS.escape(deviceId)}.active-cases-checkbox`);
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            checkbox.checked = false;
            checkbox.dispatchEvent(new Event('change', {
                bubbles: true
            }));
        }
    });
}


function disableAllButton(key) {
    document.querySelector(`.blackbox-button-outer.waiting-popup`).classList.remove('enabled');
    document.querySelector(`.blackbox-button-inner.waiting-popup`).classList.remove('enabled');
}


function enableButton(key, deviceId = "") {
    console.log("enable button key : " + key + " deviceId : " + deviceId);

    console.log("Selector: " + `.${CSS.escape(key)}.blackbox-button-outer.waiting-popup`);

    // document.querySelector(`.${CSS.escape(key)}.blackbox-button-outer.waiting-popup`)
    //     .classList.add('enabled');
    // document.querySelector(`.${CSS.escape(key)}.blackbox-button-inner.waiting-popup`)
    //     .classList.add('enabled');
    //
    try {
        $(".sigma-button." + CSS.escape(key)).removeClass('disabled');
        document.querySelector(`.${CSS.escape(key)}.blackbox-button-outer`)
            .classList.add('enabled');
        document.querySelector(`.${CSS.escape(key)}.blackbox-button-inner`)
            .classList.add('enabled');
    } catch (e) {
        $(".sigma-button").removeClass('disabled');
        document.querySelector(`.blackbox-button-outer.waiting-popup`)
            .classList.add('enabled');
        document.querySelector(`.blackbox-button-outer.waiting-popup`)
            .classList.add('enabled');
    }
}

function disableTextInput() {
    try {
        document.getElementById('silicon-valley-input').disabled = true;
    } catch (e) {
        console.log("Tried disabling text input");
    }
}

function enableTextInput() {
    try {
        document.getElementById('silicon-valley-input').disabled = false;
    } catch (e) {
        console.log("Tried enabling text input");
    }
}

function resetDialogStatus({
                               stageType1,
                               isWaiting = true,
                               deviceId = 0,
                               exactId = null
                           }) {

    if (exactId != null) {
        console.log("resetting dialog by ID: " + exactId);
        $("#" + exactId + " .bb-outer-img").removeClass('silicon-valley-enabled');
        $("#" + exactId + " #blackbox-button-outer").removeClass(
            'silicon-valley-enabled enabled  blue orange green');
        $("#" + exactId + " #blackbox-button-inner").removeClass(
            'silicon-valley-enabled enabled blue orange green',);

        uncheckCheckboxes(deviceId);
        console.log("reset done for " + exactId + " stageType1 : " + stageType1 + " deviceId : " + deviceId);
    } else {
        let classSelector =CSS.escape(stageType1)
        console.log("resetting dialog : " + stageType1);

        $(classSelector).addClass('silicon-valley-enabled');


        if ($(classSelector).length === 0) {
            console.log("No matching element found for selector: " + classSelector);
            //TODO reset by current ID
            // return;
        }
    }

    // reset all
    $(".sigma-machine-card.selected").removeClass("selected");


    disableButton(stageType1, "", isWaiting);
    clearTextInput();

    disableTextInput();
    uncheckCheckboxes(deviceId)
}

function clearTextInput() {
    $('.sigma-form-control').val('');
}

function toggleButtonStatus(key, forceState = null) {
    const outerButton = document.querySelector(`.${CSS.escape(key)}.blackbox-button-outer`);
    const innerButton = document.querySelector(`.${CSS.escape(key)}.blackbox-button-inner`);

    if (!outerButton || !innerButton) {
        console.warn(`Buttons not found for key: ${key}`);
        return;
    }

    // If forceState is provided, use it directly
    if (forceState !== null) {
        if (forceState) {
            outerButton.classList.add('enabled');
            innerButton.classList.add('enabled');
        } else {
            outerButton.classList.remove('enabled');
            innerButton.classList.remove('enabled');
        }
        return;
    }

    // Otherwise toggle based on current state
    const isEnabled = outerButton.classList.contains('enabled');
    if (isEnabled) {
        outerButton.classList.remove('enabled');
        innerButton.classList.remove('enabled');
    } else {
        outerButton.classList.add('enabled');
        innerButton.classList.add('enabled');
    }
}


function YSH_toggleBuild(event, clickedRow) {
    console.log("YSH_toggleBuild", clickedRow);

    // Collapse all other rows
    document.querySelectorAll('.YSH-build-row').forEach(row => {
        if (row !== clickedRow) {
            row.classList.remove('active');
            const body = row.querySelector('.YSH-build-body');
            if (body) {
                body.style.display = 'none';
            }
        }
    });

    // Toggle the clicked one
    const body = clickedRow.querySelector('.YSH-build-body');
    if (!body) return; // safety check

    const isVisible = body.style.display === 'block';

    if (isVisible) {
        body.style.display = 'none';
        clickedRow.classList.remove('active');
    } else {
        body.style.display = 'block';
        clickedRow.classList.add('active');
    }
}




function closeModal({id, isWaiting = false, deviceId = 0, exactId = null}) {
    console.log(`closeModal called with id: ${id}, isWaiting: ${isWaiting}, exactId: ${exactId}`);

    // If exactId is provided, use it directly, otherwise construct modal ID
    let modalId = exactId || id;
    if (isWaiting && !modalId.includes('-waiting')) {
        modalId = modalId + '-waiting';
    }

    // For waiting dialogs, try different ID patterns
    let modal = document.getElementById(modalId);

    if (!modal && id === 'WaitingDialog') {
        // Try to find any open waiting dialog
        modal = document.querySelector('.modal.show[id*="waitingDialog"]') ||
                document.querySelector('.sigma-workflow-modal.active[id*="waiting"]');
        if (modal) {
            modalId = modal.id;
            console.log(`Found waiting dialog with ID: ${modalId}`);
        }
    }

    if (modal) {
        console.log(`Closing modal: ${modalId}`);

        // Remove focus if modal contains active element
        if (modal.contains(document.activeElement)) {
            document.activeElement.blur();
        }

        // Add Animate.css fade-out animation (fadeOutUp to top) - faster
        const dialogContent = modal.querySelector('.sigma-workflow-dialog') || modal.querySelector('.modal-content');
        if (dialogContent) {
            dialogContent.classList.remove('animate__fadeInDown');
            dialogContent.classList.add('animate__fadeOutUp');
        }

        // Hide modal after animation completes (300ms for faster)
        setTimeout(() => {
            modal.classList.remove('active', 'show');
            modal.style.display = 'none';
            if (dialogContent) {
                dialogContent.classList.remove('animate__fadeOutUp', 'animate__fadeInDown', 'animate__animated', 'animate__faster');
                dialogContent.style.willChange = 'auto'; // Reset GPU optimization
            }

            // Reset dialog state if needed
            if (typeof resetDialogStatus === 'function') {
                resetDialogStatus({
                    stageType1: modalId,
                    isWaiting: isWaiting,
                    deviceId: deviceId,
                    exactId: exactId
                });
            }
        }, 300);

    } else {
        console.error(`Modal not found: ${modalId}, trying fallback cleanup`);

        // Fallback: close all active modals/dialogs
        document.querySelectorAll('.modal.show, .sigma-workflow-modal.active, [id*="waitingDialog"].show').forEach(m => {
            m.classList.remove('active', 'show');
            m.style.display = 'none';
            console.log(`Closed modal via fallback: ${m.id}`);
        });
    }

    // Clean up any remaining overlays
    document.querySelectorAll('.modal-backdrop, .modal-overlay').forEach(backdrop => {
        backdrop.remove();
    });
}

function processWorkflowAction222(deviceId, type, actionType, action) {




    console.log(`Processing action: ${action} for ${actionType}(s) on device ${deviceId}`);

    const form = document.getElementById(`process-form-${deviceId}`);
    const itemsInput = document.getElementById(`selected-items-${deviceId}`);
    const actionTypeInput = document.getElementById(`action-type-${deviceId}`);
    const stageTypeInput = document.getElementById(`stage-type-${deviceId}`);

    let selectedItems = [];

    if (actionType === 'build') {
        // Get all selected build checkboxes
        const selectedBuilds = form.querySelectorAll('input[name="buildId"]:checked');

        if (!selectedBuilds.length) {
            alert('Please select at least one build to process.');
            return;
        }

        // Add build IDs to selected items
        selectedBuilds.forEach(build => {
            selectedItems.push(build.value);
        });

        // Set stage type for the request
        if (stageTypeInput) {
            stageTypeInput.value = type;
        }

    } else { // actionType is 'jobs'
        // Get all checked job checkboxes
        const checkedCheckboxes = $(`input[type="checkbox"]:checked[class~="sigma-checkbox"][class~="${type}"]`);
        console.log( "checkedCheckboxes magic selector of 3d builds : " + checkedCheckboxes);


        if (checkedCheckboxes.length === 0) {
            alert('Please select at least one job.');
            return;
        }

        checkedCheckboxes.forEach(checkbox => {
            selectedItems.push(checkbox.value);
        });
    }

    if (selectedItems.length > 0) {
        itemsInput.value = selectedItems.join(','); // Populate the hidden input with comma-separated IDs
        actionTypeInput.value = action; // 'start' or 'complete'
        form.submit(); // Submit the form
    } else if (actionType !== 'build') { // Only show this if not in the build logic that's currently blocked
        alert('No items selected.');
    }
}

// Ensure the action button state is updated correctly on page load
// document.addEventListener('DOMContentLoaded', function() {
//    document.querySelectorAll('[id$="casesListDialog"]').forEach(dialog => {
//         const deviceId = dialog.id.replace('casesListDialog', '');
//         const type = dialog.dataset.type; // Assuming you can add data-type attribute to the dialog div
//         const isBuilds = dialog.dataset.isBuilds === 'true'; // Assuming data-is-builds attribute
//
//          // Only run for non-build dialogs initially to set correct state based on checkboxes
//       //  if (!isBuilds) {
//          //    updateActionButtonState(deviceId, type);
//      //   } else {
//             // For builds, the button starts disabled and is enabled when a radio button is clicked
//              // The click handler on the build row handles the radio button selection and enabling
//      //   }
//     });
//
//     // Reset global variables - Ensure these are not causing unintended side effects
//     // window.selectedBuildId = null; // Consider if this is necessary or causing issues
//     // selectedBuildId = null; // Consider if this is necessary or causing issues
// });

$(document).ready(function () {
    // Loop through each div with the class `sigma-workflow-modal waiting`
    $(".sigma-workflow-modal.active").each(function () {
        const modal = $(this); // The current modal
        const button = modal.find(".sigma-button"); // Find the button within this modal

        // Function to check and set button state
        function updateButtonState() {
            const isAnyChecked = modal.find("input[type='checkbox']:checked").length > 0;
            button.prop("disabled", !isAnyChecked); // Enable if at least one checkbox is checked

            console.log("isAnyChecked" + isAnyChecked);
        }
        // Attach a change event listener to checkboxes in this modal
        modal.on("input", "input[type='checkbox']", updateButtonState);

        // Trigger the function on page load to initialize the button state
        updateButtonState();
    });
});


// $(document).ready(function () {
//     console.log("Listening for visible dialogs with role='dialog'");
//
//     // Use MutationObserver to detect changes in dialog visibility
//     const observer = new MutationObserver(function () {
//         $(".sigma-workflow-modal[role='dialog']").each(function () {
//             const modal = $(this);
//
//             // Check if the modal is visible (display is 'flex')
//             const isVisible =
//                 modal.css("display") === "flex" && modal.is(":visible");
//
//             if (isVisible) {
//                 dialogOnScreen = modal; // Save the modal element in the global variable
//                 console.log("Dialog is now visible and stored in dialogOnScreen:", dialogOnScreen);
//             }
//         });
//     });
//
//     // Observe the entire document for changes in child elements and their attributes
//     observer.observe(document.body, { attributes: true, childList: true, subtree: true });
// });


// Add a new function to update the action button state for non-builds
// function updateActionButtonState(deviceId, type) {
//     const form = document.getElementById(`process-form-${deviceId}`);
//     const actionButton = document.getElementById(`actionXX-button-${deviceId}`);
//    console.log("updateActionButtonState = " + actionButton);
//    form.querySelectorAll("checkbox:checked")
//
//     const checkedCheckboxes = document.querySelectorAll(`input.sigma-checkbox.${CSS.escape(type)}[type="checkbox"]:checked`);
//     console.log( "checkbox change = " + checkedCheckboxes);
//     if (actionButton) {
//          // Enable the button if any checkbox is checked
//         actionButton.disabled = checkedCheckboxes.length === 0;
//
//         // Update button text and color based on whether active jobs exist
//
//         // A more reliable way to check for active jobs in the *current* dialog:
//         const currentDialog = document.getElementById(`${deviceId}casesListDialog`);
//          const activeJobRowsInDialog = currentDialog.querySelectorAll('.sigma-job-row[style*="--main-blue"]'); // Find rows with the active color
//         const hasActiveJobs = activeJobRowsInDialog.length > 0; // This check might need to be specific to the current dialog
//
//
//         const hasActiveJobsInDialog = activeJobRowsInDialog.length > 0;
//
//         // Special handling for inactive jobs (orange rows) - they are disabled if active jobs exist
//         const inactiveCheckboxes = currentDialog.querySelectorAll('.sigma-job-row[style*="--main-orange"] input[name="jobId[]"]');
//         if (hasActiveJobsInDialog) {
//             inactiveCheckboxes.forEach(checkbox => checkbox.disabled = true);
//             console.log("found inactiveCheckboxes = " + inactiveCheckboxes.length);
//         } else {
//              inactiveCheckboxes.forEach(checkbox => checkbox.disabled = false);
//             console.log("no inactiveCheckboxes = " + inactiveCheckboxes.length);
//         }
//
//
//     } else {
//         console.error(`Action button for device ${deviceId} not found.`);
//     }
// }


// Function to enable the action button specifically for build selection
function enableActionButton(deviceId, type) {
    console.log(`enableActionButton called for deviceId: ${deviceId}, type: ${type}`);
    const actionButton = document.getElementById(`actionX-button-${deviceId}`);
    if (actionButton) {
        console.log(`Action button found:`, actionButton);
        actionButton.disabled = false;
        // For builds, the button text/color is determined by whether the build has started
        // This logic is handled in the Blade template based on $data['build']->started_at
    } else {
        console.error(`Action button for device ${deviceId} not found in enableActionButton.`);
    }
}

// Toggle build details visibility


function showNoJobsMessage() {
    // You can implement a small toast or modal here
    console.log("No jobs available for this device.");
    // alert("No jobs available for this device."); // Example
}

// Also try when window is fully loaded (fallback for DataTables)
$(window).on('load', function() {
    // Try one more time when window is fully loaded
    if (!dataTableInitialized) {
        initializeDataTables();
    }
    // Reset global variables - Ensure these are not causing unintended side effects
    // window.selectedBuildId = null; // Consider if this is necessary or causing issues
    // selectedBuildId = null; // Consider if this is necessary or causing issues
});
