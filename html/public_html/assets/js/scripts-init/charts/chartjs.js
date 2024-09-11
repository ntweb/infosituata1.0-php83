// Chart.js

var randomScalingFactor = function () {
    return Math.round(Math.random() * 100);
};

var configPie = {
    type: 'pie',
    data: {
        datasets: [{
            data: [],
            backgroundColor: [
                'rgba(255, 0, 0, 1)',
                'rgba(255, 128, 0, 1)',
                'rgba(255, 255, 0, 1)',
                'rgba(0, 255, 0, 1)',
                'rgba(0, 255, 255, 1)',
                'rgba(0, 128, 255, 1)',
                'rgba(127, 0, 255, 1)',
                'rgba(255, 0, 255, 1)',
                'rgba(255, 0, 127, 1)',
            ],
            label: 'Dataset 1'
        }],
        labels: []
    },
    options: {
        responsive: true
    }
};

$( document ).ready(function() {
    var values = [];
    var labels = [];
    if ($('#chartTipoLavoratore').length) {
        values = $('#chartTipoLavoratore').data('values') + '';
        labels = $('#chartTipoLavoratore').data('labels') + '';
        values = values.split(',');
        labels = labels.split(',');
        var configPie1 = JSON.parse(JSON.stringify(configPie));
        configPie1.data.datasets[0].data = values;
        configPie1.data.labels = labels;

        new Chart(document.getElementById('chartTipoLavoratore').getContext('2d'), configPie1);
    }
    if ($('#chartTipologia').length) {
        values = $('#chartTipologia').data('values')+'';
        labels = $('#chartTipologia').data('labels')+'';
        values = values.split(',');
        labels = labels.split(',');
        var configPie2 = JSON.parse(JSON.stringify(configPie));
        configPie2.data.datasets[0].data = values;
        configPie2.data.labels = labels;

        new Chart(document.getElementById('chartTipologia').getContext('2d'), configPie2);
    }
    if ($('#chartTipologiaIncidente').length) {
        values = $('#chartTipologiaIncidente').data('values')+'';
        labels = $('#chartTipologiaIncidente').data('labels')+'';
        values = values.split(',');
        labels = labels.split(',');
        var configPie3 = JSON.parse(JSON.stringify(configPie));
        configPie3.data.datasets[0].data = values;
        configPie3.data.labels = labels;

        new Chart(document.getElementById('chartTipologiaIncidente').getContext('2d'), configPie3);
    }
    if ($('#chartReparto').length) {
        values = $('#chartReparto').data('values')+'';
        labels = $('#chartReparto').data('labels')+'';
        values = values.split(',');
        labels = labels.split(',');
        var configPie3 = JSON.parse(JSON.stringify(configPie));
        configPie3.data.datasets[0].data = values;
        configPie3.data.labels = labels;

        new Chart(document.getElementById('chartReparto').getContext('2d'), configPie3);
    }
    if ($('#chartQualifica').length) {
        values = $('#chartQualifica').data('values')+'';
        labels = $('#chartQualifica').data('labels')+'';
        values = values.split(',');
        labels = labels.split(',');
        var configPie3 = JSON.parse(JSON.stringify(configPie));
        configPie3.data.datasets[0].data = values;
        configPie3.data.labels = labels;

        new Chart(document.getElementById('chartQualifica').getContext('2d'), configPie3);
    }
});

