import $message from './message'
import $router from '../router'

export default {
    validatePwd(rule, value, callback) {     //验证创建钱包的密码强度
        let reg = /^(?![0-9]+$)(?![a-zA-Z]+$)\w{8,}$/g;
        if (value) {
            if (!reg.test(value)) {
                return callback(new Error($message.createPwd));
            } else {
                return callback()
            }
        }
    },

    linkToMainScreenRep(params){     //转到主页面 / replace模式
        if(params && (typeof params !=='object')){
            return new Error('Param Error')
        }
        params = params ? params : {}
        $router.replace({name: 'accountInfo',params: params})
    },


    getLocalAddress(){
        let json = localStorage.getItem('userAddress')
        if(json){
            return JSON.parse(json)
        } else {
            return null
        }
    },
    setLocalAddress(wallet){
        let obj = localStorage.getItem('userAddress')
        if (obj) {
            obj = JSON.parse(obj)
        }
        let addresses = obj ? obj.addresses : []
        let index = addresses.indexOf(wallet.address)
        if (index === -1) {
            index = obj ? addresses.length : 0
            addresses.push(wallet.address)
        }
        obj = {active: index, addresses: addresses}
        localStorage.setItem('userAddress',JSON.stringify(obj))
    }
}
