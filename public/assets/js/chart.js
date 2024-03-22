// setup
const colours = ["hsla(159, 90%, 40%, 0.5)", "hsla(18, 99%, 70%, 0.5)"];
const data = {
    labels: [
        'Asphodele',
        'Datura',
        'Cerisier',
        'Kiwi',
        'Romarin',
        'Eucalyptus',
        'Arbousier',
        'Laurier',
        'Olivier',
        'Rosier',
        'Ciste',
        'Myrte',
        'Lentisque',
        'Amandier',
        'Figuier',
        'Citronnier',
        'Rosepompon',
        'Lila'
    ],
    datasets: [
        {
            data: datas,
            backgroundColor: (ctx) => {
                if (ctx.raw.booking === 1) {
                    return colours[0];
                } else if (ctx.raw.booking === 2) {
                    return colours[1];
                }
            },
            borderColor: ["hsla(218, 23%, 33%, 1)"],
            borderWidth: 1.5,
            borderSkipped: false,
            borderRadius: Number.MAX_VALUE,
            barPercentage: 1,

        },
    ],
};
//todayLine

const todayLine = {
    id: "todayLine",
    afterDatasetsDraw(chart, args, pluginOptions) {
        const {
            ctx,
            data,
            chartArea: { top, bottom, left, right },
            scales: { x, y },
        } = chart;
        ctx.save();
        if (x.getPixelForValue(new Date()) >= left && x.getPixelForValue(new Date()) <= right) {



            ctx.beginPath();
            ctx.lineWidth = 3;
            ctx.strokeStyle = "hsla(218, 23%, 33%, 1)";
            ctx.setLineDash([6, 6]);
            ctx.moveTo(x.getPixelForValue(new Date()), top);
            ctx.lineTo(x.getPixelForValue(new Date()), bottom);
            ctx.stroke();
            ctx.restore();
            ctx.setLineDash([]);

            ctx.beginPath();
            ctx.lineWidth = 2;
            ctx.strokeStyle = "hsla(218, 23%, 33%, 1)";
            ctx.fillStyle = "hsla(218, 23%, 33%, 1)";

            ctx.moveTo(x.getPixelForValue(new Date()), top + 2);
            ctx.lineTo(x.getPixelForValue(new Date()) - 4, top - 6);
            ctx.lineTo(x.getPixelForValue(new Date()) + 4, top - 6);
            ctx.closePath();
            ctx.stroke();
            ctx.fill();
            ctx.restore();
        }
    },
};
//satuday plugin
const saturday = {
    id: 'saturday',
    beforeDatasetsDraw(chart, args, pluginOptions) {
        const {
            ctx,
            data,
            chartArea: { top, bottom, left, right, width, height },
            scales: { x, y },
        } = chart;
        ctx.save();

        x.ticks.forEach((tick, index) => {
            const day = new Date(tick.value).getDay();
            if (day === 6) {
                ctx.fillStyle = 'hsla(218, 23%, 33%, 0.2)';
                ctx.fillRect(
                    x.getPixelForValue(new Date(tick.value).setHours(-12)),
                    top,
                    x.getPixelForValue(new Date(tick.value).setHours(24)) - x.getPixelForValue(tick.value),
                    height
                )
            }
        });


    }
}

// config
const config = {
    type: "bar",
    data,
    plugins: [todayLine, saturday, ChartDataLabels],
    options: {
        onClick:(event, elements)=>{
            if(elements[0] !== undefined) {
                id = elements[0].element.$context.raw.id
               window.location.href = `update.php?id=${id}`;
            }
          },
        layout: {
            padding: {
                bottom: 15,
            },
        },
        indexAxis: "y",
        scales: {
            x: {
                position: "top",
                type: "time",
                time: {
                    unit: "day",
                    displayFormats: {
                        day: "d",
                    },
                },
                min: "2024-01-01",
                max: "2024-01-31",
            },
        },
        plugins: {
            saturday: {
                saturday
            },
            datalabels: {
                color:"hsla(218, 23%, 33%, 1)",
                formatter: function (value, context) {

                    return context.chart.data.datasets[0].data[context.dataIndex].nom;
                },

            },
            legend: {
                display: false,
            },
            tooltip: {
                displayColors: false,
                yAlign: 'center',
                xAlign: 'center',
                callbacks: {
                    label: (ctx) => {
                        return ''
                    },
                    title: (ctx) => {
                        const startDate = new Date(ctx[0].raw.x[0]);
                        const endDate = new Date(ctx[0].raw.x[1]);
                        const name = ctx[0].raw.nom;
                        const house = ctx[0].raw.y;
                        const formatedSartDate = startDate.toLocaleString([], {
                            year: "numeric",
                            month: "short",
                            day: "numeric",
                        });
                        const formatedEndDate = endDate.toLocaleString([], {
                            year: "numeric",
                            month: "short",
                            day: "numeric",
                        });
                        return [
                            `${name}`,
                            `${house}`,
                            `${formatedSartDate}  --  ${formatedEndDate}`,
                        ];
                    },
                },
            },
        },
    },
};

// render init block
const myChart = new Chart(document.getElementById("myChart"), config);

// Instantly assign Chart.js version
/* const chartVersion = document.getElementById("chartVersion");
chartVersion.innerText = Chart.version;
 */
// ACTION CHART
const chartContainer = document.getElementById("myChart")
var touchstartX = 0;
var touchendX = 0;
const sensitivity = 300; // Réglez cette valeur selon la sensibilité souhaitée, en pixels

chartContainer.addEventListener('touchstart', function (event) {
    touchstartX = event.targetTouches[0].clientX;
}, false);

chartContainer.addEventListener('touchend', function (event) {
    touchendX = event.changedTouches[0].clientX;
    handleGesture(event);
}, false);

function handleGesture() {
    // Calculer la distance parcourue par le balayage
    var distance = Math.abs(touchendX - touchstartX);
    if (distance >= sensitivity) { // Vérifier si la distance est supérieure à la sensibilité définie
        if (touchendX < touchstartX) {
            next();
        }
        if (touchendX > touchstartX) {
            prev();
        }
    }
}

function next() {
    const actualDate = myChart.config.options.scales.x.min;
    const actualYear = parseInt(actualDate.substring(0, 4));
    const actualMonth = parseInt(actualDate.substring(5, 7));
    let nextMonth
    let nextYear
    if (actualMonth === 12) {
        nextMonth = 1
        nextYear = actualYear + 1
    } else {
        nextMonth = actualMonth + 1;
        nextYear = actualYear;
    }
    const nextDate = new Date(nextYear, nextMonth - 1);
    const formattedMonth = String(nextMonth);

    const nextObj = {
        value: nextDate.getFullYear() + '-' + formattedMonth
    };
    chartFilter(nextObj)
}
function prev() {
    const actualDate = myChart.config.options.scales.x.min;
    const actualYear = parseInt(actualDate.substring(0, 4));
    const actualMonth = parseInt(actualDate.substring(5, 7));
    let prevMonth
    let prevYear
    if (actualMonth === 1) {
        prevMonth = 12
        prevYear = actualYear - 2;
    } else {
        prevMonth = actualMonth - 1;
        prevYear = actualYear;
    }
    const prevDate = new Date(prevYear, prevMonth);
    const formattedMonth = String(prevMonth);

    const prevObj = {
        value: prevDate.getFullYear() + '-' + formattedMonth
    };
    chartFilter(prevObj)

}

function chartFilter(date) {

    const year = date.value.substring(0, 4);

    const month = date.value.substring(5, 7);

    const lastDay = (y, m) => {
        return new Date(y, m, 0).getDate();
    };
    const formattedMonth = month.padStart(2, '0'); // Utilisation de padStart pour ajouter le zéro initial si nécessaire
    const startDate = `${year}-${formattedMonth}-01`;
    const endDate = `${year}-${formattedMonth}-${lastDay(year, month)}`;

    myChart.config.options.scales.x.min = startDate;
    myChart.config.options.scales.x.max = endDate;
    myChart.update();
    const calendarImput = document.getElementById('calendarImput')
    calendarImput.removeAttribute("value");
    calendarImput.setAttribute("value", `${year}-${formattedMonth}`);
}

const startDate = new Date;
const startObj = {
        value: startDate.getFullYear()+'-'+(startDate.getMonth()+1)
    };
document.onload = chartFilter(startObj)

