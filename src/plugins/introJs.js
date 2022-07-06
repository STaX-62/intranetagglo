import Vue from 'vue';
import introJs from 'intro.js'
import 'intro.js/introjs.css';

Vue.prototype.$introJs = introJs()
    .setOptions({
        nextLabel: "Suivant",
        prevLabel: "Précédent",
        skipLabel: "Passer",
        doneLabel: "Terminer",
        steps: [{
            title: "Tutoriel",
            intro: "Bienvenue sur l'intranet de la CA2BM"
        },
        {
            title: "Menu de Navigation",
            intro: "Les liens utiles adapter à vos besoins trier en fonction de votre service",
            element: document.querySelector(".sidenav-menu")
        },
        {
            title: "Documents Partagés",
            intro: "Retrouvez ici le dossier des documents partagés pour tous les services",
            element: document.querySelector(".Files")
        },
        {
            title: "Alertes",
            intro: "Ici vous retrouverez les informations/alertes temporaires, celle-ci expireront au bout d'une certaine période",
            element: document.querySelector(".news-alerts")
        },
        {
            title: "Actualités",
            intro: "Dans cette section vous seront partagé les actualités de la CA2BM, cliquez simplement sur une actualité pour avoir agrandir ou être redirigé vers le contenu",
            element: document.querySelector(".news-block")
        },
        {
            title: "Barre de Recherche",
            intro: "Vous pouvez rechercher des actualités et alertes",
            element: document.querySelector(".searchbar")
        },
        {
            title: "Filtres",
            intro: "ainsi que filtrer les actualités par date de parution ou catégorie",
            element: document.querySelector(".news-filtres")
        },
        {
            title: "Tutoriel",
            intro: "Retrouvez également les applications les plus utiles dans cette section",
            element: document.querySelector(".apps-container")
        }
        ],
        showBullets: false,
        showProgress: true,
    });