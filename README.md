# Application Météo

# Description
L'application permet de générer une image au format .png avec les informations (Visuel des conditions météo, témpérature)
à partir d'une ville saisie.

## Prérequis
- Installation de Docker

## Installation de l'environnement avec docker
- Cloner le projet :  <code>git clone git@github.com:r-lempereur/app-meteo.git</code>  
- <code>cd app-meteo</code> 
- <code>docker-compose up -d</code>

## Configuration
- Créer un compte sur l'api Visual Crossing et générer une clé d'api https://www.visualcrossing.com/weather-data
- Renseigner la variable API_KEY du fichier .env avec la clé générée

## Démarrage de l'application
- Accéder à l'application: http://localhost:8000/public/index.php
