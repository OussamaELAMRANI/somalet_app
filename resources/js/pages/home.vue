<template>
    <div id="home">

        <main class="container">
            <div class="row">

            </div>
<!--            <div class="row">-->
<!--                <div class="col">-->
<!--                    &lt;!&ndash;                    <kalendar :configuration="calendar_settings" :appointments="appointments"/>&ndash;&gt;-->
<!--                    <Calendar :startDate="new Date()" :dateData="data" class="segment">-->
<!--                        <div slot="header-left">-->
<!--                            <div class="btn-group">-->
<!--                                <Button class="btn btn-sm btn-outline-primary">month</Button>-->
<!--                                <Button class="btn btn-sm btn-outline-primary">week</Button>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div :class="['calendar-item', { 'is-otherMonth': !isCurMonth }]" slot-scope="item">-->
<!--                            <div :class="['calendar-item-date']">-->
<!--                                {{item.date.date}}-->
<!--                            </div>-->
<!--                            <div class="calendar-item-name">{{item.data.title}}</div>-->
<!--                        </div>-->
<!--                    </Calendar>-->
<!--                </div>-->
<!--            </div>-->

            <div class="row">
                <div class="col-8 segment">
                    <GChart
                        type="ColumnChart"
                        :data="chartData"
                        :options="chartOptions"
                    />
                </div>
                <div class="col-4">
                    <Charty class="segment"/>
                </div>
            </div>
        </main>
        <div class="right-bottom">
            <radial-menu
                style="margin: auto; margin-top: 300px; background-color: #2fa360; color: white"
                :itemSize="50"
                :radius="90"
                :angle-restriction="150">
                <radial-menu-item
                    v-for="(item, index) in items"
                    :key="index"
                    style="background-color: #563d7d"
                    @click="() => handleClick(item)">
                    <span>{{item}}</span>
                </radial-menu-item>
            </radial-menu>
            <div style="color: rgba(0,0,0,0.6); margin-top: 16px;">{{ lastClicked }}</div>
        </div>
    </div>
</template>

<script>
    import LeftBar from "../components/Admin/LeftBar";
    import {RadialMenu, RadialMenuItem} from 'vue-radial-menu'
    import {GChart} from 'vue-google-charts'
    // Calender (2)
    // import component
    import 'vue2-event-calendar/default.css'
    import Calendar from 'vue2-event-calendar'
    import Charty from '../components/Charts/CommitChart.js'

    export default {
        name: "home",
        data() {
            return {
                items: ['Calen','Chart','Histo'],
                lastClicked: 'Ajouter au bord',
                //    Charts =======================
                chartData: [
                    ['Year', 'Sales', 'Expenses', 'Profit'],
                    ['2014', 1000, 400, 200],
                    ['2015', 1170, 460, 250],
                    ['2016', 660, 1120, 300],
                    ['2017', 1030, 540, 350]
                ],
                chartOptions: {
                    chart: {
                        title: 'Company Performance',
                        subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                    }
                },
            }
        },
        methods: {
            handleClick(item) {
                this.lastClicked = item;
            },
            renderHeader({prev, next, selectedDate}) {
                const h = this.$createElement

                const prevButton = h('div', {
                    on: {
                        click: prev
                    }
                }, ['prev'])

                const nextButton = h('div', {
                    on: {
                        click: next
                    }
                }, ['next'])

                return h('div', [prevButton, selectedDate, nextButton])
            }

        },
        components: {LeftBar, RadialMenu, RadialMenuItem, GChart, Calendar, Charty}
    }
</script>

<style scoped>
    main.main {
        margin-left: 160px;
    }

    .right-bottom {
        position: fixed;
        bottom: 10px;
        right: 50px;
    }
</style>
