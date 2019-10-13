console.log("chegou");
var mbus = new Vue({
    el: '#mbus',
    delimiters: ["[[", "]]"],
    data: {
        home_url: '/ava/', 
        logo_url: 'https://ead.ifrn.edu.br/ava/aberto/theme/boost_eadifrn/pix/eadifrn_zl_logo.svg',
        user_picture: 'https://warehouse-camo.cmh1.psfhosted.org/39ec2ccf3e22a59cc3b05241c2257f7d3413806b/68747470733a2f2f7365637572652e67726176617461722e636f6d2f6176617461722f35366537666234303865663638636133663766646163646565396439636635353f73697a653d3530',
        categories: [
            {
                'description': 'Destaques', 
                'url': '#',
                'courses': [
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/12/30/20/59/report-3050965_960_720.jpg',
                        'title': 'Estudo de patologias infantis',
                        'see': '#',
                        'enrol': '#',
                        'duration': '40h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/12/30/20/59/report-3050965_960_720.jpg',
                        'title': 'Estudo de patologias infantis',
                        'see': '#',
                        'enrol': '#',
                        'duration': '40h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                ],
            },
            {
                'description': 'Gestão', 
                'url': '#',
                'courses': [
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/12/30/20/59/report-3050965_960_720.jpg',
                        'title': 'Estudo de patologias infantis',
                        'see': '#',
                        'enrol': '#',
                        'duration': '40h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                ],
            },
            {
                'description': 'Administração', 
                'url': '#',
                'courses': [
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/12/30/20/59/report-3050965_960_720.jpg',
                        'title': 'Estudo de patologias infantis',
                        'see': '#',
                        'enrol': '#',
                        'duration': '40h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                    { 
                        'thumbnail': 'https://cdn.pixabay.com/photo/2017/09/02/01/07/trail-2706080_960_720.jpg',
                        'title': 'Segurança em trekking',
                        'see': '#',
                        'enrol': '#',
                        'duration': '4000h',
                    },
                ]
            }             
        ]
    }
});
console.log("saiu");