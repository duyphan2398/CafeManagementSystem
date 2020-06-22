
var auth_user;

axios.get(location.origin + '/axios/info').then(function (response) {
    auth_user = response.data.auth_user;
}).catch(function (error) {
    console.log(error);
})


function checkRole(insert, auth) {
    if (auth_user.role == 'Admin') {
        return true;
    } else {
        return insert.role == 'Admin' ? false : true;
    }
}
