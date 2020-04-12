const confirmed = document.querySelector('#confirmed');
const newCases = document.querySelector('#newCases');
const active = document.querySelector('#active');
const deaths = document.querySelector('#deaths');
const recovered = document.querySelector('#recovered');
const hospital = document.querySelector('#hospital');
const intensive = document.querySelector('#intensive');
const percent = document.querySelector('#percent');
const prognosis = document.querySelector('#prognosis');
const updatedAt = document.querySelector('#updated');
const url = 'data/data-covid.json';
var data = data = fetchDataCovid();

window.addEventListener('load', function (e) {

    data.then(jsonData => {
        let updated = new Date(jsonData.today.date+' 18:00:00');
        updatedAt.innerHTML = updated.toLocaleString('bg-BG');
        confirmed.innerHTML = jsonData.today.confirmed;
        newCases.innerHTML = jsonData.today.newCases;
        active.innerHTML = jsonData.today.active;
        deaths.innerHTML = jsonData.today.deaths;
        recovered.innerHTML = jsonData.today.recovered;
        hospital.innerHTML = jsonData.today.hospital;
        intensive.innerHTML = jsonData.today.intensive;
        percent.innerHTML = jsonData.today.percent + '%';

    })

});

$("#date").datepicker({
    minDate: new Date(),
    dateFormat: 'yy-mm-dd',
    onSelect: function (date) {
        data.then(jsonData => {
            let beginTime = new Date(jsonData.today.date);
            let newDateTime = new Date(date);
            let diffTime = newDateTime.getTime() - beginTime.getTime();
            let diffDays = Math.ceil(diffTime / (1000 * 3600 * 24));
            let base = (jsonData.today.confirmed / (jsonData.today.confirmed - jsonData.today.newCases));
            let prognosisActive = Math.pow(base, diffDays) * jsonData.today.confirmed;
            let prognosisNewCases = Math.pow(base, diffDays) * jsonData.today.newCases;

            prognosis.innerHTML = `<p>прогнозата  за 
            дата ${date} е около <strong>${prognosisActive.toFixed()}</strong> oбщо заразени, 
            от които нови за деня <strong>${prognosisNewCases.toFixed()}</strong></p>`;
        });

    }

});

async function fetchDataCovid() {
    const response = await fetch(url);
    const jsonData = await response.json();

    return jsonData;
}
