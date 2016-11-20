//<!--
/*
 * Fonction permettant de mettre en place le fonctionnement d'un menu à onglet
 * @Param
 *      name : permet de définir le premier onglet comme actif. 
 */
function change_onglet(name) {
    document.getElementById('onglet_' + anc_onglet).className = 'onglet_0 onglet';
    document.getElementById('onglet_' + name).className = 'onglet_1 onglet';
    document.getElementById('contenu_onglet_' + anc_onglet).style.display = 'none';
    document.getElementById('contenu_onglet_' + name).style.display = 'block';
    anc_onglet = name;
}

/*
 * Fonction permettant de réaliser un zoom sur une image au click de la souris 
 */
$(document).ready(function () {
    $("#historique img").each(function (i) {
        var name = 'img_zoom_image';
        $("body").append("<div class='zoom' id='" + name + i + "' style='position:absolute; visibility:hidden; left:-286px; top:0px; z-index:1000;'><figure><img id='img_zoom_image' src='" + $(this).attr('src') + "'  /><figcaption>" + $(this).attr('title') + "</figcaption></figure></div>");
        console.log($(this).attr('src'));
        var my_tooltip = $("#" + name + i);

        $(this).click(function () {
            my_tooltip.css({opacity: 1, display: "none", visibility: "visible"}).fadeIn(333);
        }).mousemove(function (kmouse) {
            my_tooltip.css({left: kmouse.pageX + 15, top: kmouse.pageY - 230});
        }).mouseout(function () {
            my_tooltip.fadeOut(50);
        });
    });
});

/*
 * Fonction permettant de définir les hauteurs et les largeurs des cadres de l'application 
 */
$(document).ready(function () {
    var hauteur = ($(window).height() - 105); //-105 correspond au nombre de pixel du bandeau supérieur. 
    var largeur = ($(window).width() - 3); //-3 correspond au margin appliqué pour aérrer. 
    $('#ficheClient').css({height: hauteur, width: largeur});
    $('#historique').css({height: hauteur - (hauteur / 6.5)});
    $('#recherche').css({height: hauteur});
    $('#res').css({height: hauteur - 20});

    var hauteurCase = ($('#ficheClient').height() / 2);
    var largeurCase = ($('#ficheClient').width() / 2);
    $('#graph').css({height: hauteurCase, width: largeurCase});
    $('#detail').css({height: hauteurCase, width: largeurCase});
    $('#poste').css({height: hauteurCase, width: largeurCase});
    $('#champs').css({height: hauteurCase, width: largeurCase});
});

/*
 * Fonction permettant de modifier la taille des cadres à chaque redimensionnement de la page
 */
function taille() {
    var hauteur = ($(window).height() - 105); //-105 correspond au nombre de pixel du bandeau supérieur. 
    var largeur = ($(window).width() - 3); //-3 correspond au margin appliqué pour aérrer. 
    $('#ficheClient').css({height: hauteur, width: largeur});
    $('#historique').css({height: hauteur - (hauteur / 6.5)});
    $('#recherche').css({height: hauteur});
    $('#res').css({height: hauteur - 20});

    var hauteurCase = ($('#ficheClient').height() / 2);
    var largeurCase = ($('#ficheClient').width() / 2);
    $('#graph').css({height: hauteurCase, width: largeurCase});
    $('#detail').css({height: hauteurCase, width: largeurCase});
    $('#poste').css({height: hauteurCase, width: largeurCase});
    $('#champs').css({height: hauteurCase, width: largeurCase});
}

/*
 * Fonction permettant de charger la partie droite d'une fiche client. Elle permet de charger les détails d'un poste, serveur ou BDD
 * @Param 
 *      id : id du poste, serveur ou BDD dont on veut les infos
 *      chemin : indique la page a charger. 
 */
function load(id, chemin) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', chemin + '?id=' + id);
    xhr.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr.readyState === 4 && xhr.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('detail').innerHTML = xhr.responseText; // Et on affiche !
        } else if (xhr.readyState === 4 && xhr.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
        }
    }, false);
    xhr.send(null);

    var xhr2 = new XMLHttpRequest();
    xhr2.open('GET', chemin + 'Info?id=' + id);
    xhr2.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr2.readyState === 4 && xhr2.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('champs').innerHTML = xhr2.responseText;// Et on affiche !
            $('#onglet_1').click();
        } else if (xhr2.readyState === 4 && xhr2.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr2.status + '\nTexte : ' + xhr2.statusText);
        }
    }, false);
    xhr2.send(null);
}

/*
 * Permet d'afficher automatiquement le nom d'un client lorsqu'on choisie son id. 
 */
function getNom() {
    id = document.getElementById('cli_id');
    valeur = id.options[id.selectedIndex].value.split('||');
    document.getElementById('cache').value = valeur[0];
    document.getElementById('cli_nom').value = valeur[1];
}

/*
 * Permet d'effacer tous les champs d'un formulaire
 */
function effacer(id) {
    $(':input', id)
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
}

/*
 * Permet de charger les infos de la recherche dans la partie droite de l'écran 
 * @Param 
 *      chemin : chemin de la page à charger 
 *      id1 : id du premier paramètre à envoyer via le POST 
 *      id2 : id du second paramètre à envoyer via le POST 
 */
function recherche(chemin, id1, id2, nbParPage) {
    var xh = new XMLHttpRequest();
    xh.open('GET', './eraseSession', true);
    xh.send(null);
    if (xh.readyState === 4 && xh.status !== 200) { // En cas d'erreur !
        alert('Une erreur est survenue !\n\nCode :' + xh.status + '\nTexte : ' + xh.statusText);
    }
    var xhr = new XMLHttpRequest();
    xhr.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr.readyState === 4 && xhr.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('recherche').innerHTML = xhr.responseText; // Et on affiche !
            //var largeur = ($(window).width()) / 1.39;
            var largeur = $('#recherche').width();
            $('#navbar').css({
                position: 'fixed',
                bottom: '4px',
                width: largeur
            });

        } else if (xhr.readyState === 4 && xhr.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
        }
    }, false);
    xhr.open('POST', chemin, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var name1 = $('#' + id1).attr('name');
    var valName1 = $('#' + id1).val();
    var name2 = $('#' + id2).attr('name');
    var valName2 = $('#' + id2).val();
    var NbParPage = $('#' + nbParPage).val();
    xhr.send(name1 + "=" + valName1 + "&" + name2 + "=" + valName2 + "&nbparpage=" + NbParPage);
    //effacer();
}

/*
 * Fonction permettant de charger un graphique 
 * @param {type} chemin : page dont on veut appeller l'adresse.
 * @param {type} id1 : premier paramètre à passer dans le POST
 * @param {type} id2 : second paramètre à passer dans le POST
 * @param {type} id3 : troisième paramètre à passer dans le POST 
 */
function graph(chemin, id1, id2, id3) {
    var xhr = new XMLHttpRequest();
    xhr.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr.readyState === 4 && xhr.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('res').innerHTML = xhr.responseText; // Et on affiche !
            var largeur = ($(window).width()) / 1.39;
            $('#navbar').css({
                position: 'fixed',
                bottom: '4px',
                width: largeur
            });

        } else if (xhr.readyState === 4 && xhr.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
        }
    }, false);
    xhr.open('POST', chemin, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var name1 = $('#' + id1).attr('name');
    var valName1 = $('#' + id1).val();
    var name2 = $('#' + id2).attr('name');
    var valName2 = $('#' + id2).val();
    var name3 = $('#' + id3).attr('name');
    var valName3 = $('#' + id3).val();
    xhr.send(name1 + "=" + valName1 + "&" + name2 + "=" + valName2 + "&" + name3 + "=" + valName3);
}

/*
 * Permet de charger les autres pages après une recherche
 * @Param 
 *      chemin : chemin de la page a charger
 */
function charger(chemin) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', chemin);
    xhr.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr.readyState === 4 && xhr.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('recherche').innerHTML = xhr.responseText; // Et on affiche !
            var largeur = ($(window).width()) / 1.39;
            $('#navbar').css({
                position: 'fixed',
                bottom: '4px',
                width: largeur
            });
        } else if (xhr.readyState === 4 && xhr.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
        }
    }, false);
    xhr.send(null);
}

/*
 * Fonction permettant d'appeller une page php qui supprimera le contenu d'un dossier
 * La page sera rechargé après l'appel de cette fonction. 
 */
function clearFolder() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', './clear');
    xhr.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr.readyState === 4 && xhr.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('clear').innerHTML = xhr.responseText;
        } else if (xhr.readyState === 4 && xhr.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
        }
    }, false);
    xhr.send(null);
    location.reload();
}


/*
 * Fonction qui permet de charger les données présentent dans la pop up de log pour un poste
 * @Param 
 *      id : id du log dont on veut charger les données. 
 */
function getLog(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', './getLog' + '?id=' + id);
    xhr.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr.readyState === 4 && xhr.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('afficheLog').innerHTML = xhr.responseText; // Et on affiche !

        } else if (xhr.readyState === 4 && xhr.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
        }
    }, false);
    xhr.send(null);

    var xhr2 = new XMLHttpRequest();
    xhr2.open('GET', './getCat' + '?id=' + id);
    xhr2.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr2.readyState === 4 && xhr2.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('afficheCategorie').innerHTML = xhr2.responseText; // Et on affiche !

        } else if (xhr2.readyState === 4 && xhr2.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr2.status + '\nTexte : ' + xhr2.statusText);
        }
    }, false);
    xhr2.send(null);

    var xhr3 = new XMLHttpRequest();
    xhr3.open('GET', './getBase' + '?id=' + id);
    xhr3.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr3.readyState === 4 && xhr3.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('afficheBase').innerHTML = xhr3.responseText; // Et on affiche !

        } else if (xhr3.readyState === 4 && xhr3.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr3.status + '\nTexte : ' + xhr3.statusText);
        }
    }, false);
    xhr3.send(null);
}

/*
 * Fonction qui permet de charger les données présentent dans la pop up de log pour une base de données
 * @Param 
 *      id : id du log dont on veut charger les données. 
 */
function getLogBase(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', './getLogBase' + '?id=' + id);
    xhr.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr.readyState === 4 && xhr.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('afficheLog').innerHTML = xhr.responseText; // Et on affiche !

        } else if (xhr.readyState === 4 && xhr.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
        }
    }, false);
    xhr.send(null);

    var xhr2 = new XMLHttpRequest();
    xhr2.open('GET', './getCatBase' + '?id=' + id);
    xhr2.addEventListener('readystatechange', function () { // On gère ici une requête asynchrone
        if (xhr2.readyState === 4 && xhr2.status === 200) { // Si le fichier est chargé sans erreur
            document.getElementById('afficheCategorie').innerHTML = xhr2.responseText; // Et on affiche !

        } else if (xhr2.readyState === 4 && xhr2.status !== 200) { // En cas d'erreur !
            console.log('Une erreur est survenue !\n\nCode :' + xhr2.status + '\nTexte : ' + xhr2.statusText);
        }
    }, false);
    xhr2.send(null);

}

//-->