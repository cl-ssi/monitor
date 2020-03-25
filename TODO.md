TODO:
- Agregar un Log de cambios.
- Que no se duplique el RUT.
- Agregar pdf con resultado.

Perfil Laboratorio:
- No puede editar Epivigila, Paho flu y estado

Perfil Epidemilogos:
- NO puede editar Resultado IFD, Subtipo, PCR,
- Puede editar datos de demograficos.

Permisos:
+ Dirección (SEREMI)
+ Telefono (SEREMI)
+ Correo Electrónico (SEREMI)
+ Fecha Nacimiento (SEREMI)
- Centro que notifica (SEREMI)
- Fecha toma muestra (comuna)
- Fecha entrega de muestra a lab (comuna)
- Fecha envío a ISP, (lab)
- Fecha resultado IFD (lab)
- Fecha notificacion paciente (SEREMI)  
- Fecha de resultado COVID (Lab->Epidemiologos)
- Fecha entrega a resultado paciente (comuna)
- Contrareferencia (epidemiologo)
- Licencia médica (comuna)
- Origen toma de muestra (quién completa?)
- Centro que notifica (quién completa?)


Apuntes:
@foreach($log->diferencesArray as $key => $diference)
    {{ $key }} => {{ $diference}} <br>
@endforeach

Funcionarios Hospital:
Cesar Carú (lis hospital), francia, fabiola
