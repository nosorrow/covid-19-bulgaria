const confirmed = document.querySelector("#confirmed");
const newCases = document.querySelector("#newCases");
const active = document.querySelector("#active");
const deaths = document.querySelector("#deaths");
const recovered = document.querySelector("#recovered");
const hospital = document.querySelector("#hospital");
const intensive = document.querySelector("#intensive");
const percent = document.querySelector("#percent");
const prognosis = document.querySelector("#prognosis");
const updatedAt = document.querySelector("#updated");
const tests = document.querySelector("#tests");
const url = "data/data-covid.json";
const getResult = fetchDataCovid();

window.addEventListener("load", function (e) {
  getResult.then((jsonData) => {
    let updated = new Date(jsonData.today.date);
    updatedAt.innerHTML = updated.toLocaleString("bg-BG");
    confirmed.innerHTML = formatNumber(jsonData.today.confirmed);
    newCases.innerHTML = formatNumber(jsonData.today.newCases);
    active.innerHTML = formatNumber(jsonData.today.active);
    deaths.innerHTML = formatNumber(jsonData.today.deaths);
    recovered.innerHTML = formatNumber(jsonData.today.recovered);
    hospital.innerHTML = formatNumber(jsonData.today.hospital);
    intensive.innerHTML = jsonData.today.intensive;
    percent.innerHTML = jsonData.today.percent + "%";
    tests.innerHTML = formatNumber(jsonData.today.tests);

    console.log(formatNumber(jsonData.today.hospital));
  });
});

$("#date").datepicker({
  minDate: new Date(),
  dateFormat: "yy-mm-dd",
  onSelect: function (date) {
    getResult.then((jsonData) => {
      let beginTime = new Date(jsonData.today.date);
      let newDateTime = new Date(date);
      let diffTime = newDateTime.getTime() - beginTime.getTime();
      let diffDays = Math.ceil(diffTime / (1000 * 3600 * 24));
      let base =
        jsonData.today.confirmed /
        (jsonData.today.confirmed - jsonData.today.newCases);
      let prognosisActive = Math.pow(base, diffDays) * jsonData.today.confirmed;
      let prognosisNewCases =
        Math.pow(base, diffDays) * jsonData.today.newCases;

      prognosis.innerHTML = `<p>прогнозата  за 
            дата ${date} е около <strong>${prognosisActive.toFixed()}</strong> oбщо заразени, 
            от които нови за деня <strong>${prognosisNewCases.toFixed()}</strong></p>`;
    });
  },
});

async function fetchDataCovid() {
  const response = await fetch(url);
  const jsonData = await response.json();

  return jsonData;
}

function formatNumber(n) {
  return n.toLocaleString("en").replace(/,/g, " ");
}
