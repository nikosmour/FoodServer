<div>
    <table class="table text-center  table-hover table-col-to-row-sm caption-top">
        <caption>{{ __((isset($caption))?$caption:'table') }}</caption>
        @if((count($models)!=0))
            <thead class="thead-dark">
            <tr>
                @foreach ($models[0]->getAttributes() as $key=> $value)
                    <th scope="col">{{$key}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach ($models as $model)
                <tr>
                    @foreach ($model->getAttributes() as $value)
                        <td>{{$value}}</td>
                    @endforeach
                </tr>
                @foreach($model->getRelations() as $name=>$relation)
                    @if(!is_null($relation))
                        <tr>
                            <td></td>
                            <td colspan="{{count($model->getAttributes())-1}}">
                                @include('components.modelToTable',['models'=>(is_countable($relation))?$relation: [$relation],'caption'=>$name ])
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
            </tbody>
        @endif
    </table>
</div>
