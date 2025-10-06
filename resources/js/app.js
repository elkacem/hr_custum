import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

// Month select plugin
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect/index.js";
import "flatpickr/dist/plugins/monthSelect/style.css";

document.addEventListener("DOMContentLoaded", () => {
    // Engagement Date & Facture Date → full calendar with time
    flatpickr("input[name='engagement_date'], input[name='date_facture']", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        defaultDate: new Date(),   // <-- sets current date + time as default
        allowInput: true   // <-- optional, for typing months

    });

    // Periode → month/year selector
    flatpickr("input[name='periode']", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,       // display like Jan, Feb...
                dateFormat: "Y-m",     // stored in DB as 2025-09
                altFormat: "F Y"       // displayed as September 2025
            })
        ],
        defaultDate: new Date(),   // sets current month by default
        allowInput: true   // <-- optional, for typing months

    });
});
