<template>
    <div>
        <div class="create-app">
            <div class="create-header no-select-text">
                <p :class="{'header-active':steps===1}">用户协议</p>
                <span></span>
                <p :class="{'header-active':steps===2}">选择类型</p>
                <span></span>
                <p :class="{'header-active':steps===3}">配置应用</p>
                <span></span>
                <p :class="{'header-active':steps===4}">创建成功</p>
            </div>

            <div class="create-body">
                <!--step-1-->
                <transition name="fof-fade">
                    <div class="step-1" v-show="steps===1">
                        <div class="header">用户协议</div>
                        <div class="body">
                            <agreement></agreement>
                        </div>
                        <p class="tc" style="margin: 20px;">
                            <el-checkbox v-model="checked">同意以上协议</el-checkbox>
                        </p>
                    </div>
                </transition>
                <!--step-2-->
                <transition name="fof-fade">
                    <div class="step-2" v-show="steps===2">
                        <div class="header">选择你想要创建的游戏</div>
                        <div class="body">
                            <el-row>
                                <el-col :span="4" v-for="(item,index) in appList" :key="index">
                                    <application :item="item"
                                                 :index="index"
                                                 ref="funcs"
                                                 @click.native="selectAnApp(item.type,index)"
                                    ></application>
                                </el-col>
                            </el-row>
                        </div>
                    </div>
                </transition>
                <!--step3-->
                <transition name="fof-fade">
                    <div class="step-3" v-show="steps===3">
                        <div class="header">请配置相关参数</div>
                        <div class="body">
                            <div class="content">
                                <el-form
                                        label-width="110px"
                                        label-position="left"
                                        @submit.native.prevent>

                                    <el-form-item class="el-wallet-style" label="充值金额">
                                        <el-input
                                                auto-complete="off"
                                                v-model="rechargeData.value"
                                        >
                                            <template slot="append">FOF</template>
                                        </el-input>
                                    </el-form-item>
                                </el-form>
                                <el-form class="mt-50"
                                         label-width="110px"
                                         label-position="left"
                                         @submit.native.prevent>

                                    <el-form-item class="el-wallet-style" label="下注金额">
                                        <el-row>
                                            <el-col :span="7">
                                                <el-input
                                                        auto-complete="off"
                                                        v-model="rechargeData.price1"
                                                >
                                                    <template slot="append">FOF</template>
                                                </el-input>
                                            </el-col>
                                            <el-col :span="7" :offset="1">
                                                <el-input
                                                        auto-complete="off"
                                                        v-model="rechargeData.price2"
                                                >
                                                    <template slot="append">FOF</template>
                                                </el-input>
                                            </el-col>
                                            <el-col :span="7" :offset="1">
                                                <el-input
                                                        auto-complete="off"
                                                        v-model="rechargeData.price3"
                                                >
                                                    <template slot="append">FOF</template>
                                                </el-input>
                                            </el-col>
                                        </el-row>
                                    </el-form-item>
                                </el-form>
                            </div>
                            <div class="explain">
                                <longhudou-rule></longhudou-rule>
                            </div>
                        </div>
                    </div>
                </transition>
                <!--step4-->
                <transition name="fof-fade">
                    <div class="step-4" v-show="steps===4">
                        <div class="body">
                            <div class="finishe-icon">
                                <i></i>
                                <p>创建成功</p>
                            </div>
                            <div class="finishe-address">
                                <el-form
                                        label-width="110px"
                                        label-position="left"
                                        @submit.native.prevent>

                                    <el-form-item class="el-wallet-style" label="应用地址">
                                        <el-row>
                                            <el-col :span="20">
                                                <el-input id="appAddress"
                                                          auto-complete="off"
                                                          v-model="contractAddressUrl"
                                                          readonly
                                                ></el-input>
                                            </el-col>
                                            <el-col :span="4">
                                                <el-button @click="copyAddress">复制</el-button>
                                            </el-col>
                                        </el-row>
                                    </el-form-item>
                                </el-form>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
            <div class="create-footer">
                <el-button class="el-wallet-main-button" @click="preStep" v-show="steps===2 || steps===3">上一步
                </el-button>
                <el-button class="el-wallet-main-button" @click="nextStep" :disabled="!checked">{{btnVal}}</el-button>
                <a id="linkToApp" :href="contractAddressUrl" target="_blank"></a>
            </div>
        </div>
    </div>
</template>

<script>
    import agreement from './agreement'
    import application from './apps'
    import longhudouRule from '../ruleDescription/longhudouRule'
    // 先部署定时器合约 得到其地址
    import intervalContract from '../../../contracts/Interval.json'
    import playGameContract from '../../../contracts/playGame.json'
    import solSource from '../../../contracts/playGame.sol'


    export default {
        name: "create-app",
        components: {
            agreement,
            application,
            longhudouRule
        },
        data() {
            return {
                steps: 1,
                selected: '',
                appList: [{
                    contractAddr: "0xfe56582a999c5dae6ba4cc9ea84e9f1842e5fb39",
                    createAddr: "0x8ddb5f0b47a027cea553c58734389dd4ed7ff7f5",
                    currentCoin: "1000000000000.00000000",
                    historyCoin: null,
                    id: "93",
                    time: "2018-05-23 02:25:30",
                    type: "1"
                }],
                btnVal: "下一步",
                setCoin: null,
                contractAddress: '',
                contractAddressUrl: '',// 可以访问的地址
                rechargeValue: '',
                rechargeData: {
                    value: '100',
                    gasPrice: '41',
                    gas: '21000',
                    price1: '1',
                    price2: '5',
                    price3: '10'
                },
                publicKey: '',
                password: '',
                checked: false,//同意协议
            }
        },
        methods: {
            preStep() {
                if (this.steps > 1) {
                    this.steps--
                }
            },
            nextStep() {
                if (this.steps < 4) {
                    let users = this.$funs.getLocalAddress()
                    let balance = this.$funs.getBalance(users.addresses[users.active])
                    if (this.steps === 1) {
                        if (balance < 1) {
                            this.$message({
                                message: this.$msg.balanceNotEnough,
                                type: 'error'
                            })
                            return
                        }
                    }
                    if (this.steps === 2 && (this.selected === '')) {
                        this.$message({
                            message: this.$msg.mustSelectAnApp,
                            type: 'error'
                        })
                        return
                    }
                    if (this.steps === 3) {
                        if (isNaN(this.rechargeData.value)) {
                            this.$message.error('请输入正确的充值数额！')
                            return
                        }
                        if (this.rechargeData.value === '' || Number(this.rechargeData.value) === 0) {
                            this.$message.error('充值数量不能为空和不能为0！')
                            return
                        }
                        let reg = /^\d*.\d{0,9}$/g
                        if (!reg.test(this.rechargeData.value)) {
                            this.$message.error('充值数额小数点后不能超过9位！')
                            return
                        }
                        if (balance < (Number(this.rechargeData.value) + 1)) {
                            this.$message.error('余额不足以支付充值金额！')
                            return
                        }
                        if (isNaN(this.rechargeData.gasPrice)) {
                            this.$message.error('请输入正确的gasPrice！')
                            return
                        }
                        if (this.rechargeData.gasPrice === '' || Number(this.rechargeData.gasPrice) === 0) {
                            this.$message.error('gasPrice不能为空和不能为0！')
                            return
                        }
                        if (isNaN(this.rechargeData.price1)
                            || isNaN(this.rechargeData.price2)
                            || isNaN(this.rechargeData.price3)) {
                            this.$message.error('请设置正确的下注金额！')
                            return
                        }
                        if (this.rechargeData.price1.trim() === ''
                            || this.rechargeData.price2.trim() === ''
                            || this.rechargeData.price3.trim() === ''
                            || Number(this.rechargeData.price1) === 0
                            || Number(this.rechargeData.price2) === 0
                            || Number(this.rechargeData.price3) === 0) {
                            this.$message.error('下注金额不能为空和不能为0！')
                            return
                        }
                        if (this.rechargeData.price1.length > 2
                            || this.rechargeData.price2.length > 2
                            || this.rechargeData.price3.length > 2) {
                            this.$message.error('每个下注金额不能大于99！')
                            return
                        }
                        this.steps--
                        this.$confirm('确认部署合约吗?', '提示', {
                            confirmButtonText: '确定',
                            cancelButtonText: '取消',
                            type: 'warning'
                        }).then(() => {
                            let users = this.$funs.getLocalAddress()
                            this.publicKey = users.addresses[users.active]
                            if (this.$store.state.userPassword === "") {
                                this.$prompt(`请输入${this.publicKey}的密码`, '提示', {
                                    inputType: 'password',
                                    confirmButtonText: '确定',
                                    cancelButtonText: '取消',
                                }).then(({value}) => {
                                    this.password = value
                                    try {
                                        this.$web3.personal.unlockAccount(this.publicKey, value, 6000000)
                                        this.$confirm('是否临时保存密码，页面在刷新或者关闭后自动清除', '提示', {
                                            confirmButtonText: '确定',
                                            cancelButtonText: '取消',
                                            type: 'warning'
                                        }).then(() => {
                                            this.$store.commit("setPassword", value)
                                            this.migration(this.publicKey, value)
                                        }).catch(() => {
                                            this.migration(this.publicKey, value)
                                        })
                                    } catch (err) {
                                        this.$message.error(String(err))
                                    }
                                }).catch((error) => {
                                    console.log(error)
                                })
                            } else {
                                this.migration(this.publicKey, this.$store.state.userPassword)
                            }
                        }).catch((err) => {
                            if (err !== 'cancel') {
                                this.$message.error(String(err))
                            }
                        })
                    }
                    this.steps++
                } else {
                    let a = document.getElementById('linkToApp')
                    a.click()
                }
            },
            selectAnApp(type, index) {
                this.selected = type
                for (let c = 0; c < this.appList.length; c++) {
                    this.$refs.funcs[c].selectApp(index)
                }
            },
            /**
             * 充值
             */
            recharge() {
                let users = this.$funs.getLocalAddress()
                this.publicKey = users.addresses[users.active]
                this.rechargeFun(this.publicKey, this.$store.state.userPassword === "" ? this.password : this.$store.state.userPassword)
            },
            //
            rechargeFun(user, value) {
                this.$web3.personal.unlockAccount(user, value)
                let MyContract = this.$web3.eth.contract(
                    playGameContract.abi,//selected等于1,龙虎斗
                )
                let myContractInstance = MyContract.at(this.contractAddress)
                // 转账 到合约账户，返回交易hash
                let hash = myContractInstance.deposit({
                    from: user,
                    value: this.$web3.toWei(this.rechargeData.value, 'ether'),
                    gasPrice: this.rechargeData.gasPrice * Math.pow(10, 9),
                    gas: this.$web3.eth.estimateGas({data: playGameContract.bytecode})
                })
                if (hash) {
                    let txObj = this.$web3.eth.getTransaction(hash)
                    this.$axios.post('/api/addTx.php', {
                        "type": "1",
                        "sendAddr": txObj.from,
                        "revAddr": txObj.to,
                        "txHash": txObj.hash,
                    }).then((res) => {
                        if (res.status === 200) {
                            // console.log(res)
                        }
                    }).catch((error) => {
                        this.$message.error(String(error))
                    })
                    let timer = setTimeout(() => {
                        clearTimeout(timer)
                        this.$store.commit('setCryptPercent', {percent: false, text: '充值成功！'})
                        this.steps++
                        this.btnVal = '完成'
                    }, 500)
                }
            },
            /**
             * 部署
             * @param user
             * @param value
             */
            migration(user, value) {
                this.$store.commit('setCryptPercent', {percent: true, text: '创建中···'})
                let MyContract = this.$web3.eth.contract(
                    playGameContract.abi
                )
                // 传入设置的下注金额和类型(1 代表是龙虎斗)
                MyContract.new(Number(this.rechargeData.price1), Number(this.rechargeData.price2), Number(this.rechargeData.price3), Number(this.selected), {
                    // MyContract.new({
                    data: playGameContract.bytecode,
                    from: user,
                    gasPrice: 41000000000,
                    datasourcecode: solSource, // 传入sol源码
                    gas: this.$web3.eth.estimateGas({data: playGameContract.bytecode}) * 2
                }, (err, myContract) => {
                    if (err) {
                        this.$store.commit('setCryptPercent', {percent: false, text: '创建中···'})
                        this.$message.error(String(err))
                    } else {
                        if (myContract.address) {
                            this.$store.commit('setCryptPercent', {percent: true, text: '创建成功！正在充值···'})
                            console.log(myContract.address)
                            // return
                            this.$axios.post('/api/addContract.php', {
                                "type": this.selected,
                                "contractAddr": myContract.address,
                                "createAddr": user,
                                "createMoney": this.rechargeData.value,
                                "txHash": myContract.transactionHash
                            }).then((res) => {
                                if (res.status === 200) {
                                    // console.log(res)
                                }
                            }).catch((error) => {
                                this.$message.error(String(error))
                            })
                            let txObj = this.$web3.eth.getTransaction(myContract.transactionHash)
                            this.$axios.post('/api/addTx.php', {
                                "type": "1",
                                "sendAddr": txObj.from,
                                "revAddr": txObj.to,
                                "txHash": txObj.hash,
                            }).then((res) => {
                                if (res.status === 200) {
                                    // console.log(res)
                                }
                            }).catch((error) => {
                                this.$message.error(String(error))
                            })
                            this.contractAddress = myContract.address
                            localStorage.setItem('contractAddress', myContract.address)
                            // 每次部署完合约，需要向定时器合约中注册当前合约地址
                            let myIntContractInstance = MyContract.at(myContract.address)
                            let hash = myIntContractInstance.registerInterval('0x9d6e9343fd878066337bcaf094260eef7b5202c1', {
                            // let hash = myIntContractInstance.registerInterval('0x7dceb234f633a7a0c821cf31188db701fadecb5b', { // 君哥测试
                                from: user,
                                gasPrice: 41000000000,
                                gas: this.$web3.eth.estimateGas({data: playGameContract.bytecode})
                            })
                            let tHxObj = this.$web3.eth.getTransaction(hash)
                            this.$axios.post('/api/addTx.php', {
                                "type": "1",
                                "sendAddr": tHxObj.from,
                                "revAddr": tHxObj.to,
                                "txHash": tHxObj.hash,
                            }).then((res) => {
                                if (res.status === 200) {
                                    // console.log(res)
                                }
                            }).catch((error) => {
                                this.$message.error(String(error))
                            })
                            sessionStorage.setItem('userContract', myContract.address)
                            this.recharge() // 充值
                            //部署成功！你的合约地址为
                            let host = window.location.host
                            this.contractAddressUrl = `http://${host}/#/appDetail?${myContract.address}`
                        }
                    }
                })
            },
            sendMsgToServer() {
                this.$axios.get('/api/requestContract.php')
                    .then((res) => {
                        if (res.status === 200) {
                            let obj = {
                                contractAddr: "0xfe56582a999c5dae6ba4cc9ea84e9f1842e5fb39",
                                createAddr: "0x8ddb5f0b47a027cea553c58734389dd4ed7ff7f5",
                                currentCoin: "1000000000000.00000000",
                                historyCoin: null,
                                id: "93",
                                time: "2018-05-23 02:25:30",
                                type: "1"
                            }
                            this.appList = [obj]
                            // this.appList = res.data
                        }
                    })
                    .catch((error) => {
                        this.$message.error(String(error))
                        let timer = setTimeout(() => {
                            clearTimeout(timer)
                            this.$message.error('无法获取应用列表')
                        }, 3000)
                    })
            },
            copyAddress($event) {
                let input = document.getElementById('appAddress')
                input.select()
                document.execCommand('copy')
            }
        },
        mounted() {
            // this.sendMsgToServer()
        }
    }
</script>

<style lang="scss" type="text/scss" scoped>
    $title_color: #cec8ff;
    $content_color: #8abdec;

    .create-app {
        .create-header {
            display: flex;
            text-align: center;
            p {
                flex-grow: 1;
                line-height: 70px;
                background-color: #221D44;
                box-shadow: 1px 0px 0px 0px #272345;
                font-size: 20px;
                color: #d3ceff;

            }
            .header-active {
                line-height: 68px;
                border-bottom-style: solid;
                border-bottom-width: 2px;
                border-image-source: linear-gradient(105deg,
                        #3410f7 0%,
                        #711bdc 59%,
                        #ad25c0 100%);
                border-image-slice: 1;
                background-color: #3a346a;
            }
            span {
                display: inline-block;
                height: 70px;
                border-right: 2px solid #272345;
            }
        }

        .create-body {
            padding: 20px 70px;
            font-size: 16px;
            color: #cec8ff;

            .header {
                line-height: 40px;
                font-size: 20px;
                text-align: center;
            }
            .body {
                height: 500px;
                overflow-y: auto;
                padding: 10px;
                border: solid 1px rgba(208, 202, 253, 0.15);
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .step-1 {
                .body {
                    height: 440px;
                }
            }
            .step-3 {
                .content {
                    width: 800px;
                    padding: 30px;
                }
                .explain {
                    border-top: solid 1px #28234D;
                    margin: 20px;
                }
            }
            .step-4 {
                .body {
                    border-left: none;
                    border-top: none;
                    border-right: none;
                    margin-bottom: 30px;
                    .finishe-icon {
                        text-align: center;
                        padding: 100px 0;
                        i {
                            display: inline-block;
                            width: 120px;
                            height: 120px;
                            background: url("../../assets/images/apps/icon_cjcg.png");
                        }
                        p {
                            margin-top: 25px;
                            font-size: 24px;
                            color: #8490c5;
                        }
                    }
                    .finishe-address {
                        .el-button {
                            width: 80%;
                            height: 70px;
                            margin-left: 10px;
                            background-image: linear-gradient(
                                            #443e67,
                                            #443e67),
                            linear-gradient(
                                            #443e67,
                                            #443e67);
                            background-blend-mode: normal, normal;
                            font-size: 26px;
                            color: #bbb3ef;
                            border: none;
                        }
                    }
                }
            }
        }
        .create-footer {
            position: relative;
            width: 800px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            .el-button:last-child {
                margin-left: 50px;
            }
        }
    }
</style>