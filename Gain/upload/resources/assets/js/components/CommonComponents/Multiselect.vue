<template>
    <div class="dropdown-search-select">
        <div class="search-filter-dropdown" :class="{'disabled':data.disabled}">

            <div class="chips-dropdown dropdown">

                <div class="p-2 chips-container"
                     id="dropdownMenuLink"
                     data-toggle="dropdown" @click="startNavigation">

                    <span class="chips d-inline-block mr-2 mb-2" v-for="(chip, index) in selectedOptions" :key="index">
                        <span class="mx-3">{{chip}}</span>
                        <span class="delete-chips" @click.prevent="deleteChips($event, index)">
                            <i class="las la-times"></i>
                        </span>
                    </span> <span class="add">+ {{ trans('lang.add') }}</span>

                </div>

                <div class="dropdown-menu py-0" aria-labelledby="dropdownMenuLink">

                    <div class="form-group form-group-with-search p-4">
                        <span class="form-control-feedback">
                            <i class="las la-search"/>
                        </span>

                        <input type="text"
                               ref="searchInput"
                               :class="'form-control'"
                               v-bind:value="searchValue"
                               @input="getSearchValue($event)"
                               :placeholder="data.placeholder"
                               @keydown.down="navigateDown"
                               @keydown.up="navigateUp"
                               @keydown.enter.prevent="enterSelectedValue"
                               :autofocus="startNavigation">
                    </div>

                    <div class="dropdown-divider my-0"/>
                    <div class="dropdown-search-result-wrapper" ref="optionList">
                        <a v-for="(option, index) in options"
                           :key="index"
                           class="dropdown-item"
                           :class="{'active':index==activeIndex, 'disabled selected': isChipSelected(option.id), 'disabled':option.disabled}"
                           href="#" @click.prevent="addChips(option)">
                            {{ option[data.listValueField] }}
                            <span class="check-sign float-right">
                                <i class="las la-check"/>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MultiSelect",
        props: {
            data: {
                default: null
            },
            value:{}
        },
        data() {
            return {
                fieldValue: '',
                inputFieldId: '',
                searchValue: "",
                activeIndex: -1,
                navigationStart: false,
            }
        },
        computed: {
            selectedOptions() {
                let arr = [],
                    instance = this;
                for (let item of instance.value) {
                    for (let smItem of instance.data.list) {
                        if (item == smItem.id && !arr.includes(smItem[instance.data.listValueField])) {
                            arr.push(smItem[instance.data.listValueField])
                        }
                    }
                }
                return arr;
            },
            options() {
                let instance = this;
                if (!_.isEmpty(instance.searchValue)) {
                    return instance.data.list.filter(option => {
                        return option[this.data.listValueField].toLowerCase().includes(instance.searchValue.toLowerCase());
                    });
                } else return instance.data.list;
            }
        },
        methods: {
            getSearchValue($event) {
                this.searchValue = $event.target.value;
            },
            startNavigation() {
                this.activeIndex = -1;
                this.navigationStart = true;
                setTimeout(() => {
                    this.$refs.searchInput.focus();
                    this.$refs.optionList.scrollTop = 0;
                }, 300);
            },
            navigateUp() {
                if (this.activeIndex >= 1) {

                    this.activeIndex--;
                    let sH = this.$refs.optionList.scrollHeight;
                    let aH = this.$refs.optionList.offsetHeight;
                    if (sH > aH) {
                        //need to adjust scroll height
                        let adS = this.activeIndex == 0 ? 0 : this.$refs.optionList.scrollTop - (sH - aH) / (this.options.length);
                        this.$refs.optionList.scrollTo(0, adS)
                    }
                }
            },
            navigateDown() {
                if (this.activeIndex < this.options.length - 1 && this.activeIndex > -2) {

                    this.activeIndex++;
                    let sH = this.$refs.optionList.scrollHeight;
                    let aH = this.$refs.optionList.offsetHeight;

                    if (sH > aH && this.activeIndex > 0) {
                        //need to adjust scroll height
                        let adS = this.activeIndex == this.options.length - 1 ? (sH - aH) : this.$refs.optionList.scrollTop + (sH - aH) / (this.options.length);
                        this.$refs.optionList.scrollTo(0, adS)
                    }
                }
            },
            enterSelectedValue() {
                this.options.filter((option, index) => {
                    if (index == this.activeIndex) {
                        this.addChips(option)
                    }
                });
                this.endNavigation();
            },
            endNavigation() {
                this.activeIndex = -1;
                this.navigationStart = false;
                $(".dropdown-menu").removeClass('show');
            },
            isChipSelected(value) {
                return this.selectedOptions.includes(value);
            },
            addChips(chip) {
                this.value.push(chip.id);
                this.changed();
                this.searchValue = "";
            },
            deleteChips(event, index) {
                event.stopPropagation();
                this.value.splice(index, 1);
                this.changed();
            },
            changed() {
                this.$emit('input', this.value);
            }
        }
    }
</script>

