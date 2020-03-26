TODO:
- Agregar un Log de cambios.
- Agregar pdf con resultado.

Perfil Laboratorio:
- No puede editar Epivigila, Paho flu y estado

Perfil Epidemilogos:
- NO puede editar Resultado IFD, Subtipo, PCR,
- Puede editar datos de demograficos.

Permisos:
+ Fecha envío a ISP (lab)
+ Fecha resultado IFD (lab)
+ Fecha de resultado COVID (Lab->Epidemiologos)
+ Fecha toma muestra (lab o comuna?)

+ Dirección (SEREMI)
+ Telefono (SEREMI)
+ Correo Electrónico (SEREMI)
+ Fecha Nacimiento (SEREMI)

- Centro que notifica (SEREMI)
- Fecha notificacion paciente (SEREMI)

- Fecha recepción en Laboratorio (laboratorio)
- Fecha toma de muestra (comuna)
- Fecha entrega a resultado paciente (comuna)
- Licencia médica (comuna)

- Contrareferencia (coumna)



Apuntes:
@foreach($log->diferencesArray as $key => $diference)
    {{ $key }} => {{ $diference}} <br>
@endforeach

Funcionarios Hospital:
Cesar Carú (lis hospital), francia, fabiola
