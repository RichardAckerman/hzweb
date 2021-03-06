import Vue from 'vue'
import Router from 'vue-router'

import firstScreen from '@/components/indexRouter/firstScreen'
import createWallet from '@/components/wallet/createWallet'
import importWallet from '@/components/wallet/importWallet'

import mainScreen from '@/components/indexRouter/mainScreen'
import accountInfo from '@/components/accountInfo/accountInfo'
import tradeRecord from '@/components/tradeRecord/tradeRecord'
import transaction from '@/components/transaction/transaction'
import applications from '@/components/applications/appList'
import createApp from '@/components/createApp/createApp'
import myApps from '@/components/myApps/myApps'


import appDetail from '@/components/applications/appDetail'

Vue.use(Router)

export default new Router({
    mode: 'hash',
    routes: [
        {
            path: '/',
            component: firstScreen,
            children: [
                {
                    path: '/',
                    redirect: 'createWallet'
                },
                {
                    path: 'createWallet',
                    name: 'createWallet',
                    component: createWallet
                },
                {
                    path: 'importWallet',
                    name: 'importWallet',
                    component: importWallet
                }

            ]
        },
        {
            path: '/mainScreen',
            component: mainScreen,
            children: [
                {
                    path: '/',
                    redirect: 'accountInfo'
                },
                {
                    path: 'accountInfo',
                    name: 'accountInfo',
                    component: accountInfo,
                    meta: {
                        keepAlive: true // 需要被缓存
                    }
                },
                {
                    path: 'tradeRecord',
                    name: 'tradeRecord',
                    component: tradeRecord
                },
                {
                    path: 'transaction',
                    name: 'transaction',
                    component: transaction
                },
                {
                    path: 'applications',
                    name: 'applications',
                    component: applications
                },
                {
                    path: 'createApp',
                    name: 'createApp',
                    component: createApp
                },
                {
                    path: 'myApps',
                    name: 'myApps',
                    component: myApps
                },
            ]
        },
        {
            path: '/appDetail',
            name: 'appDetail',
            component: appDetail
        }
    ]
})
