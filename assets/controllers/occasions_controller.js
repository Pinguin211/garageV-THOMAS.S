import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {request} from "./lib/func/Request";
import {createNotification} from "./lib/func/Notification";


export default class extends Controller {

    connect() {

        $(document).ready(() => {
            this.setRange('min_price', 'max_price', { style: 'currency', currency: 'EUR' })
            this.setRange('min_klm', 'max_klm', { style: 'unit', unit: 'kilometer' })
            this.setRange('min_year', 'max_year', {})
            this.setRefreshButton()
            setCars()
        })
    }


    setRange(min_id, max_id, label_nb_options) {
        const min_price = $('#' + min_id);
        const max_price = $('#' + max_id);


        const setLabel = function () {
            let min = parseInt(min_price.val())
            let max = parseInt(max_price.val())

            const min_label = $(`#show_${min_id}`)
            const max_label = $(`#show_${max_id}`)

            max_label.empty()
            max_label.text(max.toLocaleString('fr-FR', label_nb_options))
            min_label.empty()
            min_label.text(min.toLocaleString('fr-FR', label_nb_options))
        }

        min_price.on("input", function() {
            if (parseInt($(this).val()) >= parseInt(max_price.val())) {
                max_price.val($(this).val());
            }
            setLabel()
        });

        max_price.on("input", function() {
            if (parseInt($(this).val()) <= parseInt(min_price.val())) {
                min_price.val($(this).val());
            }
            setLabel()
        });

        setLabel()
    }

    setRefreshButton() {
        $('#refresh_button').on('click', function () {
            setCars()
        })
    }
}

function setCars()
{
    const title = $('#search_title').val()
    const max_price = $('#max_price').val()
    const min_price = $('#min_price').val()
    const min_klm = $('#min_klm').val()
    const max_klm = $('#max_klm').val()
    const min_year = $('#min_year').val()
    const max_year = $('#max_year').val()

    request(
        '/occasions_cars',
        {
            'title': title,
            'min_price': min_price,
            'max_price': max_price,
            'min_klm': min_klm,
            'max_klm': max_klm,
            'min_year': min_year,
            'max_year': max_year
        },
        'json',
        false
    )
        .then(response => {
            setCarList(response)
        })
        .catch(error => {
            createNotification(error)
        })
}

function setCarList(cars) {

    const list = $('#car_list')

    list.empty()

    cars.forEach(car => {
        list.append(getHeadPiece(car))
    })
}

function getHeadPiece(car)
{
    const message = `Sujet: Annonce ${car.id} - ${car.title}`

    return $(`
<div class="headpiece m-3">
    <div id="headpiece-image">
        <div id="headpieceCarrouselControls${car.id}" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="99999999999">
            <div class="carousel-inner">
                ${getCarCaroussel(car)}
                <button class="carousel-control-prev" type="button" data-bs-target="#headpieceCarrouselControls${car.id}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#headpieceCarrouselControls${car.id}" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
    <div id="headpiece-content">
        ${getCarContent(car)}
    </div>
    <div id="headpiece-button">
        <a href="/occasions/car?id=${car.id}&message=${message}">
            <button class="btn">Details</button>
        </a>
    </div>
</div>
`)

}

function getCarContent(car)
{
    return `<div id="car-content-headpiece">
            <div class="title">${car.title}</div>
            <div class="car-content-headpiece-info">${car.year}</div>
            <div class="car-content-headpiece-info">${car.fuel}</div>
            <div class="car-content-headpiece-info">${ car.kilometers.toLocaleString('fr-FR', { style: 'unit', unit: 'kilometer' })}</div>
            <div class="text-center title">${car.price.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' }) }</div>
            </div>`
}

function getCarCaroussel(car)
{
    let html = ''
    let list = Object.values(car.images)

    if (list.length > 0)
    {
        const path = list.shift()
        const image1 =
            '<div class="carousel-item active">' +
            `<img src="images/occasions/${path}" alt="Image">` +
            '</div>'

        html += image1
        if (list.length > 0)
        {
            list.forEach(path => {
                html +=
                    '<div class="carousel-item">' +
                    `<img src="images/occasions/${path}" alt="Image">` +
                    '</div>'
            })
        }
        else
            html +=
                '<div class="carousel-item">' +
                `<img src="images/occasions/${path}" alt="Image">` +
                '</div>'
    }
    else
    {
        return  '<div class="carousel-item active">' +
                '<img src="images/unknow.jpg" alt="no image">' +
                '</div>' +
                '<div class="carousel-item">' +
                '<img src="images/unknow.jpg" alt="no image">' +
                '</div>'
    }
    return html
}


