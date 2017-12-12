$().ready(function () {

    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        }
    });

    $("#signupForm").validate({
        rules: {
            email: {
                required: true,
                remote: {
                    url: '/check/email',
                    type: "post",
                    data: {
                            email: $('#signupForm:input[name="email"]').val()
                        }
                },
                email: true,
                maxlength: 100
            },
            password: {
                required: true,
                alphanumeric: true,
                minlength: 6
            },
            passwordAgain: {
                equalTo: '#signupPass'
            }
        },
        messages: {
            email: {
                remote: 'Такой Email уже занят, попробуйте войти'
            },
            password: {
                alphanumeric: 'Пароль может содержать только буквы и числа'
            }
        }
    });

    $("#profileForm").validate({
        rules: {
            f_name: {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            l_name: {
                required: true,
                minlength: 2,
                maxlength: 40
            },
            email: {
                required: true,
                email: true,
                maxlength: 100,
                remote: {
                    url: '/check/email',
                    type: "post",
                    data: {
                        email: $('#profileForm:input[name="email"]').val()
                    }
                },
            },
            phone_number: {
                pattern: '\\+380\\d{9}',
                remote: {
                    url: '/check/phone',
                    type: "post",
                    data: {
                        phone: $('#profileForm:input[name="phone_number"]').val()
                    }
                }
            },
            city : {
                maxlength: 64
            }
        },
        messages: {
            phone_number: {
                pattern: 'Введите корректный номер телефона',
                remote: 'Такой номер телефона уже используеться'
            }
        }
    });

    $("#changePassForm").validate({
        rules: {
            password: {
                required: true,
                alphanumeric: true,
                minlength: 6
            },
            passwordAgain: {
                equalTo: '#inputPassword-ChangePassword'
            }
        }
    });

    $("#addReviewForm").validate({
        rules: {
            text: {
                required: true
            }
        },
        messages: {
            mark: {
                required: 'Выберите марку авто'
            },
            model: {
                required: 'Выберите модель авто'
            },
            text: {
                required: 'Напишите отзыв об автомобиле'
            }
        }
    });

    $("#addTheme").validate({
        rules: {
            title: {
                required: true,
                minlength: 2,
                remote: {
                    url: '/check/themeTitle',
                    type: "post",
                    data: {
                        title: $('#addTheme:input[name="title"]').val()
                    }
                }
            },
            text: {
                required: true
            }
        },
        messages: {
            title: {
                remote: 'Такая тема уже существует'
            }
        }
    });
});