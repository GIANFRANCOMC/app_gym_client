 <!-- <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-center align-middle">
                    <th class="bg-secondary text-white fw-semibold" style="width: 5%;">#</th>
                    <th class="bg-secondary text-white fw-semibold" style="width: 30%;">ACTIVO</th>
                    <th class="bg-secondary text-white fw-semibold min-w-150px" style="width: 15%;">CANTIDAD</th>
                    <th class="bg-secondary text-white fw-semibold min-w-150px" style="width: 15%;">VALOR DE ADQUISICIÓN</th>
                    <th class="bg-secondary text-white fw-semibold min-w-150px" style="width: 15%;">FECHA DE ADQUISICIÓN</th>
                    <th class="bg-secondary text-white fw-semibold min-w-150px" style="width: 20%;">ESTADO</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0 bg-white">
                <template v-if="lists.entity.extras.loading">
                    <tr class="text-center">
                        <td colspan="99" class="py-4">
                            <Loader/>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <template v-if="lists.entity.records.total > 0">
                        <template v-for="(record, indexRecord) in lists.entity.records.data" :key="record.branch_asset_id">
                            <tr class="text-center">
                                <td v-text="indexRecord + 1"></td>
                                <td class="text-start">
                                    <div class="d-flex justify-content-start mb-1">
                                        <span class="badge bg-danger" v-if="record.branch_asset_quantity <= 0">Sin existencias</span>
                                        <span class="badge bg-warning" v-else-if="record.branch_asset_quantity < 5">Hay pocas existencias</span>
                                        <span class="badge bg-success" v-else-if="record.branch_asset_quantity >= 5">Con existencias disponibles</span>
                                    </div>
                                    <span v-text="record.asset_name" class="text-dark fw-bold d-block"></span>
                                    <span v-text="record.asset_internal_code" class="fst-italic d-block small"></span>
                                    <div v-if="isDefined({value: record?.asset_description})" class="mt-1 fst-italic text-muted small">
                                        <span class="colon-at-end">Descripción</span>
                                        <span v-text="record?.asset_description" class="ms-1"></span>
                                    </div>
                                </td>
                                <td>
                                    <InputNumber
                                        v-model="record.branch_asset_quantity"/>
                                </td>
                                <td>
                                    <InputNumber
                                        v-model="record.branch_asset_acquisition_value">
                                        <template v-slot:inputGroupPrepend>
                                            <button v-if="isDefined({value: record?.currencies_sign})" class="btn btn-primary waves-effect px-2" type="button">
                                                <small v-text="record?.currencies_sign"></small>
                                            </button>
                                            <button v-else class="btn btn-secondary waves-effect px-2" type="button">
                                                <small>Nuevo</small>
                                            </button>
                                        </template>
                                    </InputNumber>
                                </td>
                                <td>
                                    <InputDate
                                        v-model="record.branch_asset_acquisition_date"/>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start" v-for="status in statuses" :key="status.code">
                                        <div class="form-check">
                                            <label class="cursor-pointer text-nowrap">
                                                <input v-model="record.branch_asset_status" class="form-check-input" type="radio" :value="status.code">
                                                <span v-text="status?.label" class="fw-semibold"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <template v-else>
                        <tr>
                            <td class="text-center" colspan="99">
                                <WithoutData type="image"/>
                            </td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center d-none" v-if="!lists.entity.extras.loading && lists.entity.records?.total > 0">
        <Paginator :links="lists.entity.records.links" @clickPage="listEntity"/>
    </div> -->

if(["createUpdateEntity"].includes(functionName)) {

    result.msg = [];

    const isDescriptive = ["descriptive"].includes(extras?.type);

    if(!this.isDefined({value: form?.branch_id})) {

        result.msg.push(`<b>Sucursal:</b> ${this.config.forms.errors.labels.required}`);
        result.bool = false;

    }

    if((form.items).length === 0) {

        result.msg.push(`<b>Registros:</b> ${this.config.forms.errors.labels.required}`);
        result.bool = false;

    }

    for(const [indexRecord, record] of Object.entries(form.items)) {

        const seq = parseInt(indexRecord) + 1;

        if(!Utils.isNumber({value: form?.branch_asset_quantity ?? 0})) {

            result.msg.push(`<b>Activo #${seq}:</b> Cantidad / ${this.config.forms.errors.labels.min_equal_number_0}`);
            result.bool = false;

        }

        if(!Utils.isNumber({value: form?.branch_asset_acquisition_value ?? 0})) {

            result.msg.push(`<b>Activo #${seq}:</b> Valor de adquisición / ${this.config.forms.errors.labels.min_equal_number_0}`);
            result.bool = false;

        }

    }

}


/* $list = Asset::leftJoin("branch_assets", function($join) use($branch) {

                        $join->on("branch_assets.asset_id", "=", "assets.id")
                             ->where("branch_assets.branch_id", $branch->id);

                      })
                      ->leftJoin("currencies", function($join) use($branch) {

                        $join->on("currencies.id", "=", "branch_assets.currency_id");

                      })
                      ->select(
                        "assets.id AS asset_id",
                        "assets.name AS asset_name",
                        "assets.description AS asset_description",
                        "assets.internal_code AS asset_internal_code",
                        DB::raw('COALESCE(branch_assets.id, "") as branch_asset_id'),
                        DB::raw('COALESCE(currencies.sign, "") as currencies_sign'),
                        DB::raw('COALESCE(branch_assets.quantity, 0) as branch_asset_quantity'),
                        DB::raw('COALESCE(branch_assets.acquisition_value, 0) as branch_asset_acquisition_value'),
                        DB::raw('COALESCE(branch_assets.acquisition_date, "") as branch_asset_acquisition_date'),
                        DB::raw('COALESCE(branch_assets.note, "") as branch_asset_note'),
                        DB::raw('COALESCE(branch_assets.status, "active") as branch_asset_status')
                      )
                      ->orderBy("assets.name", "ASC")
                      ->paginate($request->per_page ?? Utilities::$per_page_max);

        $list->getCollection()->transform(function($item) use($branch) {

            $item->branch_id = $branch->id;

            if(!Utilities::isDefined($item->branch_asset_id)) {

                $item->branch_asset_id                = Utilities::generateCode(15);
                $item->branch_asset_quantity          = 0;
                $item->branch_asset_acquisition_value = 0;
                $item->branch_asset_acquisition_date  = "";
                $item->branch_asset_note              = "";
                $item->branch_asset_status            = "active";

            }

            return $item;

        });

        return $list; */
